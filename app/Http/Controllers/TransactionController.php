<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use App\Models\CreditCard;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);

        // Calculate previous and next months
        $date = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $previousMonth = $date->copy()->subMonth()->month;
        $previousYear = $date->copy()->subMonth()->year;
        $nextMonth = $date->copy()->addMonth()->month;
        $nextYear = $date->copy()->addMonth()->year;

        // Get available years (e.g., last 5 years to next year)
        $years = range(now()->subYears(5)->year, now()->addYear()->year);

        // Months list in Portuguese
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        // Get transactions for the selected month
        $query = Transaction::with(['category', 'account', 'creditCard', 'creditCardInvoice'])
            ->where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear);

        // Filtrar por status de pagamento
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filtrar por tipo de transação (cartão de crédito ou conta)
        if ($request->has('transaction_type')) {
            if ($request->transaction_type === 'credit_card') {
                $query->whereNotNull('credit_card_id');
            } elseif ($request->transaction_type === 'account') {
                $query->whereNull('credit_card_id');
            }
        }

        $transactions = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get user accounts for payment
        $accounts = Account::where('user_id', $user->id)
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact(
            'transactions',
            'currentMonth',
            'currentYear',
            'previousMonth',
            'previousYear',
            'nextMonth',
            'nextYear',
            'months',
            'years',
            'accounts'
        ));
    }

    public function create()
    {
        if (!auth()->user()->checkTransactionLimit()) {
            return redirect()->route('plans.index')
                ->with('error', 'Você atingiu o limite de contas para seu plano atual. Faça um upgrade para adicionar mais contas.');
        }
        $categories = Category::where('user_id', auth()->id())->get();
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('transactions.create', compact('categories', 'accounts'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string|max:255',
                'amount' => 'required|string',
                'date' => 'required|date',
                'type' => 'required|in:income,expense',
                'category_id' => 'required|exists:categories,id',
                'account_id' => 'required|exists:accounts,id',
                'payment_status' => 'required|in:pending,paid'
            ]);

            // Converte o valor de moeda para decimal
            $amount = str_replace(['R$', '.', ','], ['', '', '.'], $request->amount);

            DB::beginTransaction();

            $transaction = Transaction::create([
                'description' => $request->description,
                'amount' => $amount,
                'date' => $request->date,
                'type' => $request->type,
                'category_id' => $request->category_id,
                'account_id' => $request->account_id,
                'payment_status' => $request->payment_status,
                'user_id' => auth()->id()
            ]);

            // Se a transação for marcada como paga, atualiza o saldo da conta
            if ($request->payment_status === 'paid') {
                $account = Account::findOrFail($request->account_id);
                if ($request->type === 'income') {
                    $account->balance += (float) $amount;
                } else {
                    $account->balance -= (float) $amount;
                }
                $account->save();
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transação criada com sucesso!',
                    'transaction' => $transaction
                ]);
            }

            return redirect()->back()->with('success', 'Transação criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar transação: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()->with('error', 'Erro ao criar transação: ' . $e->getMessage());
        }
    }

    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        return view('transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $categories = Category::where('user_id', auth()->id())->get();
        $accounts = Account::where('user_id', auth()->id())->get();

        return view('transactions.edit', compact('transaction', 'categories', 'accounts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transação atualizada com sucesso.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transação excluída com sucesso.');
    }

    public function pay(Transaction $transaction, Request $request)
    {
        try {
            DB::beginTransaction();

            $account = Account::findOrFail($request->account_id);
            
            // Verifica se a conta tem saldo suficiente para pagamento (apenas para despesas)
            if ($transaction->type === 'expense' && $account->balance < $transaction->amount) {
                return redirect()->back()->with('error', 'Saldo insuficiente na conta selecionada.');
            }

            $transaction->markAsPaid($account);

            DB::commit();

            $actionType = $transaction->type === 'expense' ? 'paga' : 'recebida';
            return redirect()->back()->with('success', "Transação {$actionType} com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao processar a transação: ' . $e->getMessage());
        }
    }

    public function checkOverdueTransactions()
    {
        $user = auth()->user();
        
        // Busca todas as transações pendentes vencidas
        $overdueTransactions = Transaction::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->where('date', '<', now())
            ->get();

        foreach ($overdueTransactions as $transaction) {
            $transaction->checkOverdue();
        }

        return redirect()->back()->with('success', 'Status das transações atualizado com sucesso!');
    }
} 
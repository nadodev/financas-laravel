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
        $transactions = Transaction::with(['category', 'account'])
            ->where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->orderBy('date', 'desc')
            ->paginate(10);

        // Calculate current balance (sum of all accounts)
        $currentBalance = $user->accounts()->sum('balance');

        // Calculate summary for the selected month
        $income = Transaction::where('user_id', $user->id)
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $expenses = Transaction::where('user_id', $user->id)
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $balance = $income - $expenses;

        // Get categories and accounts for modals
        $categories = Category::where('user_id', $user->id)->get();
        $accounts = Account::where('user_id', $user->id)->get();

        return view('transactions.index', compact(
            'transactions',
            'income',
            'expenses',
            'balance',
            'currentBalance',
            'currentMonth',
            'currentYear',
            'previousMonth',
            'previousYear',
            'nextMonth',
            'nextYear',
            'months',
            'years',
            'categories',
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
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'is_recurring' => 'boolean',
            'recurrence_interval' => 'nullable|required_if:is_recurring,true|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|required_if:is_recurring,true|date|after:date',
        ]);

        // Garantir que is_recurring seja um booleano
        $validated['is_recurring'] = filter_var($validated['is_recurring'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // Se não for recorrente, limpar os campos de recorrência
        if (!$validated['is_recurring']) {
            $validated['recurrence_interval'] = null;
            $validated['recurrence_end_date'] = null;
        }

        $validated['user_id'] = auth()->id();
        $transaction = Transaction::create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transação criada com sucesso.');
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
} 
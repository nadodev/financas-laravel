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
use Illuminate\Support\Facades\Storage;
use App\Notifications\TransactionCreated;
use App\Http\Requests\StoreTransactionRequest;
use App\Notifications\TransactionOverdue;

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

        $startDate = Carbon::createFromDate($currentYear, $currentMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

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

        $years = range(now()->subYears(5)->year, now()->addYear()->year);

        $query = Transaction::with(['category', 'account'])
            ->where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString(); 

            $categories = Category::where('user_id', auth()->id())->get();

            $accounts = Account::where('user_id', auth()->id())->get();

        return view('transactions.index', compact(
            'transactions',
            'currentMonth',
            'currentYear',
            'months',
            'years',
            'categories',
            'accounts'
        ));
    }

    public function create()
    {
        return view('transactions.form', [
            'categories' => auth()->user()->categories,
            'accounts' => auth()->user()->accounts,
            'creditCards' => auth()->user()->creditCards
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $data['user_id'] = auth()->id();
            
            $data['amount'] = (float) $data['amount'];

            // Verifica saldo se for despesa e estiver paga
            if ($data['type'] === 'expense' && $data['status'] === 'paid') {
                $account = Account::findOrFail($data['account_id']);
                if ($account->balance < $data['amount']) {
                    throw new \Exception('Saldo insuficiente para realizar esta despesa.');
                }
            }

            // Processa anexo antes de criar a transação
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('attachments/transactions', 'public');
                $data['attachment'] = $path;
            }

            // Se for uma transação recorrente, marca como pai
            if ($data['recurring'] ?? false) {
                $data['is_recurring_parent'] = true;
            }

            // Cria a transação principal
            $transaction = Transaction::create($data);

            // Atualiza o saldo da conta se a transação estiver paga
            if ($data['status'] === 'paid') {
                $account = Account::findOrFail($data['account_id']);
                if ($data['type'] === 'income') {
                    $account->balance += $data['amount'];
                } else {
                    $account->balance -= $data['amount'];
                }
                $account->save();
            }

            // Processa recorrência
            if ($data['recurring'] ?? false) {
                $this->createRecurringTransactions($transaction);
            }

            // Processa parcelamento
            if ($data['installment'] ?? false) {
                $transaction->installment = true;
                $transaction->total_installments = (int) $data['total_installments'];
                $transaction->current_installment = 1;
                $transaction->save();

                // Cria as parcelas futuras
                $installmentAmount = $data['amount'] / $data['total_installments'];
                for ($i = 2; $i <= $data['total_installments']; $i++) {
                    $installmentDate = Carbon::parse($data['date'])->addMonths($i - 1);
                    
                    Transaction::create([
                        'description' => $data['description'] . " (Parcela {$i}/{$data['total_installments']})",
                        'amount' => $installmentAmount,
                        'date' => $installmentDate,
                        'type' => $data['type'],
                        'category_id' => $data['category_id'],
                        'account_id' => $data['account_id'],
                        'status' => 'pending',
                        'user_id' => auth()->id(),
                        'parent_id' => $transaction->id,
                        'installment' => true,
                        'total_installments' => $data['total_installments'],
                        'current_installment' => $i,
                        'attachment' => $data['attachment'] ?? null
                    ]);
                }
            }

            DB::commit();

            // Envia notificação
            auth()->user()->notify(new TransactionCreated($transaction));

            return redirect()
                ->route('transactions.index')
                ->with('success', 'Transação criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Se houve upload de arquivo, remove-o em caso de erro
            if (isset($data['attachment'])) {
                Storage::disk('public')->delete($data['attachment']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar transação: ' . $e->getMessage());
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

    public function update(StoreTransactionRequest $request, Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Se for uma transação recorrente pai
            if ($transaction->is_recurring_parent) {
                // Atualiza os campos de recorrência
                $data['recurring'] = $request->has('recurring');
                $data['recurrence_interval'] = $data['recurring'] ? $data['recurrence_interval'] : null;
                $data['recurrence_end_date'] = $data['recurring'] ? $data['recurrence_end_date'] : null;

                // Se desmarcou recorrência, remove a marcação de pai
                if (!$data['recurring']) {
                    $data['is_recurring_parent'] = false;
                }

                // Se mudou o intervalo ou a data final, recria as recorrências
                if ($data['recurring'] && (
                    $transaction->recurrence_interval != $data['recurrence_interval'] ||
                    $transaction->recurrence_end_date != $data['recurrence_end_date']
                )) {
                    // Exclui todas as recorrências existentes
                    $transaction->allRecurrences()->delete();
                    
                    // Atualiza a transação pai
                    $transaction->update($data);

                    // Recria as recorrências com os novos parâmetros
                    $this->createRecurringTransactions($transaction);

                    DB::commit();
                    return redirect()->route('transactions.index')
                        ->with('success', 'Transação e recorrências atualizadas com sucesso!');
                }
            }

            // Se for uma transação recorrente pai ou filha e pediu para atualizar todas
            if (($transaction->is_recurring_parent || $transaction->is_recurring_child) && $request->has('update_all_recurrences')) {
                $updateData = [
                    'description' => $data['description'],
                    'amount' => $data['amount'],
                    'category_id' => $data['category_id'],
                    'account_id' => $data['account_id'],
                    'type' => $data['type']
                ];

                if ($transaction->is_recurring_parent) {
                    // Atualiza todas as recorrências
                    $transaction->allRecurrences()->update($updateData);
                } else {
                    // Se for uma transação filha, atualiza o pai e todos os irmãos
                    $parent = $transaction->parent;
                    $parent->update($updateData);
                    $parent->allRecurrences()->update($updateData);
                }
            }

            // Processa o anexo
            if ($request->has('remove_attachment') && $request->remove_attachment == '1') {
                // Remove o anexo antigo
                if ($transaction->attachment) {
                    Storage::disk('public')->delete($transaction->attachment);
                    $data['attachment'] = null;
                }
            } elseif ($request->hasFile('attachment')) {
                // Remove o anexo antigo se existir
                if ($transaction->attachment) {
                    Storage::disk('public')->delete($transaction->attachment);
                }

                // Salva o novo anexo
                $path = $request->file('attachment')->store('attachments', 'public');
                $data['attachment'] = $path;
            }

            // Converte o valor para float
            $data['amount'] = (float) str_replace(['.', ','], ['', '.'], $data['amount']);

            // Se mudou o status para pago, verifica o saldo
            if ($data['type'] === 'expense' && $data['status'] === 'paid' && $transaction->status !== 'paid') {
                $account = Account::findOrFail($data['account_id']);
                if ($account->balance < $data['amount']) {
                    throw new \Exception('Saldo insuficiente para realizar esta despesa.');
                }
            }

            // Atualiza a transação
            $transaction->update($data);

            // Atualiza o saldo da conta se necessário
            if ($data['status'] !== $transaction->getOriginal('status') || 
                $data['amount'] !== $transaction->getOriginal('amount') ||
                $data['type'] !== $transaction->getOriginal('type') ||
                $data['account_id'] !== $transaction->getOriginal('account_id')) {
                
                // Reverte a transação antiga se estava paga
                if ($transaction->getOriginal('status') === 'paid') {
                    $oldAccount = Account::findOrFail($transaction->getOriginal('account_id'));
                    if ($transaction->getOriginal('type') === 'income') {
                        $oldAccount->balance -= $transaction->getOriginal('amount');
                    } else {
                        $oldAccount->balance += $transaction->getOriginal('amount');
                    }
                    $oldAccount->save();
                }

                // Aplica a nova transação se estiver paga
                if ($data['status'] === 'paid') {
                    $newAccount = Account::findOrFail($data['account_id']);
                    if ($data['type'] === 'income') {
                        $newAccount->balance += $data['amount'];
                    } else {
                        $newAccount->balance -= $data['amount'];
                    }
                    $newAccount->save();
                }
            }

            DB::commit();
            return redirect()->route('transactions.index')
                ->with('success', 'Transação atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Se houve upload de arquivo novo, remove-o em caso de erro
            if (isset($data['attachment']) && $data['attachment'] !== $transaction->getOriginal('attachment')) {
                Storage::disk('public')->delete($data['attachment']);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar transação: ' . $e->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        
        try {
            DB::beginTransaction();

            // Se a transação estava paga, reverte o saldo da conta
            if ($transaction->status === 'paid') {
                $account = $transaction->account;
                if ($transaction->type === 'income') {
                    $account->balance -= $transaction->amount;
                } else {
                    $account->balance += $transaction->amount;
                }
                $account->save();
            }

            // Remove o anexo se existir
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }
        
        $transaction->delete();

            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transação excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao excluir transação: ' . $e->getMessage());
        }
    }

    public function pay(Transaction $transaction)
    {
        try {
            DB::beginTransaction();

            // Verifica se a transação já está paga
            if ($transaction->status === 'paid') {
                throw new \Exception('Esta transação já está paga.');
            }

            // Verifica se há saldo suficiente para despesas
            if ($transaction->type === 'expense') {
                $account = $transaction->account;
                if ($account->balance < $transaction->amount) {
                    throw new \Exception('Saldo insuficiente para pagar esta despesa.');
                }
            }

            // Atualiza o status da transação
            $transaction->status = 'paid';
            $transaction->save();

            // Atualiza o saldo da conta
            $account = $transaction->account;
            if ($transaction->type === 'income') {
                $account->balance += $transaction->amount;
            } else {
                $account->balance -= $transaction->amount;
            }
            $account->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Transação paga com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Erro ao pagar transação: ' . $e->getMessage());
        }
    }

    public function checkOverdue()
    {
        $overdueTransactions = Transaction::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->where('date', '<', now()->startOfDay())
            ->get();

        foreach ($overdueTransactions as $transaction) {
            // Notifica o usuário sobre transações vencidas
            auth()->user()->notify(new TransactionOverdue($transaction));
        }

        return response()->json(['message' => 'Verificação de transações vencidas realizada com sucesso.']);
    }

    public function removeAttachment(Transaction $transaction, $index)
    {
        $this->authorize('update', $transaction);

        try {
            if ($transaction->removeAttachment($index)) {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false, 'message' => 'Anexo não encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function createRecurringTransactions(Transaction $transaction)
    {
        if (!$transaction->recurring || !$transaction->recurrence_interval) {
            return;
        }

        $currentDate = Carbon::parse($transaction->date);
        $endDate = $transaction->recurrence_end_date 
            ? Carbon::parse($transaction->recurrence_end_date)
            : $currentDate->copy()->addYear(); // Se não tiver data fim, cria por 1 ano

        while ($currentDate->lt($endDate)) {
            $currentDate = $currentDate->addDays($transaction->recurrence_interval);

            if ($currentDate->gt($endDate)) {
                break;
            }

            $transaction->recurrences()->create([
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'date' => $currentDate,
                'type' => $transaction->type,
                'category_id' => $transaction->category_id,
                'account_id' => $transaction->account_id,
                'status' => 'pending',
                'user_id' => $transaction->user_id,
                'recurring' => true,
                'recurrence_interval' => $transaction->recurrence_interval,
                'recurrence_end_date' => $transaction->recurrence_end_date,
                'next_recurrence_date' => null,
            ]);
        }
    }
} 
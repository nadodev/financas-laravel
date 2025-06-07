<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Account;
use App\Models\CreditCard;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'start_date',
            'end_date',
            'type',
            'category_id',
            'account_id',
            'credit_card_id'
        ]);

        $transactions = $this->transactionService->getTransactions(auth()->id(), $filters);

        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $creditCards = CreditCard::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'accounts',
            'creditCards'
        ));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $creditCards = CreditCard::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('transactions.create', compact('categories', 'accounts', 'creditCards'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'payment_method' => 'required|in:account,credit_card',
            'account_id' => 'required_if:payment_method,account|exists:accounts,id',
            'credit_card_id' => 'required_if:payment_method,credit_card|exists:credit_cards,id',
            'notes' => 'nullable|max:1000',
            'is_recurring' => 'boolean',
            'installments' => 'nullable|required_if:is_recurring,true|integer|min:2|max:24',
        ]);

        $validated['user_id'] = auth()->id();

        try {
            $this->transactionService->createTransaction($validated);
            return redirect()->route('transactions.index')
                ->with('success', 'Transação criada com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Erro ao criar transação: ' . $e->getMessage()]);
        }
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        $creditCards = CreditCard::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact(
            'transaction',
            'categories',
            'accounts',
            'creditCards'
        ));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'description' => 'required|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'payment_method' => 'required|in:account,credit_card',
            'account_id' => 'required_if:payment_method,account|exists:accounts,id',
            'credit_card_id' => 'required_if:payment_method,credit_card|exists:credit_cards,id',
            'notes' => 'nullable|max:1000',
        ]);

        try {
            $this->transactionService->updateTransaction($transaction, $validated);
            return redirect()->route('transactions.index')
                ->with('success', 'Transação atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Erro ao atualizar transação: ' . $e->getMessage()]);
        }
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        try {
            $this->transactionService->deleteTransaction($transaction);
            return redirect()->route('transactions.index')
                ->with('success', 'Transação excluída com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Erro ao excluir transação: ' . $e->getMessage()]);
        }
    }
} 
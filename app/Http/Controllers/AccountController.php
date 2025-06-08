<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    protected $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['type', 'bank', 'balance']);
        
        $data = $this->accountService->getAccountsWithStats(
            auth()->id(),
            $filters
        );

        return view('accounts.index', $data);
    }

    public function create()
    {
        // Verifica se o usuário pode criar mais contas
        if (!auth()->user()->checkAccountLimit()) {
            return redirect()->route('plans.index')
                ->with('error', 'Você atingiu o limite de contas para seu plano atual. Faça um upgrade para adicionar mais contas.');
        }

        $types = $this->accountService->getAccountTypes();
        return view('accounts.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Account::$types)),
            'balance' => 'required|numeric|min:0',
            'bank_name' => 'nullable|max:255',
            'agency' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'notes' => 'nullable|max:1000',
        ]);

        try {
            $this->accountService->createAccount($validated, auth()->id());
            return redirect()->route('accounts.index')
                ->with('success', 'Conta criada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao criar conta: ' . $e->getMessage());
        }
    }

    public function show(Account $account)
    {
        $this->authorize('view', $account);

        $transactions = $account->transactions()
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('accounts.show', compact('account', 'transactions'));
    }

    public function edit(Account $account)
    {
        $this->authorize('update', $account);
        $types = $this->accountService->getAccountTypes();
        return view('accounts.edit', compact('account', 'types'));
    }

    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Account::$types)),
            'bank_name' => 'nullable|max:255',
            'agency' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'notes' => 'nullable|max:1000',
            'balance' => 'required|numeric|min:0',
        ]);

        try {
            $this->accountService->updateAccount($account, $validated);
            return redirect()->route('accounts.index')
                ->with('success', 'Conta atualizada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar conta: ' . $e->getMessage());
        }
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        try {
            $this->accountService->deleteAccount($account);
            return redirect()->route('accounts.index')
                ->with('success', 'Conta excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('accounts.index')
                ->with('error', $e->getMessage());
        }
    }
} 
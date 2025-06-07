<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $query = Account::where('user_id', auth()->id());

        // Filtro por tipo
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtro por banco
        if ($request->filled('bank')) {
            $query->where('bank', 'like', '%' . $request->bank . '%');
        }

        // Filtro por saldo
        if ($request->filled('balance')) {
            if ($request->balance === 'positive') {
                $query->where('balance', '>=', 0);
            } else if ($request->balance === 'negative') {
                $query->where('balance', '<', 0);
            }
        }

        $accounts = $query->orderBy('name')->paginate(10);

        // Calcular totais para o resumo
        $totalBalance = $accounts->sum('balance');
        $totalAccounts = $accounts->count();
        $negativeAccounts = $accounts->where('balance', '<', 0)->count();

        return view('accounts.index', compact(
            'accounts',
            'totalBalance',
            'totalAccounts',
            'negativeAccounts'
        ));
    }

    public function create()
    {
        $types = Account::$types;
        return view('accounts.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Account::$types)),
            'initial_balance' => 'required|numeric|min:0',
            'bank' => 'nullable|max:255',
            'agency' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'notes' => 'nullable|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['balance'] = $validated['initial_balance'];
        unset($validated['initial_balance']);

        Account::create($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Conta criada com sucesso!');
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
        $types = Account::$types;
        return view('accounts.edit', compact('account', 'types'));
    }

    public function update(Request $request, Account $account)
    {
        $this->authorize('update', $account);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:' . implode(',', array_keys(Account::$types)),
            'bank' => 'nullable|max:255',
            'agency' => 'nullable|max:255',
            'account_number' => 'nullable|max:255',
            'notes' => 'nullable|max:1000',
        ]);

        $account->update($validated);

        return redirect()->route('accounts.index')
            ->with('success', 'Conta atualizada com sucesso!');
    }

    public function destroy(Account $account)
    {
        $this->authorize('delete', $account);

        // Verificar se existem transações vinculadas
        if ($account->transactions()->exists()) {
            return redirect()->route('accounts.index')
                ->with('error', 'Não é possível excluir uma conta que possui transações vinculadas.');
        }

        $account->delete();

        return redirect()->route('accounts.index')
            ->with('success', 'Conta excluída com sucesso!');
    }
} 
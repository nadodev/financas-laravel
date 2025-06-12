<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use App\Models\Account;
use App\Models\CreditCardInvoice;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $account = $user->accounts()->first();

        if (!$account) {
            // Se o usuário não tem uma conta, cria uma padrão
            $account = $user->accounts()->create([
                'name' => 'Conta Principal',
                'type' => 'checking',
                'balance' => 0,
            ]);
        }

        $creditCards = $account->creditCards;
        return view('credit-cards.index', compact('creditCards'));
    }

    public function create()
    {
        $accounts = auth()->user()->accounts;
        
        if ($accounts->isEmpty()) {
            return redirect()->route('accounts.create')
                ->with('error', 'Você precisa criar uma conta antes de adicionar um cartão de crédito.');
        }

        return view('credit-cards.create', [
            'brands' => CreditCard::$brands,
            'accounts' => $accounts
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:19',
            'brand' => 'required|string|in:' . implode(',', array_keys(CreditCard::$brands)),
            'credit_limit' => 'required|numeric|min:0',
            'closing_day' => 'required|integer|between:1,31',
            'due_day' => 'required|integer|between:1,31',
            'account_id' => 'required|exists:accounts,id'
        ]);

        // Verifica se a conta pertence ao usuário
        $account = auth()->user()->accounts()->findOrFail($validated['account_id']);
        
        // Adiciona o user_id aos dados validados
        $validated['user_id'] = auth()->id();
        
        $creditCard = $account->creditCards()->create($validated);

        return redirect()->route('credit-cards.index')
            ->with('success', 'Cartão de crédito cadastrado com sucesso!');
    }

    public function show(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        return view('credit-cards.show', compact('creditCard'));
    }

    public function edit(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        return view('credit-cards.edit', [
            'creditCard' => $creditCard,
            'brands' => CreditCard::$brands,
            'accounts' => auth()->user()->accounts
        ]);
    }

    public function update(Request $request, CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|in:' . implode(',', array_keys(CreditCard::$brands)),
            'credit_limit' => 'required|numeric|min:0',
            'closing_day' => 'required|integer|between:1,31',
            'due_day' => 'required|integer|between:1,31',
            'account_id' => 'required|exists:accounts,id'
        ]);

        // Verifica se a conta pertence ao usuário
        $account = auth()->user()->accounts()->findOrFail($validated['account_id']);
        $creditCard->update($validated);

        return redirect()->route('credit-cards.index')
            ->with('success', 'Cartão de crédito atualizado com sucesso!');
    }

    public function destroy(CreditCard $creditCard)
    {
        $this->authorize('delete', $creditCard);
        $creditCard->delete();

        return redirect()->route('credit-cards.index')
            ->with('success', 'Cartão de crédito excluído com sucesso!');
    }

    public function showConfirmPassword(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        return view('credit-cards.confirm-password', compact('creditCard'));
    }

    public function confirmPassword(Request $request, CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);

        $request->validate([
            'password' => 'required|string',
        ]);

        if (!$creditCard->verifyPassword($request->password)) {
            return back()->withErrors([
                'password' => 'A senha informada está incorreta.',
            ]);
        }

        $creditCard->markPasswordAsConfirmed();

        return redirect()->intended(
            route('credit-cards.show', $creditCard)
        );
    }

    public function closeInvoice(CreditCardInvoice $invoice)
    {
        try {
            if ($invoice->status !== 'open') {
                throw new \Exception('Apenas faturas abertas podem ser fechadas.');
            }

            $invoice->markAsClosed();

            return redirect()->back()->with('success', 'Fatura fechada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function payInvoice(Request $request, CreditCardInvoice $invoice)
    {
        try {
            $request->validate([
                'account_id' => 'required|exists:accounts,id'
            ]);

            $account = Account::findOrFail($request->account_id);
            
            if ($account->balance < $invoice->total_amount) {
                throw new \Exception('Saldo insuficiente na conta selecionada.');
            }

            $invoice->markAsPaid($account);

            return redirect()->back()->with('success', 'Fatura paga com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function invoices(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        
        $invoices = $creditCard->invoices()
            ->orderBy('reference_year', 'desc')
            ->orderBy('reference_month', 'desc')
            ->paginate(12);

        return view('credit-cards.invoices', [
            'creditCard' => $creditCard,
            'invoices' => $invoices
        ]);
    }
} 
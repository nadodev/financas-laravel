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
        
        // Carrega as faturas do cartão
        $invoices = $creditCard->invoices()
            ->orderBy('reference_year', 'desc')
            ->orderBy('reference_month', 'desc')
            ->paginate(10);
        
        return view('credit-cards.show', compact('creditCard', 'invoices'));
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

    public function showInvoices(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        
        $invoices = $creditCard->invoices()
            ->orderBy('reference_year', 'desc')
            ->orderBy('reference_month', 'desc')
            ->paginate(10);
            
        return view('credit-cards.invoices', compact('creditCard', 'invoices'));
    }

    public function closeInvoice(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        
        // Lógica para fechar a fatura atual
        $currentInvoice = $creditCard->getCurrentInvoice();
        $currentInvoice->close();
        
        return redirect()->back()->with('success', 'Fatura fechada com sucesso!');
    }

    public function payInvoice(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        
        // Lógica para pagar a fatura atual
        $currentInvoice = $creditCard->getCurrentInvoice();
        $currentInvoice->pay();
        
        return redirect()->back()->with('success', 'Fatura paga com sucesso!');
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
} 
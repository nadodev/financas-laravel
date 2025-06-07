<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use App\Models\Account;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    public function index()
    {
        $creditCards = CreditCard::with('account')
            ->where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('credit-cards.index', compact('creditCards'));
    }

    public function create()
    {
        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
        $brands = CreditCard::$brands;

        return view('credit-cards.create', compact('accounts', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'number' => 'required|max:255',
            'expiration_date' => 'required|date',
            'credit_limit' => 'required|numeric|min:0',
            'closing_day' => 'required|integer|min:1|max:31',
            'due_day' => 'required|integer|min:1|max:31',
            'account_id' => 'required|exists:accounts,id',
            'brand' => 'required|in:' . implode(',', array_keys(CreditCard::$brands)),
        ]);

        $validated['user_id'] = auth()->id();

        CreditCard::create($validated);

        return redirect()->route('credit-cards.index')
            ->with('success', 'Cartão de crédito criado com sucesso!');
    }

    public function edit(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);

        $accounts = Account::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();
        $brands = CreditCard::$brands;

        return view('credit-cards.edit', compact('creditCard', 'accounts', 'brands'));
    }

    public function update(Request $request, CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'number' => 'required|max:255',
            'expiration_date' => 'required|date',
            'credit_limit' => 'required|numeric|min:0',
            'closing_day' => 'required|integer|min:1|max:31',
            'due_day' => 'required|integer|min:1|max:31',
            'account_id' => 'required|exists:accounts,id',
            'brand' => 'required|in:' . implode(',', array_keys(CreditCard::$brands)),
        ]);

        $creditCard->update($validated);

        return redirect()->route('credit-cards.index')
            ->with('success', 'Cartão de crédito atualizado com sucesso!');
    }

    public function destroy(CreditCard $creditCard)
    {
        $this->authorize('delete', $creditCard);

        // Verificar se existem transações vinculadas
        if ($creditCard->transactions()->exists()) {
            return redirect()->route('credit-cards.index')
                ->with('error', 'Não é possível excluir um cartão que possui transações vinculadas.');
        }

        $creditCard->delete();

        return redirect()->route('credit-cards.index')
            ->with('success', 'Cartão de crédito excluído com sucesso!');
    }

    public function invoices(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);

        $invoices = $creditCard->invoices()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('credit-cards.invoices', compact('creditCard', 'invoices'));
    }

    public function currentInvoice(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);

        $invoice = $creditCard->getCurrentInvoice();
        $transactions = $invoice->transactions()
            ->with('category')
            ->orderBy('date')
            ->get();

        return view('credit-cards.current-invoice', compact('creditCard', 'invoice', 'transactions'));
    }

    public function closeInvoice(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);

        $invoice = $creditCard->getCurrentInvoice();
        $invoice->close();

        return redirect()->route('credit-cards.invoices', $creditCard)
            ->with('success', 'Fatura fechada com sucesso!');
    }

    public function payInvoice(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);

        $invoice = $creditCard->getCurrentInvoice();
        $invoice->markAsPaid();

        return redirect()->route('credit-cards.invoices', $creditCard)
            ->with('success', 'Fatura marcada como paga com sucesso!');
    }
} 
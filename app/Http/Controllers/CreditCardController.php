<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use App\Models\Account;
use App\Models\CreditCardInvoice;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    public function index()
    {
        $creditCards = auth()->user()->creditCards()->with(['currentInvoice'])->get();
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

    public function show(CreditCard $creditCard)
    {
        $invoices = $creditCard->invoices()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('credit-cards.show', compact('creditCard', 'invoices'));
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
} 
<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\CreditCardInvoice;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $start = Carbon::parse($request->start)->startOfDay();
        $end = Carbon::parse($request->end)->endOfDay();
        $today = Carbon::today();

        // Buscar transações no período
        $transactions = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$start, $end])
            ->with(['category'])
            ->get();

        // Buscar faturas de cartão no período
        $creditCardInvoices = CreditCardInvoice::whereHas('creditCard', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->with(['creditCard'])
        ->whereBetween('due_date', [$start, $end])
        ->get();

        $events = [];

        // Adicionar transações ao calendário
        foreach ($transactions as $transaction) {
            $isOverdue = $transaction->payment_status === 'pending' && $transaction->date < $today;
            
            // Define a cor baseada no tipo e status
            $color = match(true) {
                $isOverdue => '#991B1B', // Vermelho escuro para atrasados
                $transaction->type === 'income' && $transaction->payment_status === 'paid' => '#10B981', // Verde para receitas pagas
                $transaction->type === 'income' => '#059669', // Verde mais escuro para receitas pendentes
                $transaction->payment_status === 'paid' => '#EF4444', // Vermelho para despesas pagas
                default => '#DC2626' // Vermelho mais escuro para despesas pendentes
            };

            $status = match(true) {
                $isOverdue => '(Atrasado)',
                $transaction->payment_status === 'paid' => '(Pago)',
                default => '(Pendente)'
            };

            $amount = number_format($transaction->amount, 2, ',', '.');
            $category = $transaction->category ? " - {$transaction->category->name}" : '';
            $icon = $isOverdue ? '⚠️ ' : '';

            $events[] = [
                'id' => 'transaction_' . $transaction->id,
                'title' => "{$icon}{$transaction->description}{$category} - R$ {$amount} {$status}",
                'start' => $transaction->date->format('Y-m-d'),
                'color' => $color,
                'url' => route('transactions.index', ['date' => $transaction->date->format('Y-m-d')]),
                'textColor' => '#FFF',
                'allDay' => true,
                'extendedProps' => [
                    'isOverdue' => $isOverdue,
                    'type' => $transaction->type,
                    'paymentStatus' => $transaction->payment_status
                ]
            ];
        }

        // Adicionar faturas de cartão ao calendário
        foreach ($creditCardInvoices as $invoice) {
            $isOverdue = $invoice->status === 'overdue' || 
                        ($invoice->status === 'open' && $invoice->due_date < $today);
            
            $amount = number_format($invoice->total_amount, 2, ',', '.');
            $status = match(true) {
                $isOverdue => '(Vencida)',
                $invoice->status === 'open' => '(Aberta)',
                $invoice->status === 'closed' => '(Fechada)',
                $invoice->status === 'paid' => '(Paga)',
                default => ''
            };

            $color = match(true) {
                $isOverdue => '#991B1B',    // Vermelho escuro
                $invoice->status === 'open' => '#3B82F6',    // Azul
                $invoice->status === 'closed' => '#F59E0B',  // Amarelo
                $invoice->status === 'paid' => '#10B981',    // Verde
                default => '#6B7280'    // Cinza
            };

            $icon = $isOverdue ? '⚠️ ' : '';

            $events[] = [
                'id' => 'invoice_' . $invoice->id,
                'title' => "{$icon}Fatura {$invoice->creditCard->name} - R$ {$amount} {$status}",
                'start' => $invoice->due_date->format('Y-m-d'),
                'color' => $color,
                'url' => route('credit-cards.show', $invoice->creditCard),
                'textColor' => '#FFF',
                'allDay' => true,
                'extendedProps' => [
                    'isOverdue' => $isOverdue,
                    'status' => $invoice->status
                ]
            ];
        }

        return response()->json($events);
    }
}

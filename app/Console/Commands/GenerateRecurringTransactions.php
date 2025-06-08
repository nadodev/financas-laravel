<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\CreditCard;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRecurringTransactions extends Command
{
    protected $signature = 'transactions:generate-recurring';
    protected $description = 'Generate recurring transactions and monthly invoices';

    public function handle()
    {
        $this->info('Iniciando geração de transações recorrentes...');

        // Gera transações recorrentes
        $recurringTransactions = Transaction::where('is_recurring', true)
            ->where('next_recurrence_date', '<=', now())
            ->whereNull('parent_transaction_id')
            ->get();

        foreach ($recurringTransactions as $transaction) {
            $newTransaction = $transaction->generateNextRecurrence();
            if ($newTransaction) {
                $newTransaction->save();
                $this->info("Transação recorrente gerada: {$newTransaction->description}");
            }
        }

        // Gera faturas mensais de cartão de crédito
        $creditCards = CreditCard::all();
        foreach ($creditCards as $creditCard) {
            $closingDate = Carbon::now()->addMonth()->setDay($creditCard->closing_day);
            $dueDate = Carbon::now()->addMonth()->setDay($creditCard->due_day);

            // Verifica se já existe uma fatura para este mês
            $existingInvoice = $creditCard->invoices()
                ->where('closing_date', $closingDate)
                ->exists();

            if (!$existingInvoice) {
                $invoice = $creditCard->invoices()->create([
                    'closing_date' => $closingDate,
                    'due_date' => $dueDate,
                    'amount' => 0, // Será atualizado conforme as transações forem adicionadas
                    'status' => 'open'
                ]);
                $this->info("Fatura criada para o cartão {$creditCard->name}");
            }
        }

        $this->info('Geração de transações recorrentes concluída!');
    }
} 
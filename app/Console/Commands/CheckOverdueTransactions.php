<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Notifications\TransactionOverdue;
use Illuminate\Console\Command;

class CheckOverdueTransactions extends Command
{
    protected $signature = 'transactions:check-overdue';
    protected $description = 'Check for overdue transactions and send notifications';

    public function handle()
    {
        $overdueTransactions = Transaction::where('status', 'pending')
            ->where('date', '<', now())
            ->get();

        foreach ($overdueTransactions as $transaction) {
            $transaction->user->notify(new TransactionOverdue($transaction));
            $this->info("Notificação enviada para transação #{$transaction->id}");
        }

        $this->info("Total de notificações enviadas: " . $overdueTransactions->count());
    }
} 
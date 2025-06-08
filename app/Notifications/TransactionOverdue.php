<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TransactionOverdue extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        $channels = ['mail'];
        
        if ($notifiable->pushSubscriptions()->exists()) {
            $channels[] = WebPushChannel::class;
        }
        
        return $channels;
    }

    public function toMail($notifiable)
    {
        $type = $this->transaction->type === 'income' ? 'Receita' : 'Despesa';
        $amount = number_format($this->transaction->amount, 2, ',', '.');
        $date = $this->transaction->date->format('d/m/Y');
        $diasAtraso = $this->transaction->date->diffInDays(now());

        return (new MailMessage)
            ->subject("{$type} Vencida")
            ->greeting("Olá {$notifiable->name}!")
            ->line("Você tem uma {$type} vencida há {$diasAtraso} dias.")
            ->line("Descrição: {$this->transaction->description}")
            ->line("Valor: R$ {$amount}")
            ->line("Data de Vencimento: {$date}")
            ->action('Ver Detalhes', route('transactions.show', $this->transaction))
            ->line('Por favor, regularize esta situação o mais breve possível.');
    }

    public function toWebPush($notifiable, $notification)
    {
        $type = $this->transaction->type === 'income' ? 'Receita' : 'Despesa';
        $amount = number_format($this->transaction->amount, 2, ',', '.');
        $diasAtraso = $this->transaction->date->diffInDays(now());

        return (new WebPushMessage)
            ->title("{$type} Vencida")
            ->icon('/notification-icon.png')
            ->body("{$type} de R$ {$amount} está vencida há {$diasAtraso} dias: {$this->transaction->description}")
            ->action('Ver Detalhes', route('transactions.show', $this->transaction))
            ->data(['url' => route('transactions.show', $this->transaction)]);
    }
} 
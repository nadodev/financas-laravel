<?php

namespace App\Notifications;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TransactionCreated extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject("Nova {$type} Registrada")
            ->greeting("Olá {$notifiable->name}!")
            ->line("Uma nova {$type} foi registrada em sua conta.")
            ->line("Descrição: {$this->transaction->description}")
            ->line("Valor: R$ {$amount}")
            ->line("Data: {$date}")
            ->line("Status: {$this->transaction->status_text}")
            ->action('Ver Detalhes', route('transactions.show', $this->transaction))
            ->line('Obrigado por usar nosso aplicativo!');
    }

    public function toWebPush($notifiable, $notification)
    {
        $type = $this->transaction->type === 'income' ? 'Receita' : 'Despesa';
        $amount = number_format($this->transaction->amount, 2, ',', '.');

        return (new WebPushMessage)
            ->title("Nova {$type} Registrada")
            ->icon('/notification-icon.png')
            ->body("Uma nova {$type} de R$ {$amount} foi registrada: {$this->transaction->description}")
            ->action('Ver Detalhes', route('transactions.show', $this->transaction))
            ->data(['url' => route('transactions.show', $this->transaction)]);
    }
} 
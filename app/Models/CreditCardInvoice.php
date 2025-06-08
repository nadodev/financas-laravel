<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreditCardInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'credit_card_id',
        'month',
        'year',
        'closing_date',
        'due_date',
        'amount',
        'status',
    ];

    protected $casts = [
        'closing_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
    ];

    // Status disponíveis para a fatura
    public static $statuses = [
        'open' => 'Em Aberto',
        'closed' => 'Fechada',
        'paid' => 'Paga',
        'overdue' => 'Vencida',
    ];

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function close()
    {
        if ($this->status !== 'open') {
            throw new \Exception('Apenas faturas em aberto podem ser fechadas.');
        }

        $this->amount = $this->transactions()->sum('amount');
        $this->status = 'closed';
        $this->save();

        // Marca todas as transações da fatura como pagas
        $this->transactions()->update([
            'payment_status' => 'paid',
            'payment_date' => Carbon::now()
        ]);

        // Cria a próxima fatura
        $nextClosingDate = $this->closing_date->copy()->addMonth();
        $nextDueDate = $this->due_date->copy()->addMonth();

        $this->creditCard->invoices()->create([
            'month' => $nextClosingDate->month,
            'year' => $nextClosingDate->year,
            'closing_date' => $nextClosingDate,
            'due_date' => $nextDueDate,
            'amount' => 0,
            'status' => 'open',
        ]);

        return $this;
    }

    public function markAsClosed()
    {
        if ($this->status === 'open') {
            $this->status = 'closed';
            $this->closing_date = now();
            $this->save();
        }
    }

    public function markAsPaid(Account $paidWithAccount)
    {
        if ($this->status !== 'closed') {
            throw new \Exception('Apenas faturas fechadas podem ser pagas.');
        }

        DB::transaction(function () use ($paidWithAccount) {
            $this->status = 'paid';
            $this->payment_date = now();
            $this->paid_with_account_id = $paidWithAccount->id;
            $this->save();

            // Atualiza o saldo da conta
            $paidWithAccount->balance -= $this->total_amount;
            $paidWithAccount->save();
        });
    }

    public static function getCurrentOpenInvoice($creditCardId)
    {
        return static::where('credit_card_id', $creditCardId)
                    ->where('status', 'open')
                    ->orderBy('created_at', 'desc')
                    ->first();
    }

    public static function getOrCreateOpenInvoice($creditCardId, $dueDate)
    {
        $openInvoice = static::getCurrentOpenInvoice($creditCardId);
        
        if (!$openInvoice) {
            $openInvoice = static::create([
                'credit_card_id' => $creditCardId,
                'due_date' => $dueDate,
                'status' => 'open',
                'total_amount' => 0
            ]);
        }

        return $openInvoice;
    }

    public function checkOverdue()
    {
        if ($this->status === 'closed' && $this->due_date->isPast()) {
            $this->status = 'overdue';
            $this->save();
        }

        return $this;
    }
} 
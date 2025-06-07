<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function markAsPaid()
    {
        if ($this->status !== 'closed' && $this->status !== 'overdue') {
            throw new \Exception('Apenas faturas fechadas ou vencidas podem ser pagas.');
        }

        // Cria uma transação de pagamento na conta vinculada ao cartão
        Transaction::create([
            'description' => 'Pagamento da fatura ' . $this->month . '/' . $this->year . ' - ' . $this->creditCard->name,
            'amount' => $this->amount,
            'date' => Carbon::now(),
            'type' => 'expense',
            'category_id' => Category::where('name', 'Cartão de Crédito')->first()->id,
            'user_id' => $this->creditCard->user_id,
            'account_id' => $this->creditCard->account_id,
        ]);

        $this->status = 'paid';
        $this->save();

        return $this;
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
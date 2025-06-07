<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'number',
        'expiration_date',
        'credit_limit',
        'closing_day',
        'due_day',
        'user_id',
        'account_id',
        'brand',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'credit_limit' => 'decimal:2',
    ];

    // Bandeiras de cartão disponíveis
    public static $brands = [
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'amex' => 'American Express',
        'elo' => 'Elo',
        'other' => 'Outra',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function invoices()
    {
        return $this->hasMany(CreditCardInvoice::class);
    }

    public function getCurrentInvoice()
    {
        $now = Carbon::now();
        $closingDate = Carbon::create($now->year, $now->month, $this->closing_day);
        
        // Se já passou do dia de fechamento, a fatura atual é do próximo mês
        if ($now->day > $this->closing_day) {
            $closingDate->addMonth();
        }

        // Calcula a data de vencimento
        $dueDate = Carbon::create($closingDate->year, $closingDate->month, $this->due_day);
        if ($this->due_day < $this->closing_day) {
            $dueDate->addMonth();
        }

        // Busca ou cria a fatura
        $invoice = $this->invoices()
            ->where('month', $closingDate->month)
            ->where('year', $closingDate->year)
            ->first();

        if (!$invoice) {
            $invoice = $this->invoices()->create([
                'month' => $closingDate->month,
                'year' => $closingDate->year,
                'closing_date' => $closingDate,
                'due_date' => $dueDate,
                'amount' => 0,
                'status' => 'open',
            ]);
        }

        return $invoice;
    }

    public function getAvailableLimit()
    {
        $currentInvoice = $this->getCurrentInvoice();
        return $this->credit_limit - $currentInvoice->amount;
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'number',
        'brand',
        'credit_limit',
        'closing_day',
        'due_day',
        'account_id',
    ];

    protected $hidden = [
        'number',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
    ];

    // Bandeiras de cartão disponíveis
    public static $brands = [
        'visa' => 'Visa',
        'mastercard' => 'Mastercard',
        'amex' => 'American Express',
        'elo' => 'Elo',
        'hipercard' => 'Hipercard',
    ];

    // Mutators para criptografar dados sensíveis
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = Crypt::encryptString($value);
    }

    // Accessors para descriptografar dados sensíveis
    public function getNumberAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    // Método para obter número mascarado do cartão
    public function getMaskedNumberAttribute()
    {
        $number = $this->number;
        return str_repeat('*', strlen($number) - 4) . substr($number, -4);
    }

    // Método para obter a fatura atual
    public function getCurrentInvoice()
    {
        $now = Carbon::now();
        
        // Procura uma fatura aberta para o mês atual
        $invoice = $this->invoices()
            ->where('reference_month', $now->month)
            ->where('reference_year', $now->year)
            ->where('status', 'open')
            ->first();
            
        // Se não encontrar, cria uma nova fatura
        if (!$invoice) {
            $closingDate = Carbon::create($now->year, $now->month, $this->closing_day);
            $dueDate = Carbon::create($now->year, $now->month, $this->due_day);
            
            // Se o dia de fechamento já passou, a fatura é para o próximo mês
            if ($now->day > $this->closing_day) {
                $closingDate->addMonth();
                $dueDate->addMonth();
            }
            
            $invoice = $this->invoices()->create([
                'reference_month' => $closingDate->month,
                'reference_year' => $closingDate->year,
                'closing_date' => $closingDate,
                'due_date' => $dueDate,
                'amount' => 0,
                'status' => 'open'
            ]);
        }
        
        return $invoice;
    }

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

    public function currentInvoice()
    {
        return $this->hasOne(CreditCardInvoice::class)
            ->where(function ($query) {
                $today = Carbon::now();
                
                // Se hoje é depois do dia de fechamento, pega a fatura do próximo mês
                if ($today->day > $this->closing_day) {
                    $query->where('reference_month', $today->addMonth()->month)
                          ->where('reference_year', $today->year);
                } else {
                    // Senão, pega a fatura do mês atual
                    $query->where('reference_month', $today->month)
                          ->where('reference_year', $today->year);
                }
            })
            ->withDefault([
                'amount' => 0,
                'status' => 'open',
                'reference_month' => Carbon::now()->month,
                'reference_year' => Carbon::now()->year,
            ]);
    }

    public function getAvailableLimit()
    {
        $usedLimit = $this->transactions()->whereMonth('date', now()->month)
                         ->whereYear('date', now()->year)
                         ->sum('amount');
        return $this->credit_limit - $usedLimit;
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'balance',
        'user_id',
        'description',
        'bank_name',
        'account_number',
        'agency',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    protected $appends = ['type_formatted'];

    // Tipos de conta disponíveis
    public static $types = [
        'checking' => 'Conta Corrente',
        'savings' => 'Conta Poupança',
        'investment' => 'Conta Investimento',
        'wallet' => 'Carteira',
    ];

    public function getTypeFormattedAttribute()
    {
        return self::$types[$this->type] ?? $this->type;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function creditCards()
    {
        return $this->hasMany(CreditCard::class);
    }

    // Atualiza o saldo da conta
    public function updateBalance()
    {
        $income = $this->transactions()
            ->where('type', 'income')
            ->sum('amount');

        $expenses = $this->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $this->balance = $income - $expenses;
        $this->save();
    }
} 
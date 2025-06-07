<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
        'type',
        'category_id',
        'user_id',
        'account_id',
        'credit_card_id',
        'credit_card_invoice_id',
        'notes',
        'is_recurring',
        'installments',
        'current_installment',
        'total_installments',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function creditCard()
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function creditCardInvoice()
    {
        return $this->belongsTo(CreditCardInvoice::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Ao criar uma transação
        static::created(function ($transaction) {
            if ($transaction->account_id) {
                $account = $transaction->account;
                if ($transaction->type === 'income') {
                    $account->balance += $transaction->amount;
                } else {
                    $account->balance -= $transaction->amount;
                }
                $account->save();
            }

            if ($transaction->credit_card_invoice_id) {
                $invoice = $transaction->creditCardInvoice;
                $invoice->amount += $transaction->amount;
                $invoice->save();
            }
        });

        // Ao atualizar uma transação
        static::updated(function ($transaction) {
            if ($transaction->isDirty('amount') || $transaction->isDirty('type') || $transaction->isDirty('account_id')) {
                // Se mudou de conta, restaura o saldo da conta antiga
                if ($transaction->getOriginal('account_id')) {
                    $oldAccount = Account::find($transaction->getOriginal('account_id'));
                    if ($transaction->getOriginal('type') === 'income') {
                        $oldAccount->balance -= $transaction->getOriginal('amount');
                    } else {
                        $oldAccount->balance += $transaction->getOriginal('amount');
                    }
                    $oldAccount->save();
                }

                // Atualiza o saldo da nova conta
                if ($transaction->account_id) {
                    $account = $transaction->account;
                    if ($transaction->type === 'income') {
                        $account->balance += $transaction->amount;
                    } else {
                        $account->balance -= $transaction->amount;
                    }
                    $account->save();
                }
            }

            // Atualiza o valor da fatura se necessário
            if ($transaction->isDirty('amount') || $transaction->isDirty('credit_card_invoice_id')) {
                if ($transaction->getOriginal('credit_card_invoice_id')) {
                    $oldInvoice = CreditCardInvoice::find($transaction->getOriginal('credit_card_invoice_id'));
                    $oldInvoice->amount -= $transaction->getOriginal('amount');
                    $oldInvoice->save();
                }

                if ($transaction->credit_card_invoice_id) {
                    $invoice = $transaction->creditCardInvoice;
                    $invoice->amount += $transaction->amount;
                    $invoice->save();
                }
            }
        });

        // Ao excluir uma transação
        static::deleted(function ($transaction) {
            if ($transaction->account_id) {
                $account = $transaction->account;
                if ($transaction->type === 'income') {
                    $account->balance -= $transaction->amount;
                } else {
                    $account->balance += $transaction->amount;
                }
                $account->save();
            }

            if ($transaction->credit_card_invoice_id) {
                $invoice = $transaction->creditCardInvoice;
                $invoice->amount -= $transaction->amount;
                $invoice->save();
            }
        });
    }
} 
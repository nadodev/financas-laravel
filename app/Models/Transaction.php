<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'recurrence_interval',
        'recurrence_end_date',
        'payment_status',
        'payment_date',
        'paid_with_account_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
        'is_recurring' => 'boolean',
        'recurrence_end_date' => 'datetime',
        'payment_date' => 'datetime',
    ];

    protected $with = ['category', 'account'];

    // Status de pagamento disponíveis
    public static $paymentStatuses = [
        'pending' => 'Pendente',
        'paid' => 'Pago',
        'overdue' => 'Vencido',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function paidWithAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'paid_with_account_id');
    }

    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function creditCardInvoice(): BelongsTo
    {
        return $this->belongsTo(CreditCardInvoice::class);
    }

    public function markAsPaid(Account $paidWithAccount)
    {
        $this->payment_status = 'paid';
        $this->payment_date = now();
        $this->paid_with_account_id = $paidWithAccount->id;
        $this->save();

        // Atualiza o saldo da conta usada para pagar/receber
        if ($this->type === 'expense') {
            $paidWithAccount->balance -= $this->amount;
        } else {
            $paidWithAccount->balance += $this->amount;
        }
        $paidWithAccount->save();
    }

    public function getActionButtonTextAttribute()
    {
        return $this->type === 'expense' ? 'Pagar' : 'Receber';
    }

    public function getActionDescriptionAttribute()
    {
        if ($this->type === 'expense') {
            return 'Pagar ' . $this->description;
        } else {
            return 'Receber ' . $this->description;
        }
    }

    public function getStatusTextAttribute()
    {
        $baseStatus = self::$paymentStatuses[$this->payment_status];
        if ($this->type === 'income') {
            switch ($this->payment_status) {
                case 'pending':
                    return 'A Receber';
                case 'paid':
                    return 'Recebido';
                case 'overdue':
                    return 'Atrasado';
                default:
                    return $baseStatus;
            }
        }
        return $baseStatus;
    }

    public function checkOverdue()
    {
        if ($this->payment_status === 'pending' && $this->date->isPast()) {
            $this->payment_status = 'overdue';
            $this->save();
        }
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
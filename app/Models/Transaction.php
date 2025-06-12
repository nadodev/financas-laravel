<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'amount',
        'date',
        'type',
        'category_id',
        'account_id',
        'status',
        'user_id',
        'attachment',
        'recurring',
        'recurrence_interval',
        'recurrence_end_date',
        'next_recurrence_date',
        'parent_id',
        'installment',
        'total_installments',
        'current_installment',
        'is_recurring_parent',
        'credit_card_id',
        'credit_card_invoice_id',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
        'recurring' => 'boolean',
        'recurrence_interval' => 'integer',
        'recurrence_end_date' => 'date',
        'next_recurrence_date' => 'date',
        'installment' => 'boolean',
        'total_installments' => 'integer',
        'current_installment' => 'integer',
        'is_recurring_parent' => 'boolean',
    ];

    protected $with = ['category', 'account'];

    public static $types = [
        'income' => 'Receita',
        'expense' => 'Despesa',
        'transfer' => 'Transferência',
    ];

    public static $statuses = [
        'pending' => 'Pendente',
        'completed' => 'Concluída',
        'cancelled' => 'Cancelada',
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

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'parent_id');
    }

    public function recurrences(): HasMany
    {
        return $this->hasMany(Transaction::class, 'parent_id')->where('recurring', true);
    }

    public function allRecurrences(): HasMany
    {
        return $this->hasMany(Transaction::class, 'parent_id');
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Transaction::class, 'parent_id')->where('installment', true);
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
        return self::$statuses[$this->status] ?? $this->status;
    }

    public function getTypeTextAttribute()
    {
        return $this->type === 'income' ? 'Receita' : 'Despesa';
    }

    public function getAttachmentUrlAttribute()
    {
        return $this->attachment ? Storage::url($this->attachment) : null;
    }

    public function getIsRecurringChildAttribute()
    {
        return $this->recurring && $this->parent_id !== null;
    }

    public function calculateNextRecurrenceDate()
    {
        if (!$this->recurring || !$this->recurrence_interval) {
            return null;
        }

        $lastDate = $this->next_recurrence_date ?? $this->date;
        $nextDate = Carbon::parse($lastDate)->addDays($this->recurrence_interval);

        // Se tiver data final e a próxima data ultrapassar, retorna null
        if ($this->recurrence_end_date && $nextDate->gt($this->recurrence_end_date)) {
            return null;
        }

        return $nextDate;
    }

    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function creditCardInvoice(): BelongsTo
    {
        return $this->belongsTo(CreditCardInvoice::class);
    }

    // Escopo para filtrar transações por período
    public function scopeInPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    // Escopo para filtrar por tipo
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Escopo para filtrar por status
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    protected static function boot()
    {
        parent::boot();

        // Quando uma transação for excluída
        static::deleting(function ($transaction) {
            // Se for uma transação pai recorrente, exclui todas as recorrências
            if ($transaction->is_recurring_parent) {
                $transaction->allRecurrences()->delete();
            }

            // Remove o anexo se existir
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }
        });
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'name',
        'description',
        'target_amount',
        'start_date',
        'target_date',
        'current_amount',
        'status',
        'monthly_amount',
    ];

    protected $casts = [
        'target_date' => 'date',
        'start_date' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'monthly_amount' => 'decimal:2',
    ];

    protected $appends = [
        'progress_percentage',
        'remaining_amount',
        'days_remaining',
        'monthly_required_amount',
    ];

    public static $statuses = [
        'in_progress' => 'Em Andamento',
        'completed' => 'Concluído',
        'cancelled' => 'Cancelado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(FinancialGoalProgress::class)->orderBy('date', 'desc');
    }

    public function getProgressPercentageAttribute(): int
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        $percentage = ($this->current_amount / $this->target_amount) * 100;
        return min(round($percentage), 100);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getDaysRemainingAttribute(): int
    {
        if ($this->status !== 'in_progress') {
            return 0;
        }

        return now()->diffInDays($this->target_date, false);
    }

    public function getMonthlyRequiredAmountAttribute(): float
    {
        if ($this->status !== 'in_progress') {
            return 0;
        }

        $monthsRemaining = max(1, now()->diffInMonths($this->target_date, false));
        return round($this->remaining_amount / $monthsRemaining, 2);
    }

    public function calculateTimeToReachGoal($monthly_amount)
    {
        if ($monthly_amount <= 0) {
            return null;
        }

        $remaining = $this->remaining_amount;
        $months = ceil($remaining / $monthly_amount);
        
        return [
            'months' => $months,
            'estimated_date' => now()->addMonths($months)->format('d/m/Y')
        ];
    }

    public function calculateRequiredMonthlyAmount($months)
    {
        if ($months <= 0) {
            return 0;
        }

        return $this->remaining_amount / $months;
    }

    public function updateProgress($amount)
    {
        $this->current_amount = min($this->target_amount, $this->current_amount + $amount);
        
        if ($this->current_amount >= $this->target_amount) {
            $this->status = 'completed';
        }
        
        $this->save();
    }
} 
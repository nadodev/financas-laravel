<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'start_date',
        'status',
        'user_id',
        'account_id',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'target_date' => 'date',
        'start_date' => 'date',
    ];

    protected $appends = [
        'progress_percentage',
        'remaining_amount',
        'days_remaining',
        'monthly_required_amount',
    ];

    public static $statuses = [
        'in_progress' => 'Em andamento',
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

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(100, round(($this->current_amount / $this->target_amount) * 100, 2));
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    public function getDaysRemainingAttribute(): int
    {
        return max(0, $this->target_date->diffInDays(Carbon::now()));
    }

    public function getMonthlyRequiredAmountAttribute(): float
    {
        if ($this->days_remaining <= 0) {
            return 0;
        }
        
        $months_remaining = ceil($this->days_remaining / 30);
        return $this->remaining_amount / $months_remaining;
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
            'estimated_date' => Carbon::now()->addMonths($months)->format('d/m/Y')
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'amount',
        'month',
        'year',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSpentAttribute()
    {
        return Transaction::where('category_id', $this->category_id)
            ->whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->where('type', 'expense')
            ->sum('amount');
    }

    public function getRemainingAttribute()
    {
        return $this->amount - $this->spent;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->amount <= 0) {
            return 100;
        }
        return min(100, round(($this->spent / $this->amount) * 100));
    }
} 
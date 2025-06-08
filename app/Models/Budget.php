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
        'user_id',
        'category_id',
        'amount',
        'start_date',
        'end_date',
        'recurrence',
        'description'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAttribute()
    {
        $query = Transaction::where('user_id', $this->user_id)
            ->where('category_id', $this->category_id)
            ->where('type', 'expense');

        if ($this->start_date) {
            $query->where('date', '>=', $this->start_date);
        }

        if ($this->end_date) {
            $query->where('date', '<=', $this->end_date);
        }

        return $query->sum('amount');
    }

    public function getRemainingAttribute()
    {
        return max(0, $this->amount - $this->spent);
    }

    public function getPercentageAttribute()
    {
        if ($this->amount <= 0) {
            return 0;
        }
        return ($this->spent / $this->amount) * 100;
    }
} 
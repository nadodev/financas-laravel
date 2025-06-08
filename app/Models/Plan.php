<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public static function getBasicPlan()
    {
        return self::where('slug', 'basic')->first();
    }

    public function getAccountLimitAttribute()
    {
        $limits = [
            'basic' => 2,
            'essential' => 3,
            'flexible' => PHP_INT_MAX,
            'advanced' => PHP_INT_MAX
        ];

        return $limits[$this->slug] ?? 0;
    }
} 
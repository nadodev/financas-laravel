<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visible_sections'
    ];

    protected $casts = [
        'visible_sections' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isVisible($section)
    {
        if (!$this->visible_sections) {
            return true; // Se não houver configurações, mostrar tudo
        }
        return in_array($section, $this->visible_sections);
    }
} 
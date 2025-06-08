<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPlanLimits;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasPlanLimits;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'phone',
        'address',
        'address_number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'zip_code',
        'role',
        'plan_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function financialGoals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function hasFeature($feature)
    {
        if ($this->role === 'admin') {
            return true;
        }
        
        return $this->plan && in_array($feature, $this->plan->features);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function checkAccountLimit()
    {
        return $this->accounts()->count() < $this->plan->account_limit;
    }

    public function isBasicPlan()
    {
        return $this->plan && $this->plan->slug === 'basic';
    }

    public function isEssentialPlan()
    {
        return $this->plan && $this->plan->slug === 'essential';
    }

    public function isFlexiblePlan()
    {
        return $this->plan && $this->plan->slug === 'flexible';
    }

    public function isAdvancedPlan()
    {
        return $this->plan && $this->plan->slug === 'advanced';
    }

    public function dashboardPreference()
    {
        return $this->hasOne(DashboardPreference::class);
    }

    public function dashboardSetting()
    {
        return $this->hasOne(DashboardSetting::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if ($user->role !== 'admin' && !$user->plan_id) {
                $user->plan_id = Plan::getBasicPlan()->id;
            }
        });
    }
}

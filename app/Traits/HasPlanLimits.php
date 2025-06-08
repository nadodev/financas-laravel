<?php

namespace App\Traits;

use App\Models\Transaction;
use Carbon\Carbon;

trait HasPlanLimits
{
    public function checkTransactionLimit()
    {
        if (!$this->plan) {
            return true;
        }

        if ($this->plan->slug === 'advanced') {
            return true;
        }

        $limits = [
            'basic' => 30,
            'essential' => 500,
            'flexible' => 2000
        ];

        $monthlyLimit = $limits[$this->plan->slug] ?? 0;
        
        $transactionCount = $this->transactions()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return $transactionCount < $monthlyLimit;
    }

    public function checkAccountLimit()
    {
        if (!$this->plan) {
            return true;
        }

        if (in_array($this->plan->slug, ['flexible', 'advanced'])) {
            return true;
        }

        $limits = [
            'basic' => 1,
            'essential' => 3
        ];

        $accountLimit = $limits[$this->plan->slug] ?? 0;
        
        $currentAccountCount = $this->accounts()->count();
        
        return $currentAccountCount < $accountLimit;
    }

    public function getRemainingTransactions()
    {
        if (!$this->plan) {
            return 'Ilimitado';
        }

        if ($this->plan->slug === 'advanced') {
            return 'Ilimitado';
        }

        $limits = [
            'basic' => 50,
            'essential' => 500,
            'flexible' => 2000
        ];

        $monthlyLimit = $limits[$this->plan->slug] ?? 0;
        
        $transactionCount = $this->transactions()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        return max(0, $monthlyLimit - $transactionCount);
    }

    public function getRemainingAccounts()
    {
        if (!$this->plan) {
            return 'Ilimitado';
        }

        if (in_array($this->plan->slug, ['flexible', 'advanced'])) {
            return 'Ilimitado';
        }

        $limits = [
            'basic' => 3,
            'essential' => 3
        ];

        $accountLimit = $limits[$this->plan->slug] ?? 0;
        
        return max(0, $accountLimit - $this->accounts()->count());
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
} 
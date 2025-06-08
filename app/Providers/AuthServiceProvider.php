<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Models\Account;
use App\Models\CreditCard;
use App\Policies\CategoryPolicy;
use App\Policies\AccountPolicy;
use App\Policies\CreditCardPolicy;
use App\Models\FinancialGoal;
use App\Policies\FinancialGoalPolicy;
use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use App\Models\Budget;
use App\Policies\BudgetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Transaction::class => TransactionPolicy::class,
        Category::class => CategoryPolicy::class,
        FinancialGoal::class => FinancialGoalPolicy::class,
        Budget::class => BudgetPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
} 
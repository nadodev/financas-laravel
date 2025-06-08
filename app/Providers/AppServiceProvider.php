<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;
use App\Repositories\FinancialGoalRepository;
use App\Repositories\FinancialGoalRepositoryInterface;
use App\Services\FinancialGoalService;
use App\Repositories\AccountRepository;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use App\Services\AccountService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);

        // Register Financial Goals Repository
        $this->app->bind(FinancialGoalRepository::class, function ($app) {
            return new FinancialGoalRepository();
        });

        // Register Financial Goals Service
        $this->app->bind(FinancialGoalService::class, function ($app) {
            return new FinancialGoalService(
                $app->make(FinancialGoalRepository::class)
            );
        });

        // Account
        $this->app->bind(AccountRepositoryInterface::class, function ($app) {
            return new AccountRepository($app->make('App\Models\Account'));
        });

        $this->app->bind(AccountService::class, function ($app) {
            return new AccountService($app->make(AccountRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

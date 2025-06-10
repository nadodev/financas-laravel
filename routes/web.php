<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\InvestmentController;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\DashboardSettingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ExpenseAnalyticsController;
use App\Http\Controllers\PushSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingPageController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/performance', [PerformanceController::class, 'index'])->name('performance.index');

    // Transactions
    Route::resource('transactions', TransactionController::class);
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])->name('transactions.pay');
    Route::post('/transactions/check-overdue', [TransactionController::class, 'checkOverdue'])->name('transactions.check-overdue');
    Route::delete('/transactions/{transaction}/attachments/{index}', [TransactionController::class, 'removeAttachment'])->name('transactions.remove-attachment');

    Route::resource('categories', CategoryController::class);

    // Contas bancárias - Listagem disponível para todos, criação limitada pelo plano
    Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::get('accounts/create', [AccountController::class, 'create'])->name('accounts.create');
    Route::get('accounts/{account}', [AccountController::class, 'show'])->name('accounts.show');
    Route::get('accounts/{account}/edit', [AccountController::class, 'edit'])->name('accounts.edit');
    Route::put('accounts/{account}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('accounts/{account}', [AccountController::class, 'destroy'])->name('accounts.destroy');
    
    // Rota de criação com verificação de limite
    Route::middleware(['check.account.limit'])->group(function () {
        Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
    });

    // Rotas que requerem plano Flexível ou superior
    Route::middleware(['check.plan.features:investments'])->group(function () {
        Route::resource('investments', InvestmentController::class);
    });

    // Rotas que requerem plano Avançado
    Route::middleware(['check.plan.features:api_access'])->group(function () {
        Route::get('api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
    });

    // Credit Cards
    Route::resource('credit-cards', CreditCardController::class);
    Route::post('/credit-cards/invoices/{invoice}/close', [CreditCardController::class, 'closeInvoice'])->name('credit-cards.invoices.close');
    Route::post('/credit-cards/invoices/{invoice}/pay', [CreditCardController::class, 'payInvoice'])->name('credit-cards.invoices.pay');

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/export-pdf', [ReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
        Route::get('/{type}/export/{format}', [ReportController::class, 'export'])->name('export');
        Route::get('/{type}', [ReportController::class, 'show'])->name('show');
    });

    Route::resource('budgets', BudgetController::class);

    // Financial Goals
    Route::resource('financial-goals', FinancialGoalController::class);
    Route::post('financial-goals/{financialGoal}/progress', [FinancialGoalController::class, 'updateProgress'])
        ->name('financial-goals.update-progress');

    // Plans & Subscriptions
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/subscription', [SubscriptionController::class, 'create'])->name('subscription.create');
    Route::put('/subscription', [SubscriptionController::class, 'update'])->name('subscription.update');
    Route::delete('/subscription', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');

    // Dashboard Settings
    Route::get('/settings/dashboard', [DashboardSettingController::class, 'edit'])
        ->name('settings.dashboard');
    Route::put('/settings/dashboard', [DashboardSettingController::class, 'update'])
        ->name('settings.dashboard.update');
        
    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    // Rotas para análise de gastos
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/expenses', [ExpenseAnalyticsController::class, 'index'])->name('expenses.index');
        Route::get('/expenses/data', [ExpenseAnalyticsController::class, 'getExpenseData'])->name('expenses.data');
        Route::get('/expenses/trend', [ExpenseAnalyticsController::class, 'getExpenseTrend'])->name('expenses.trend');
        Route::get('/expenses/metrics', [ExpenseAnalyticsController::class, 'getExpenseMetrics'])->name('expenses.metrics');
    });

    // Push Notification Routes
    Route::post('push-subscriptions', [PushSubscriptionController::class, 'store'])->name('push-subscriptions.store');
    Route::delete('push-subscriptions', [PushSubscriptionController::class, 'destroy'])->name('push-subscriptions.destroy');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

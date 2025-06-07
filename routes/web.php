<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\BudgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('credit-cards', CreditCardController::class);
    Route::get('/credit-cards/{creditCard}/invoices', [CreditCardController::class, 'invoices'])
        ->name('credit-cards.invoices');
    Route::get('/credit-cards/{creditCard}/current-invoice', [CreditCardController::class, 'currentInvoice'])
        ->name('credit-cards.current-invoice');
    Route::post('/credit-cards/{creditCard}/close-invoice', [CreditCardController::class, 'closeInvoice'])
        ->name('credit-cards.close-invoice');
    Route::post('/credit-cards/{creditCard}/pay-invoice', [CreditCardController::class, 'payInvoice'])
        ->name('credit-cards.pay-invoice');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::resource('reports', ReportController::class);
    Route::resource('budgets', BudgetController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    Route::get('/financial-goals', [FinancialGoalController::class, 'index'])->name('financial-goals.index');
    Route::get('/financial-goals/create', [FinancialGoalController::class, 'create'])->name('financial-goals.create');
    Route::post('/financial-goals', [FinancialGoalController::class, 'store'])->name('financial-goals.store');
    Route::get('/financial-goals/{financialGoal}', [FinancialGoalController::class, 'show'])->name('financial-goals.show');
    Route::get('/financial-goals/{financialGoal}/edit', [FinancialGoalController::class, 'edit'])->name('financial-goals.edit');
    Route::put('/financial-goals/{financialGoal}', [FinancialGoalController::class, 'update'])->name('financial-goals.update');
    Route::delete('/financial-goals/{financialGoal}', [FinancialGoalController::class, 'destroy'])->name('financial-goals.destroy');

    Route::post('financial-goals/{financialGoal}/simulate', [FinancialGoalController::class, 'simulate'])
        ->name('financial-goals.simulate');
    Route::post('financial-goals/{financialGoal}/update-progress', [FinancialGoalController::class, 'updateProgress'])
        ->name('financial-goals.update-progress');
});

require __DIR__.'/auth.php';

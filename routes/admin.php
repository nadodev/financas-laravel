<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Usuários
Route::resource('users', UserController::class);
Route::post('users/{user}/change-plan', [UserController::class, 'changePlan'])->name('users.change-plan');

// Planos
Route::resource('plans', PlanController::class);
Route::post('plans/{plan}/toggle-status', [PlanController::class, 'toggleStatus'])->name('plans.toggle-status');

// Transações
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

// Estatísticas
Route::get('statistics', [StatisticsController::class, 'index'])->name('statistics'); 
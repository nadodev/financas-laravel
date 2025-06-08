<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpenseAnalyticsService
{
    public function getTopExpenses($userId, $period = 'month', $limit = 10)
    {
        $startDate = $this->getStartDate($period);

        return Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('date', '>=', $startDate)
            ->select('category_id', DB::raw('SUM(amount) as total_amount'))
            ->with('category:id,name,color')
            ->groupBy('category_id')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get()
            ->map(function ($expense) {
                return [
                    'category' => $expense->category->name,
                    'color' => $expense->category->color,
                    'amount' => $expense->total_amount,
                    'percentage' => 0 // Será calculado abaixo
                ];
            });
    }

    public function getExpensesByCategory($userId, $period = 'month')
    {
        $startDate = $this->getStartDate($period);
        
        $totalExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('date', '>=', $startDate)
            ->sum('amount');

        $expensesByCategory = $this->getTopExpenses($userId, $period);

        return $expensesByCategory->map(function ($expense) use ($totalExpenses) {
            $expense['percentage'] = $totalExpenses > 0 
                ? round(($expense['amount'] / $totalExpenses) * 100, 2) 
                : 0;
            return $expense;
        });
    }

    public function getMonthlyExpenseTrend($userId, $months = 6)
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        return Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('date', '>=', $startDate)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($expense) {
                return [
                    'month' => Carbon::createFromFormat('Y-m', $expense->month)->format('M/Y'),
                    'amount' => $expense->total_amount
                ];
            });
    }

    public function getExpenseMetrics($userId, $period = 'month')
    {
        $startDate = $this->getStartDate($period);
        $previousStartDate = $this->getStartDate($period, true);

        $currentExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->where('date', '>=', $startDate)
            ->sum('amount');

        $previousExpenses = Transaction::where('user_id', $userId)
            ->where('type', 'expense')
            ->whereBetween('date', [$previousStartDate, $startDate])
            ->sum('amount');

        $percentageChange = $previousExpenses > 0 
            ? round((($currentExpenses - $previousExpenses) / $previousExpenses) * 100, 2)
            : 100;

        return [
            'current_total' => $currentExpenses,
            'previous_total' => $previousExpenses,
            'percentage_change' => $percentageChange,
            'trend' => $percentageChange > 0 ? 'up' : 'down'
        ];
    }

    protected function getStartDate($period, $previous = false)
    {
        $date = $previous ? now()->subMonth() : now();

        return match($period) {
            'week' => $date->startOfWeek(),
            'month' => $date->startOfMonth(),
            'quarter' => $date->startOfQuarter(),
            'year' => $date->startOfYear(),
            default => $date->startOfMonth()
        };
    }
} 
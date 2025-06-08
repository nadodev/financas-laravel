<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerformanceController extends Controller
{
    public function index()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        $currentMonthMetrics = $this->getMonthlyMetrics($currentMonth);
        
        $lastMonthMetrics = $this->getMonthlyMetrics($lastMonth);

        $comparison = [
            'income' => $this->calculatePercentageChange($lastMonthMetrics['total_income'], $currentMonthMetrics['total_income']),
            'expense' => $this->calculatePercentageChange($lastMonthMetrics['total_expense'], $currentMonthMetrics['total_expense']),
            'savings' => $this->calculatePercentageChange($lastMonthMetrics['savings'], $currentMonthMetrics['savings']),
            'savings_rate' => $this->calculatePercentageChange($lastMonthMetrics['savings_rate'], $currentMonthMetrics['savings_rate']),
        ];

        $topExpenseCategories = Category::select('categories.id', 'categories.name', 'categories.icon', 'categories.color', DB::raw('SUM(transactions.amount) as total'))
            ->join('transactions', 'categories.id', '=', 'transactions.category_id')
            ->where('transactions.type', 'expense')
            ->whereBetween('transactions.date', [$currentMonth, Carbon::now()])
            ->groupBy('categories.id', 'categories.name', 'categories.icon', 'categories.color')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $monthlyEvolution = $this->getMonthlyEvolution();

        return view('performance.index', compact(
            'currentMonthMetrics',
            'lastMonthMetrics',
            'comparison',
            'topExpenseCategories',
            'monthlyEvolution'
        ));
    }

    private function getMonthlyMetrics($month)
    {
        $transactions = Transaction::whereBetween('date', [
            $month,
            $month->copy()->endOfMonth()
        ])->where('user_id', auth()->user()->id)->get();


        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $savings = $totalIncome - $totalExpense;
        $savingsRate = $totalIncome > 0 ? ($savings / $totalIncome) * 100 : 0;

        return [
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'savings' => $savings,
            'savings_rate' => $savingsRate,
            'month' => $month->format('F Y')
        ];
    }

    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        return (($newValue - $oldValue) / abs($oldValue)) * 100;
    }

    private function getMonthlyEvolution()
    {
        $now = \Carbon\Carbon::now();
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i)->startOfMonth();
            $months->push($month);
        }

        $raw = \App\Models\Transaction::select(
            \DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            \DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            \DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
        )
        ->whereBetween('date', [$months->first(), $months->last()->copy()->endOfMonth()])
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->keyBy('month');

        $result = $months->map(function($month) use ($raw) {
            $key = $month->format('Y-m');
            $data = $raw->get($key);
            $income = $data ? (float)$data->income : 0;
            $expense = $data ? (float)$data->expense : 0;
            return [
                'month' => $month->format('M/Y'),
                'income' => $income,
                'expense' => $expense,
                'savings' => $income - $expense
            ];
        });

        return $result;
    }
} 
<?php

namespace App\Http\Controllers;

use App\Services\ExpenseAnalyticsService;
use Illuminate\Http\Request;

class ExpenseAnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(ExpenseAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        return view('analytics.expenses');
    }

    public function getExpenseData(Request $request)
    {
        $period = $request->get('period', 'month');
        $expenses = $this->analyticsService->getExpensesByCategory(auth()->id(), $period);

        return response()->json($expenses);
    }

    public function getExpenseTrend(Request $request)
    {
        $months = $request->get('months', 6);
        $trend = $this->analyticsService->getMonthlyExpenseTrend(auth()->id(), $months);

        return response()->json($trend);
    }

    public function getExpenseMetrics(Request $request)
    {
        $period = $request->get('period', 'month');
        $metrics = $this->analyticsService->getExpenseMetrics(auth()->id(), $period);

        return response()->json($metrics);
    }
} 
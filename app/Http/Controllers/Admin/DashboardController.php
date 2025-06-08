<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalTransactions = Transaction::count();
        $totalPlans = Plan::count();
        
        $recentUsers = User::with('plan')
            ->latest()
            ->take(5)
            ->get();
            
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        $planDistribution = User::select('plan_id', DB::raw('count(*) as total'))
            ->with('plan')
            ->groupBy('plan_id')
            ->get();
            
        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalTransactions',
            'totalPlans',
            'recentUsers',
            'recentTransactions',
            'planDistribution'
        ));
    }
    
    public function statistics()
    {
        $monthlyStats = Transaction::selectRaw('
            DATE_FORMAT(date, "%Y-%m") as month,
            COUNT(*) as total_transactions,
            COUNT(DISTINCT user_id) as active_users,
            SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as total_income,
            SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as total_expenses
        ')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();
        
        $userGrowth = User::where('role', '!=', 'admin')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as new_users')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();
            
        $planStats = Plan::withCount(['users' => function($query) {
            $query->where('role', '!=', 'admin');
        }])
        ->get();
        
        return view('admin.statistics', compact(
            'monthlyStats',
            'userGrowth',
            'planStats'
        ));
    }
} 
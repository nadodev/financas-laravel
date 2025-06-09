<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\FinancialGoal;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;
use App\Exports\CategoriesExport;
use App\Exports\GoalsExport;
use App\Exports\AccountsExport;
use App\Exports\ReportExport;

class ReportController extends Controller
{
    public function index()
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        return view('reports.index', compact('accounts'));
    }

    public function show($type, Request $request)
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        $method = 'report' . str_replace(' ', '', ucwords(str_replace('-', ' ', $type)));
        if (method_exists($this, $method)) {
            $data = $this->$method($request);
            return view('reports.index', array_merge($data, ['accounts' => $accounts]));
        }
        abort(404);
    }

    public function export(Request $request, $type, $format)
    {
        $data = match ($type) {
            'income-expense' => $this->reportIncomeExpense($request),
            'categories' => $this->reportCategories($request),
            'goals' => $this->reportGoals($request),
            'accounts' => $this->reportAccounts($request),
            default => throw new \InvalidArgumentException('Tipo de relatório inválido')
        };

        $data['start_date'] = $request->start_date;
        $data['end_date'] = $request->end_date;
        $data['generated_at'] = now()->format('d/m/Y H:i:s');
        $data['reportType'] = $type;

        $filename = "relatorio-{$type}-" . now()->format('Y-m-d');

        return match ($format) {
            'pdf' => PDF::loadView("reports.pdf.{$type}", $data)->download("{$filename}.pdf"),
            'xlsx' => Excel::download(new ReportExport($data), "{$filename}.xlsx"),
            default => throw new \InvalidArgumentException('Formato de exportação inválido')
        };
    }

    private function reportIncomeExpense(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'account_id' => 'nullable|exists:accounts,id'
        ]);

        $query = Transaction::with(['category', 'account'])
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$request->start_date, $request->end_date]);

        if ($request->account_id) {
            $query->where('account_id', $request->account_id);
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Análise mensal
        $monthlyAnalysis = $transactions->groupBy(function($transaction) {
            return $transaction->date->format('Y-m');
        })->map(function($group) {
            return [
                'income' => $group->where('type', 'income')->sum('amount'),
                'expense' => $group->where('type', 'expense')->sum('amount'),
                'balance' => $group->where('type', 'income')->sum('amount') - $group->where('type', 'expense')->sum('amount')
            ];
        });

        return [
            'reportType' => 'income-expense',
            'transactions' => $transactions,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance,
            'monthlyAnalysis' => $monthlyAnalysis
        ];
    }

    private function reportCategories(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:all,income,expense',
            'account_id' => 'nullable|exists:accounts,id'
        ]);

        $query = Transaction::query()
            ->select(
                'categories.id',
                'categories.name',
                'categories.icon',
                'categories.color',
                'transactions.type',
                DB::raw('SUM(transactions.amount) as total'),
                DB::raw('COUNT(transactions.id) as count')
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('transactions.date', [$request->start_date, $request->end_date])
            ->groupBy('categories.id', 'categories.name', 'categories.icon', 'categories.color', 'transactions.type');

        if ($request->account_id) {
            $query->where('transactions.account_id', $request->account_id);
        }

        if ($request->type !== 'all') {
            $query->where('transactions.type', $request->type);
        }

        $transactions = $query->get();

        $categoryIncome = $transactions->where('type', 'income');
        $categoryExpense = $transactions->where('type', 'expense');

        $totalIncome = $categoryIncome->sum('total');
        $totalExpense = $categoryExpense->sum('total');

        // Análise de tendências
        $trends = $this->calculateCategoryTrends($request);

        // Formatar dados para percentuais
        $categoryIncome = $categoryIncome->map(function($category) use ($totalIncome) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon,
                'color' => $category->color,
                'total' => $category->total,
                'count' => $category->count,
                'percentage' => $totalIncome > 0 ? ($category->total / $totalIncome) * 100 : 0
            ];
        });

        $categoryExpense = $categoryExpense->map(function($category) use ($totalExpense) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'icon' => $category->icon,
                'color' => $category->color,
                'total' => $category->total,
                'count' => $category->count,
                'percentage' => $totalExpense > 0 ? ($category->total / $totalExpense) * 100 : 0
            ];
        });

        return [
            'reportType' => 'categories',
            'categoryIncome' => $categoryIncome,
            'categoryExpense' => $categoryExpense,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'trends' => $trends
        ];
    }

    private function reportGoals(Request $request)
    {
        $query = FinancialGoal::with('progress')->where('user_id', auth()->id());

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $goals = $query->get();
        $totalGoals = $goals->count();
        $totalAmount = $goals->sum('target_amount');
        $currentAmount = $goals->sum('current_amount');

        // Análise de progresso
        $progressAnalysis = [
            'completed' => $goals->where('status', 'completed')->count(),
            'in_progress' => $goals->where('status', 'in_progress')->count(),
            'cancelled' => $goals->where('status', 'cancelled')->count()
        ];

        // Calcular porcentagens de forma segura
        foreach ($goals as $goal) {
            $goal->progress_percentage = $goal->target_amount > 0 
                ? min(100, ($goal->current_amount / $goal->target_amount) * 100) 
                : 0;
        }

        return [
            'reportType' => 'goals',
            'goals' => $goals,
            'totalGoals' => $totalGoals,
            'totalAmount' => $totalAmount,
            'currentAmount' => $currentAmount,
            'progressAnalysis' => $progressAnalysis
        ];
    }

    private function reportAccounts(Request $request)
    {
        $accounts = Account::where('user_id', auth()->id())->get();
        
        $balances = $accounts->map(function($account) {
            return [
                'name' => $account->name,
                'current_balance' => $account->transactions()->sum(DB::raw('CASE WHEN type = "income" THEN amount ELSE -amount END')),
                'total_income' => $account->transactions()->where('type', 'income')->sum('amount'),
                'total_expense' => $account->transactions()->where('type', 'expense')->sum('amount'),
                'transaction_count' => $account->transactions()->count()
            ];
        });

        return [
            'reportType' => 'accounts',
            'balances' => $balances,
            'totalBalance' => $balances->sum('current_balance')
        ];
    }

    private function calculateCategoryTrends($request)
    {
        $previousPeriodStart = Carbon::parse($request->start_date)->subDays(
            Carbon::parse($request->end_date)->diffInDays(Carbon::parse($request->start_date))
        );

        $query = Transaction::query()
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(transactions.amount) as total')
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->groupBy('categories.id', 'categories.name');

        if ($request->account_id) {
            $query->where('transactions.account_id', $request->account_id);
        }

        $currentPeriod = (clone $query)
            ->whereBetween('transactions.date', [$request->start_date, $request->end_date])
            ->get();

        $previousPeriod = (clone $query)
            ->whereBetween('transactions.date', [$previousPeriodStart, $request->start_date])
            ->get();

        return $currentPeriod->map(function($current) use ($previousPeriod) {
            $previous = $previousPeriod->firstWhere('id', $current->id);
            $previousTotal = $previous ? $previous->total : 0;
            
            return [
                'category_id' => $current->id,
                'category_name' => $current->name,
                'current_total' => $current->total,
                'previous_total' => $previousTotal,
                'change_percentage' => $previousTotal > 0 
                    ? (($current->total - $previousTotal) / $previousTotal) * 100 
                    : ($current->total > 0 ? 100 : 0)
            ];
        });
    }
} 
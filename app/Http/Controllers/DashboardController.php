<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        
        // Pegar o mês e ano selecionados ou usar o mês atual como padrão
        $selectedMonth = $request->get('month', $now->month);
        $selectedYear = $request->get('year', $now->year);
        
        // Criar data com o mês/ano selecionado
        $selectedDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1);
        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();

        // Lista de meses para o seletor
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro'
        ];

        $firstTransaction = Transaction::where('user_id', auth()->id())
            ->orderBy('date', 'asc')
            ->first();

        $startYear = $firstTransaction ? $firstTransaction->date->year : $now->year;
        $years = range($startYear, $now->year);

        // Cálculo do saldo total (considerando todas as transações até a data final do mês selecionado)
        $totalBalance = Transaction::where('user_id', auth()->id())
            ->where('date', '<=', $endOfMonth)
            ->selectRaw('SUM(CASE WHEN type = "income" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        // Receitas e despesas do mês selecionado
        $monthlyIncome = Transaction::where('user_id', auth()->id())
            ->where('type', 'income')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyExpenses = Transaction::where('user_id', auth()->id())
            ->where('type', 'expense')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Cálculo da economia do mês
        $monthlySavings = $monthlyIncome - $monthlyExpenses;

        // Despesas por categoria do mês selecionado
        $expensesByCategory = Transaction::where('transactions.type', 'expense')
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('transactions.date', [$startOfMonth, $endOfMonth])
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select('categories.name as category', DB::raw('SUM(transactions.amount) as amount'))
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Dados para o gráfico de fluxo de caixa (últimos 6 meses até o mês selecionado)
        $cashFlow = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date = $selectedDate->copy()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $income = Transaction::where('user_id', auth()->id())
                ->where('type', 'income')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenses = Transaction::where('user_id', auth()->id())
                ->where('type', 'expense')
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->sum('amount');

            $cashFlow->push([
                'date' => $date->format('M/Y'),
                'income' => $income,
                'expenses' => $expenses
            ]);
        }

        // Transações recentes do mês selecionado
        $recentTransactions = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        \Log::info('Despesas por categoria:', ['data' => $expensesByCategory->toArray()]);

        return view('dashboard.index', compact(
            'totalBalance',
            'monthlyIncome',
            'monthlyExpenses',
            'monthlySavings',
            'expensesByCategory',
            'cashFlow',
            'recentTransactions',
            'months',
            'years',
            'selectedMonth',
            'selectedYear'
        ));
    }
} 
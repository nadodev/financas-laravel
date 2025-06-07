<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:all,income,expense',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $query = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate]);

        if ($validated['type'] !== 'all') {
            $query->where('type', $validated['type']);
        }

        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }

        $transactions = $query->orderBy('date')->get();

        // Cálculos de totais
        $totals = [
            'income' => $transactions->where('type', 'income')->sum('amount'),
            'expense' => $transactions->where('type', 'expense')->sum('amount'),
            'balance' => $transactions->where('type', 'income')->sum('amount') - 
                        $transactions->where('type', 'expense')->sum('amount')
        ];

        // Agrupamento por categoria
        $categoryTotals = $transactions
            ->groupBy('category.name')
            ->map(function ($group) {
                return [
                    'total' => $group->sum('amount'),
                    'count' => $group->count(),
                    'type' => $group->first()->type
                ];
            });

        // Agrupamento por mês
        $monthlyTotals = $transactions
            ->groupBy(function ($transaction) {
                return $transaction->date->format('Y-m');
            })
            ->map(function ($group) {
                return [
                    'income' => $group->where('type', 'income')->sum('amount'),
                    'expense' => $group->where('type', 'expense')->sum('amount'),
                    'balance' => $group->where('type', 'income')->sum('amount') - 
                                $group->where('type', 'expense')->sum('amount')
                ];
            });

        // Dados para gráficos
        $chartData = [
            'labels' => $monthlyTotals->keys()->map(function ($month) {
                return Carbon::createFromFormat('Y-m', $month)->format('M/Y');
            })->toArray(),
            'income' => $monthlyTotals->pluck('income')->toArray(),
            'expense' => $monthlyTotals->pluck('expense')->toArray(),
            'balance' => $monthlyTotals->pluck('balance')->toArray(),
        ];

        // Categorias disponíveis para o filtro
        $categories = Category::where('user_id', auth()->id())
            ->orderBy('name')
            ->get();

        return view('reports.show', compact(
            'transactions',
            'totals',
            'categoryTotals',
            'monthlyTotals',
            'chartData',
            'categories',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:all,income,expense',
            'category_id' => 'nullable|exists:categories,id',
            'format' => 'required|in:csv,pdf'
        ]);

        $startDate = Carbon::parse($validated['start_date'])->startOfDay();
        $endDate = Carbon::parse($validated['end_date'])->endOfDay();

        $query = Transaction::with('category')
            ->where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate]);

        if ($validated['type'] !== 'all') {
            $query->where('type', $validated['type']);
        }

        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }

        $transactions = $query->orderBy('date')->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCsv($transactions);
        } else {
            return $this->exportToPdf($transactions);
        }
    }

    private function exportToCsv($transactions)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="relatorio_financeiro.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Data', 'Descrição', 'Categoria', 'Tipo', 'Valor']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->date->format('d/m/Y'),
                    $transaction->description,
                    $transaction->category->name,
                    $transaction->type === 'income' ? 'Receita' : 'Despesa',
                    number_format($transaction->amount, 2, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPdf($transactions)
    {
        // Implementar exportação para PDF usando uma biblioteca como DomPDF
        // Esta é uma implementação básica que você pode expandir conforme necessário
        $pdf = app()->make('dompdf.wrapper');
        $html = view('reports.pdf', compact('transactions'))->render();
        $pdf->loadHTML($html);
        
        return $pdf->download('relatorio_financeiro.pdf');
    }
} 
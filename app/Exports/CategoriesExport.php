<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Transaction::query()
            ->select(
                'categories.id',
                'categories.name',
                'categories.icon',
                'categories.color',
                DB::raw('SUM(transactions.amount) as total'),
                DB::raw('COUNT(transactions.id) as transaction_count')
            )
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->whereBetween('transactions.date', [
                $this->filters['start_date'],
                $this->filters['end_date']
            ])
            ->groupBy('categories.id', 'categories.name', 'categories.icon', 'categories.color');

        if (!empty($this->filters['account_id'])) {
            $query->where('transactions.account_id', $this->filters['account_id']);
        }

        if (!empty($this->filters['type']) && $this->filters['type'] !== 'all') {
            $query->where('transactions.type', $this->filters['type']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Categoria',
            'Total',
            'Quantidade de Transações',
            'Média por Transação',
            'Porcentagem do Total'
        ];
    }

    public function map($category): array
    {
        $totalSum = $this->collection()->sum('total');
        $percentage = $totalSum > 0 ? ($category->total / $totalSum) * 100 : 0;
        $average = $category->transaction_count > 0 ? $category->total / $category->transaction_count : 0;

        return [
            $category->name,
            number_format($category->total, 2, ',', '.'),
            $category->transaction_count,
            number_format($average, 2, ',', '.'),
            number_format($percentage, 2, ',', '.') . '%'
        ];
    }
} 
<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Transaction::with('category')
            ->whereBetween('date', [
                $this->filters['start_date'],
                $this->filters['end_date']
            ]);

        if (!empty($this->filters['account_id'])) {
            $query->where('account_id', $this->filters['account_id']);
        }

        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Data',
            'Descrição',
            'Categoria',
            'Tipo',
            'Valor',
            'Conta',
            'Criado em'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->date->format('d/m/Y'),
            $transaction->description,
            $transaction->category->name,
            $transaction->type === 'income' ? 'Receita' : 'Despesa',
            number_format($transaction->amount, 2, ',', '.'),
            $transaction->account->name,
            $transaction->created_at->format('d/m/Y H:i:s')
        ];
    }
} 
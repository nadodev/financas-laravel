<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class IncomeExpenseExport implements FromCollection, WithHeadings, WithTitle, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data['transactions']);
    }

    public function headings(): array
    {
        return [
            'Data',
            'Descrição',
            'Categoria',
            'Valor',
            'Tipo'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->date->format('d/m/Y'),
            $transaction->description,
            $transaction->category->name,
            number_format($transaction->amount, 2, ',', '.'),
            $transaction->type === 'income' ? 'Receita' : 'Despesa'
        ];
    }

    public function title(): string
    {
        return 'Relatório';
    }
} 
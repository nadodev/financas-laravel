<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $reportType;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->reportType = $data['reportType'];
    }

    public function collection()
    {
        return match ($this->reportType) {
            'categories' => $this->categoriesCollection(),
            'income-expense' => $this->incomeExpenseCollection(),
            'goals' => $this->goalsCollection(),
            'accounts' => $this->accountsCollection(),
            default => new Collection([])
        };
    }

    public function headings(): array
    {
        return match ($this->reportType) {
            'categories' => [
                'Categoria',
                'Total',
                'Porcentagem',
                'Quantidade de Transações'
            ],
            'income-expense' => [
                'Data',
                'Descrição',
                'Categoria',
                'Tipo',
                'Valor'
            ],
            'goals' => [
                'Meta',
                'Valor Alvo',
                'Valor Atual',
                'Progresso',
                'Data Limite'
            ],
            'accounts' => [
                'Conta',
                'Tipo',
                'Saldo',
                'Última Atualização'
            ],
            default => []
        };
    }

    public function map($row): array
    {
        return match ($this->reportType) {
            'categories' => [
                $row['name'],
                $row['total'],
                $row['percentage'] . '%',
                $row['count']
            ],
            'income-expense' => [
                $row['date'],
                $row['description'],
                $row['category'],
                $row['type'],
                $row['amount']
            ],
            'goals' => [
                $row['name'],
                $row['target_amount'],
                $row['current_amount'],
                $row['progress'] . '%',
                $row['target_date']
            ],
            'accounts' => [
                $row['name'],
                $row['type'],
                $row['balance'],
                $row['updated_at']
            ],
            default => []
        };
    }

    public function title(): string
    {
        return match ($this->reportType) {
            'categories' => 'Relatório por Categorias',
            'income-expense' => 'Relatório de Receitas e Despesas',
            'goals' => 'Relatório de Metas',
            'accounts' => 'Relatório de Contas',
            default => 'Relatório'
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    protected function categoriesCollection()
    {
        $collection = new Collection();

        if (!empty($this->data['categoryIncome'])) {
            $collection = $collection->concat($this->data['categoryIncome']);
        }

        if (!empty($this->data['categoryExpense'])) {
            $collection = $collection->concat($this->data['categoryExpense']);
        }

        return $collection;
    }

    protected function incomeExpenseCollection()
    {
        return new Collection($this->data['transactions'] ?? []);
    }

    protected function goalsCollection()
    {
        return new Collection($this->data['goals'] ?? []);
    }

    protected function accountsCollection()
    {
        return new Collection($this->data['accounts'] ?? []);
    }
} 
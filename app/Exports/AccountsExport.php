<?php

namespace App\Exports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class AccountsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Account::where('user_id', auth()->id())
            ->withCount('transactions')
            ->withSum(['transactions as total_income' => function($query) {
                $query->where('type', 'income');
            }], 'amount')
            ->withSum(['transactions as total_expense' => function($query) {
                $query->where('type', 'expense');
            }], 'amount')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Conta',
            'Saldo Atual',
            'Total de Receitas',
            'Total de Despesas',
            'Quantidade de Transações',
            'Média por Transação',
            'Última Atualização'
        ];
    }

    public function map($account): array
    {
        $balance = $account->total_income - $account->total_expense;
        $average = $account->transactions_count > 0 
            ? ($account->total_income + $account->total_expense) / $account->transactions_count 
            : 0;

        return [
            $account->name,
            number_format($balance, 2, ',', '.'),
            number_format($account->total_income, 2, ',', '.'),
            number_format($account->total_expense, 2, ',', '.'),
            $account->transactions_count,
            number_format($average, 2, ',', '.'),
            $account->updated_at->format('d/m/Y H:i:s')
        ];
    }
} 
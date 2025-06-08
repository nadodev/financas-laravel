<?php

namespace App\Exports;

use App\Models\FinancialGoal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GoalsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = FinancialGoal::with('progress')->where('user_id', auth()->id());

        if (!empty($this->filters['status']) && $this->filters['status'] !== 'all') {
            $query->where('status', $this->filters['status']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Meta',
            'Valor Atual',
            'Progresso',
            'Status',
            'Data de Início',
            'Data Final',
            'Última Atualização'
        ];
    }

    public function map($goal): array
    {
        $progressPercentage = $goal->target_amount > 0 
            ? ($goal->current_amount / $goal->target_amount) * 100 
            : 0;

        $statuses = [
            'in_progress' => 'Em Andamento',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado'
        ];

        return [
            $goal->name,
            number_format($goal->target_amount, 2, ',', '.'),
            number_format($goal->current_amount, 2, ',', '.'),
            number_format($progressPercentage, 2, ',', '.') . '%',
            $statuses[$goal->status] ?? $goal->status,
            $goal->start_date ? $goal->start_date->format('d/m/Y') : '-',
            $goal->end_date ? $goal->end_date->format('d/m/Y') : '-',
            $goal->updated_at->format('d/m/Y H:i:s')
        ];
    }
} 
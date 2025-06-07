<?php

namespace App\Services;

use App\Models\FinancialGoal;
use App\Repositories\FinancialGoalRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class FinancialGoalService
{
    protected $repository;

    public function __construct(FinancialGoalRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllByUser(int $userId): Collection
    {
        return $this->repository->getAllByUser($userId);
    }

    public function create(array $data): FinancialGoal
    {
        $data['start_date'] = Carbon::now();
        $data['current_amount'] = $data['current_amount'] ?? 0;
        $data['status'] = 'in_progress';

        return $this->repository->create($data);
    }

    public function update(FinancialGoal $goal, array $data): bool
    {
        return $this->repository->update($goal, $data);
    }

    public function delete(FinancialGoal $goal): bool
    {
        return $this->repository->delete($goal);
    }

    public function findByIdAndUser(int $id, int $userId): ?FinancialGoal
    {
        return $this->repository->findByIdAndUser($id, $userId);
    }

    public function updateProgress(FinancialGoal $goal, float $amount, string $date, ?string $notes = null)
    {
        // Atualiza o valor atual
        $goal->current_amount += $amount;
        
        // Se atingiu ou ultrapassou a meta, marca como concluído
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->status = 'completed';
            $goal->current_amount = $goal->target_amount; // Garante que não ultrapasse a meta
        }
        
        // Salva as alterações
        $goal->save();

        // Registra o progresso
        $goal->progress()->create([
            'amount' => $amount,
            'date' => $date,
            'notes' => $notes
        ]);

        return $goal;
    }

    public function simulateByMonthlyAmount(FinancialGoal $goal, float $monthlyAmount)
    {
        if ($monthlyAmount <= 0) {
            return [
                'months' => null,
                'estimated_date' => null
            ];
        }

        $remaining = $goal->remaining_amount;
        $months = ceil($remaining / $monthlyAmount);
        
        return [
            'months' => $months,
            'estimated_date' => Carbon::now()->addMonths($months)->format('d/m/Y')
        ];
    }

    public function simulateByMonths(FinancialGoal $goal, int $months)
    {
        if ($months <= 0) {
            return [
                'monthly_amount' => 0,
                'estimated_date' => null
            ];
        }

        $monthlyAmount = $goal->remaining_amount / $months;
        
        return [
            'monthly_amount' => $monthlyAmount,
            'estimated_date' => Carbon::now()->addMonths($months)->format('d/m/Y')
        ];
    }
} 
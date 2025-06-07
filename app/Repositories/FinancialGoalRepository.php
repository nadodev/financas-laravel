<?php

namespace App\Repositories;

use App\Models\FinancialGoal;
use Illuminate\Database\Eloquent\Collection;

class FinancialGoalRepository implements FinancialGoalRepositoryInterface
{
    public function getAllByUser(int $userId): Collection
    {
        return FinancialGoal::where('user_id', $userId)
            ->orderBy('target_date', 'asc')
            ->get();
    }

    public function create(array $data): FinancialGoal
    {
        return FinancialGoal::create($data);
    }

    public function update(FinancialGoal $goal, array $data): bool
    {
        return $goal->update($data);
    }

    public function delete(FinancialGoal $goal): bool
    {
        return $goal->delete();
    }

    public function findByIdAndUser(int $id, int $userId): ?FinancialGoal
    {
        return FinancialGoal::where('id', $id)
            ->where('user_id', $userId)
            ->with('account')
            ->first();
    }

    public function updateProgress(FinancialGoal $goal, float $amount): bool
    {
        $goal->current_amount = min($goal->target_amount, $goal->current_amount + $amount);
        
        if ($goal->current_amount >= $goal->target_amount) {
            $goal->status = 'completed';
        }
        
        return $goal->save();
    }
} 
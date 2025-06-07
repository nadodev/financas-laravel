<?php

namespace App\Repositories;

use App\Models\FinancialGoal;
use Illuminate\Database\Eloquent\Collection;

interface FinancialGoalRepositoryInterface
{
    public function getAllByUser(int $userId): Collection;
    public function create(array $data): FinancialGoal;
    public function update(FinancialGoal $goal, array $data): bool;
    public function delete(FinancialGoal $goal): bool;
    public function findByIdAndUser(int $id, int $userId): ?FinancialGoal;
    public function updateProgress(FinancialGoal $goal, float $amount): bool;
} 
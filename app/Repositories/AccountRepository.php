<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountRepository implements AccountRepositoryInterface
{
    protected $model;

    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    public function getAllForUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->where('user_id', $userId);

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['bank'])) {
            $query->where('bank_name', 'like', '%' . $filters['bank'] . '%');
        }

        if (isset($filters['balance'])) {
            if ($filters['balance'] === 'positive') {
                $query->where('balance', '>=', 0);
            } else if ($filters['balance'] === 'negative') {
                $query->where('balance', '<', 0);
            }
        }

        return $query->orderBy('name')->paginate(10);
    }

    public function create(array $data): Account
    {
        return $this->model->create($data);
    }

    public function update(Account $account, array $data): bool
    {
        return $account->update($data);
    }

    public function delete(Account $account): bool
    {
        return $account->delete();
    }

    public function find(int $id): ?Account
    {
        return $this->model->find($id);
    }

    public function getTotalBalance(int $userId): float
    {
        return $this->model->where('user_id', $userId)->sum('balance');
    }

    public function getTotalAccounts(int $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function getNegativeAccounts(int $userId): int
    {
        return $this->model->where('user_id', $userId)
            ->where('balance', '<', 0)
            ->count();
    }

    public function hasTransactions(Account $account): bool
    {
        return $account->transactions()->exists();
    }
} 
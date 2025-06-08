<?php

namespace App\Repositories\Interfaces;

use App\Models\Account;
use Illuminate\Pagination\LengthAwarePaginator;

interface AccountRepositoryInterface
{
    public function getAllForUser(int $userId, array $filters = []): LengthAwarePaginator;
    
    public function create(array $data): Account;
    
    public function update(Account $account, array $data): bool;
    
    public function delete(Account $account): bool;
    
    public function find(int $id): ?Account;
    
    public function getTotalBalance(int $userId): float;
    
    public function getTotalAccounts(int $userId): int;
    
    public function getNegativeAccounts(int $userId): int;
    
    public function hasTransactions(Account $account): bool;
} 
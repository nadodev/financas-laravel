<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface TransactionRepositoryInterface
{
    public function create(array $data): Transaction;
    public function update(Transaction $transaction, array $data): bool;
    public function delete(Transaction $transaction): bool;
    public function find(int $id): ?Transaction;
    public function findByUser(int $userId, array $filters = []): LengthAwarePaginator;
    public function findByAccount(int $accountId): Collection;
    public function findByCreditCard(int $creditCardId): Collection;
    public function findByInvoice(int $invoiceId): Collection;
} 
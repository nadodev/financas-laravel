<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function create(array $data): Transaction
    {
        return Transaction::create($data);
    }

    public function update(Transaction $transaction, array $data): bool
    {
        return $transaction->update($data);
    }

    public function delete(Transaction $transaction): bool
    {
        return $transaction->delete();
    }

    public function find(int $id): ?Transaction
    {
        return Transaction::find($id);
    }

    public function findByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Transaction::with(['category', 'account', 'creditCard'])
            ->where('user_id', $userId);

        if (!empty($filters['start_date'])) {
            $query->where('date', '>=', Carbon::parse($filters['start_date']));
        }

        if (!empty($filters['end_date'])) {
            $query->where('date', '<=', Carbon::parse($filters['end_date']));
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['account_id'])) {
            $query->where('account_id', $filters['account_id']);
        }

        if (!empty($filters['credit_card_id'])) {
            $query->where('credit_card_id', $filters['credit_card_id']);
        }

        return $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function findByAccount(int $accountId): Collection
    {
        return Transaction::where('account_id', $accountId)->get();
    }

    public function findByCreditCard(int $creditCardId): Collection
    {
        return Transaction::where('credit_card_id', $creditCardId)->get();
    }

    public function findByInvoice(int $invoiceId): Collection
    {
        return Transaction::where('credit_card_invoice_id', $invoiceId)->get();
    }
} 
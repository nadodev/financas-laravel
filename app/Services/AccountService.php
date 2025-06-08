<?php

namespace App\Services;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountService
{
    protected $repository;

    public function __construct(AccountRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAccountsWithStats(int $userId, array $filters = []): array
    {
        $accounts = $this->repository->getAllForUser($userId, $filters);
        $totalBalance = $this->repository->getTotalBalance($userId);
        $totalAccounts = $this->repository->getTotalAccounts($userId);
        $negativeAccounts = $this->repository->getNegativeAccounts($userId);

        return [
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
            'totalAccounts' => $totalAccounts,
            'negativeAccounts' => $negativeAccounts
        ];
    }

    public function createAccount(array $data, int $userId): Account
    {
        // Adiciona o user_id aos dados
        $data['user_id'] = $userId;

        // Cria a conta
        return $this->repository->create($data);
    }

    public function updateAccount(Account $account, array $data): bool
    {
        return $this->repository->update($account, $data);
    }

    public function deleteAccount(Account $account): bool
    {
        // Verifica se a conta tem transações antes de deletar
        if ($this->repository->hasTransactions($account)) {
            throw new \Exception('Não é possível excluir uma conta que possui transações vinculadas.');
        }

        return $this->repository->delete($account);
    }

    public function findAccount(int $id): ?Account
    {
        return $this->repository->find($id);
    }

    public function getAccountTypes(): array
    {
        return Account::$types;
    }
} 
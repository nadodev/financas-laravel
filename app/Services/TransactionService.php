<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Account;
use App\Models\CreditCard;
use App\Models\CreditCardInvoice;
use App\Repositories\TransactionRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepositoryInterface $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function getTransactions(int $userId, array $filters = []): LengthAwarePaginator
    {
        return $this->transactionRepository->findByUser($userId, $filters);
    }

    public function createTransaction(array $data): Transaction
    {
        try {
            DB::beginTransaction();

            // Prepara os dados da transação
            $transactionData = $this->prepareTransactionData($data);

            // Cria a transação
            if ($data['is_recurring'] ?? false) {
                $transactions = $this->createRecurringTransactions($transactionData);
                $transaction = $transactions[0]; // Retorna a primeira transação
            } else {
                $transaction = $this->transactionRepository->create($transactionData);
                $this->updateAccountBalance($transaction);
                $this->updateInvoiceAmount($transaction);
            }

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTransaction(Transaction $transaction, array $data): bool
    {
        try {
            DB::beginTransaction();

            // Guarda os valores originais
            $originalData = [
                'amount' => $transaction->amount,
                'type' => $transaction->type,
                'account_id' => $transaction->account_id,
                'credit_card_invoice_id' => $transaction->credit_card_invoice_id
            ];

            // Prepara os dados da transação
            $transactionData = $this->prepareTransactionData($data);

            // Atualiza a transação
            $updated = $this->transactionRepository->update($transaction, $transactionData);

            if ($updated) {
                // Reverte as alterações antigas
                $this->revertAccountBalance($originalData);
                $this->revertInvoiceAmount($originalData);

                // Aplica as novas alterações
                $transaction->refresh();
                $this->updateAccountBalance($transaction);
                $this->updateInvoiceAmount($transaction);
            }

            DB::commit();
            return $updated;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteTransaction(Transaction $transaction): bool
    {
        try {
            DB::beginTransaction();

            // Reverte o saldo da conta
            if ($transaction->account_id) {
                $account = $transaction->account;
                if ($transaction->type === 'income') {
                    $account->balance -= $transaction->amount;
                } else {
                    $account->balance += $transaction->amount;
                }
                $account->save();
            }

            // Reverte o valor da fatura
            if ($transaction->credit_card_invoice_id) {
                $invoice = $transaction->creditCardInvoice;
                $invoice->amount -= $transaction->amount;
                $invoice->save();
            }

            $deleted = $this->transactionRepository->delete($transaction);

            DB::commit();
            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function prepareTransactionData(array $data): array
    {
        $transactionData = $data;

        // Limpa os campos baseado no método de pagamento
        if ($data['payment_method'] === 'account') {
            $transactionData['credit_card_id'] = null;
            $transactionData['credit_card_invoice_id'] = null;
        } else {
            $transactionData['account_id'] = null;
            // Vincula à fatura atual do cartão
            $creditCard = CreditCard::findOrFail($data['credit_card_id']);
            $transactionData['credit_card_invoice_id'] = $creditCard->getCurrentInvoice()->id;
        }

        // Remove campos desnecessários
        unset($transactionData['payment_method']);

        return $transactionData;
    }

    protected function createRecurringTransactions(array $data): array
    {
        $transactions = [];
        $amount = $data['amount'] / $data['installments'];
        $date = Carbon::parse($data['date']);

        for ($i = 1; $i <= $data['installments']; $i++) {
            $transactionData = $data;
            $transactionData['amount'] = $amount;
            $transactionData['date'] = $date->copy();
            $transactionData['current_installment'] = $i;
            $transactionData['total_installments'] = $data['installments'];

            if ($transactionData['credit_card_id']) {
                $creditCard = CreditCard::findOrFail($transactionData['credit_card_id']);
                $invoice = $creditCard->getCurrentInvoice();
                $transactionData['credit_card_invoice_id'] = $invoice->id;
            }

            $transaction = $this->transactionRepository->create($transactionData);
            $this->updateAccountBalance($transaction);
            $this->updateInvoiceAmount($transaction);

            $transactions[] = $transaction;
            $date->addMonth();
        }

        return $transactions;
    }

    protected function updateAccountBalance(Transaction $transaction): void
    {
        if ($transaction->account_id) {
            $account = $transaction->account;
            if ($transaction->type === 'income') {
                $account->balance += $transaction->amount;
            } else {
                $account->balance -= $transaction->amount;
            }
            $account->save();
        }
    }

    protected function updateInvoiceAmount(Transaction $transaction): void
    {
        if ($transaction->credit_card_invoice_id) {
            $invoice = $transaction->creditCardInvoice;
            $invoice->amount += $transaction->amount;
            $invoice->save();
        }
    }

    protected function revertAccountBalance(array $originalData): void
    {
        if ($originalData['account_id']) {
            $account = Account::find($originalData['account_id']);
            if ($originalData['type'] === 'income') {
                $account->balance -= $originalData['amount'];
            } else {
                $account->balance += $originalData['amount'];
            }
            $account->save();
        }
    }

    protected function revertInvoiceAmount(array $originalData): void
    {
        if ($originalData['credit_card_invoice_id']) {
            $invoice = CreditCardInvoice::find($originalData['credit_card_invoice_id']);
            $invoice->amount -= $originalData['amount'];
            $invoice->save();
        }
    }
} 
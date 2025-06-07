@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Detalhes da Conta</h1>
        <div class="flex space-x-3">
            <a href="{{ route('accounts.edit', $account) }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Editar Conta
            </a>
            <a href="{{ route('accounts.index') }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $account->name }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ $account->type }}</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Saldo Atual</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="{{ $account->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($account->balance, 2, ',', '.') }}
                        </span>
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Banco</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $account->bank ?: 'Não informado' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Agência</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $account->agency ?: 'Não informado' }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Número da Conta</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $account->account_number ?: 'Não informado' }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Observações</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $account->notes ?: 'Nenhuma observação' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Últimas Transações -->
    <div class="mt-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-900">Últimas Transações</h2>
            <a href="{{ route('transactions.create', ['account_id' => $account->id]) }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Nova Transação
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul role="list" class="divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <li class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $transaction->description }}
                                        @if($transaction->is_recurring)
                                            <span class="ml-2 text-xs text-gray-500">
                                                (Parcela {{ $transaction->current_installment }}/{{ $transaction->total_installments }})
                                            </span>
                                        @endif
                                    </p>
                                    <div class="ml-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <span class="truncate">{{ $transaction->category->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $transaction->date->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                    class="font-medium text-indigo-600 hover:text-indigo-500">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-6 py-4">
                        <p class="text-gray-500 text-center">Nenhuma transação encontrada.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        @if($transactions->hasPages())
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 
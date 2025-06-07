@extends('layouts.dashboard')

@section('header')
    Transações
@endsection

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Transações</h1>
            <a href="{{ route('transactions.create') }}" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Nova Transação
            </a>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Data Inicial</label>
                    <input type="date" name="start_date" id="start_date" 
                        value="{{ request('start_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Data Final</label>
                    <input type="date" name="end_date" id="end_date" 
                        value="{{ request('end_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                    <select name="type" id="type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Receitas</option>
                        <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Despesas</option>
                    </select>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                    <select name="category_id" id="category_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todas</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                    <select name="account_id" id="account_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todas</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="credit_card_id" class="block text-sm font-medium text-gray-700">Cartão de Crédito</label>
                    <select name="credit_card_id" id="credit_card_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos</option>
                        @foreach($creditCards as $card)
                            <option value="{{ $card->id }}" {{ request('credit_card_id') == $card->id ? 'selected' : '' }}>
                                {{ $card->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-3 flex justify-end">
                    <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Transações -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
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
                                    <span class="mx-2">•</span>
                                    @if($transaction->account)
                                        <span>Conta: {{ $transaction->account->name }}</span>
                                    @else
                                        <span>Cartão: {{ $transaction->creditCard ? $transaction->creditCard->name : 'N/A' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <a href="{{ route('transactions.edit', $transaction) }}" 
                                    class="mr-2 font-medium text-indigo-600 hover:text-indigo-500">
                                    Editar
                                </a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" 
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta transação?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-medium text-red-600 hover:text-red-500">
                                        Excluir
                                    </button>
                                </form>
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

        <!-- Paginação -->
        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
@endsection 
@extends('layouts.dashboard')

@section('content')
<div class="" x-data="{ 
    showModal: false,
    showEditModal: false,
    editingTransaction: {
        id: null,
        description: '',
        amount: '',
        date: '',
        type: '',
        category_id: '',
        account_id: '',
        notes: ''
    },
    editUrl: '',
    openEditModal(transaction) {
        this.editingTransaction = {
            id: transaction.id,
            description: transaction.description,
            amount: transaction.amount,
            date: transaction.date,
            type: transaction.type,
            category_id: transaction.category_id,
            account_id: transaction.account_id,
            notes: transaction.notes || ''
        };
        this.editUrl = `/transactions/${transaction.id}`;
        this.showEditModal = true;
    }
}">
    <div class="sm:px-6 lg:px-8">
        <!-- Month Navigation -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="p-4 flex items-center justify-between">
                <a href="{{ route('transactions.index', ['month' => $previousMonth, 'year' => $previousYear]) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Mês Anterior
                </a>
                
                <div class="flex items-center space-x-4">
                    <select name="month" 
                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            onchange="window.location.href = '{{ route('transactions.index') }}?month=' + this.value + '&year={{ $currentYear }}'">
                        @foreach($months as $key => $monthName)
                            <option value="{{ $key }}" {{ $currentMonth == $key ? 'selected' : '' }}>
                                {{ $monthName }}
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="year" 
                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            onchange="window.location.href = '{{ route('transactions.index') }}?month={{ $currentMonth }}&year=' + this.value">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $currentYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <a href="{{ route('transactions.index', ['month' => $nextMonth, 'year' => $nextYear]) }}" 
                   class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Próximo Mês
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        @include('transactions._modal')
        @include('transactions._edit_modal')

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(!auth()->user()->checkTransactionLimit())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Você atingiu o limite de transações para seu plano atual. 
                                    <a href="{{ route('plans.index') }}" class="font-medium underline hover:text-yellow-800">
                                        Conheça nossos planos disponíveis
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Minhas Transações</h2>
                    <button 
                        @click="showModal = true"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ !auth()->user()->checkTransactionLimit() ? 'disabled' : '' }}
                    >
                        Nova Transação
                    </button>
                </div>

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Left side: Transactions List -->
                    <div class="lg:w-2/3">
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($transactions as $transaction)
                                <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $transaction->description }}</h3>
                                            <p class="text-sm text-gray-600">{{ $transaction->date->format('d/m/Y') }}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button type="button"
                                                @click="openEditModal({
                                                    id: {{ $transaction->id }},
                                                    description: '{{ str_replace("'", "\\'", $transaction->description) }}',
                                                    amount: {{ $transaction->amount }},
                                                    date: '{{ $transaction->date->format('Y-m-d') }}',
                                                    type: '{{ $transaction->type }}',
                                                    category_id: {{ $transaction->category_id ?? 'null' }},
                                                    account_id: {{ $transaction->account_id ?? 'null' }},
                                                    notes: '{{ str_replace("'", "\\'", $transaction->notes ?? '') }}'
                                                })"
                                                class="text-gray-400 hover:text-indigo-600" 
                                                title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-gray-400 hover:text-red-600"
                                                        title="Excluir"
                                                        onclick="return confirm('Tem certeza que deseja excluir esta transação?')">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 grid grid-cols-2 gap-4">
                                        <div>
                                            <span class="text-sm text-gray-500">Categoria:</span>
                                            <p class="font-medium">{{ $transaction->category?->name ?? 'Sem categoria' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Conta:</span>
                                            <p class="font-medium">{{ $transaction->account?->name ?? 'Sem conta' }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                                        </span>
                                        <span class="text-lg font-semibold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </span>
                                    </div>

                                    @if($transaction->notes)
                                        <div class="mt-4 text-sm text-gray-600">
                                            <p class="italic">{{ $transaction->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $transactions->links() }}
                        </div>
                    </div>

                    @include('transactions._summary_cards')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
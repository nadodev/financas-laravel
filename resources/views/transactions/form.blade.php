@extends('layouts.dashboard')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-2xl font-semibold text-gray-900 mb-6">
                {{ isset($transaction) ? 'Editar Transação' : 'Nova Transação' }}
            </h1>

            <form action="{{ isset($transaction) ? route('transactions.update', $transaction) : route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($transaction))
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <input type="text" name="description" id="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('description', $transaction->description ?? '') }}" required>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('amount', $transaction->amount ?? '') }}" required>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Data</label>
                        <input type="date" name="date" id="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('date', isset($transaction) ? $transaction->date->format('Y-m-d') : '') }}" required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="income" {{ old('type', $transaction->type ?? '') == 'income' ? 'selected' : '' }}>Receita</option>
                            <option value="expense" {{ old('type', $transaction->type ?? '') == 'expense' ? 'selected' : '' }}>Despesa</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                        <select name="account_id" id="account_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id', $transaction->account_id ?? '') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('account_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="pending" {{ old('status', $transaction->status ?? '') == 'pending' ? 'selected' : '' }}>Pendente</option>
                            <option value="paid" {{ old('status', $transaction->status ?? '') == 'paid' ? 'selected' : '' }}>Pago</option>
                            <option value="cancelled" {{ old('status', $transaction->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-span-2">
                        <label for="attachment" class="block text-sm font-medium text-gray-700">Anexo</label>
                        <div class="mt-1 flex items-center">
                            <input type="file" name="attachment" id="attachment" 
                                class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100">
                            @if(isset($transaction) && $transaction->attachment)
                                <div class="ml-4 flex items-center">
                                    <a href="{{ Storage::url($transaction->attachment) }}" target="_blank" 
                                        class="text-indigo-600 hover:text-indigo-900 flex items-center">
                                        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        Ver anexo atual
                                    </a>
                                    <button type="button" onclick="document.getElementById('remove_attachment').value = '1'"
                                        class="ml-2 text-red-600 hover:text-red-900">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                <input type="hidden" name="remove_attachment" id="remove_attachment" value="0">
                            @endif
                        </div>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="recurring" id="recurring" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                                    {{ old('recurring', $transaction->recurring ?? false) ? 'checked' : '' }}
                                    {{ isset($transaction) && !$transaction->is_recurring_parent ? 'disabled' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="recurring" class="font-medium text-gray-700">Transação Recorrente</label>
                                <p class="text-gray-500">Marque esta opção se esta transação se repete regularmente</p>
                            </div>
                        </div>
                    </div>

                    <div id="recurrence_fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                        <div>
                            <label for="recurrence_interval" class="block text-sm font-medium text-gray-700">Intervalo de Recorrência (em dias)</label>
                            <input type="number" name="recurrence_interval" id="recurrence_interval" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                value="{{ old('recurrence_interval', $transaction->recurrence_interval ?? 30) }}" 
                                min="1"
                                {{ isset($transaction) && !$transaction->is_recurring_parent ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700">Data Final da Recorrência</label>
                            <input type="date" name="recurrence_end_date" id="recurrence_end_date" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                value="{{ old('recurrence_end_date', isset($transaction) && $transaction->recurrence_end_date ? $transaction->recurrence_end_date->format('Y-m-d') : '') }}"
                                {{ isset($transaction) && !$transaction->is_recurring_parent ? 'disabled' : '' }}>
                        </div>
                    </div>

                    @if(isset($transaction) && ($transaction->is_recurring_parent || $transaction->is_recurring_child))
                    <div class="md:col-span-2">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" name="update_all_recurrences" id="update_all_recurrences" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="update_all_recurrences" class="font-medium text-gray-700">Atualizar todas as recorrências</label>
                                <p class="text-gray-500">Marque esta opção para atualizar todas as transações recorrentes relacionadas</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('transactions.index') }}" class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                    <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ isset($transaction) ? 'Atualizar' : 'Criar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const recurringCheckbox = document.getElementById('recurring');
        const recurrenceFields = document.getElementById('recurrence_fields');

        function toggleRecurrenceFields() {
            recurrenceFields.style.display = recurringCheckbox.checked ? 'grid' : 'none';
        }

        recurringCheckbox.addEventListener('change', toggleRecurrenceFields);
        toggleRecurrenceFields();
    });
    </script>
    @endpush
@endsection
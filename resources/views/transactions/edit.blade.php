@extends('layouts.dashboard')

@section('content')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Transação') }}
        </h2>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="description" :value="__('Descrição')" />
                                <x-text-input id="description" name="description" type="text" class="mt-1 block w-full" :value="old('description', $transaction->description)" required autofocus />
                                <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="amount" :value="__('Valor')" />
                                <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1 block w-full" :value="old('amount', $transaction->amount)" required />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="date" :value="__('Data')" />
                                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date', $transaction->date->format('Y-m-d'))" required />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Tipo')" />
                                <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>Receita</option>
                                    <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>Despesa</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="category_id" :value="__('Categoria')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="account_id" :value="__('Conta')" />
                                <select id="account_id" name="account_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione uma conta</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('account_id')" class="mt-2" />
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <input id="is_recurring" name="is_recurring" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_recurring', $transaction->is_recurring) ? 'checked' : '' }}>
                                    <x-input-label for="is_recurring" :value="__('Transação Recorrente')" class="ml-2" />
                                </div>
                                <x-input-error :messages="$errors->get('is_recurring')" class="mt-2" />
                            </div>

                            <div id="recurrence_fields" class="hidden">
                                <div>
                                    <x-input-label for="recurrence_interval" :value="__('Intervalo de Recorrência')" />
                                    <select id="recurrence_interval" name="recurrence_interval" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="monthly" {{ old('recurrence_interval', $transaction->recurrence_interval) === 'monthly' ? 'selected' : '' }}>Mensal</option>
                                        <option value="weekly" {{ old('recurrence_interval', $transaction->recurrence_interval) === 'weekly' ? 'selected' : '' }}>Semanal</option>
                                        <option value="yearly" {{ old('recurrence_interval', $transaction->recurrence_interval) === 'yearly' ? 'selected' : '' }}>Anual</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('recurrence_interval')" class="mt-2" />
                                </div>

                                <div class="mt-4">
                                    <x-input-label for="recurrence_end_date" :value="__('Data Final da Recorrência')" />
                                    <x-text-input id="recurrence_end_date" name="recurrence_end_date" type="date" class="mt-1 block w-full" :value="old('recurrence_end_date', optional($transaction->recurrence_end_date)->format('Y-m-d'))" />
                                    <x-input-error :messages="$errors->get('recurrence_end_date')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Atualizar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isRecurringCheckbox = document.getElementById('is_recurring');
            const recurrenceFields = document.getElementById('recurrence_fields');

            function toggleRecurrenceFields() {
                recurrenceFields.classList.toggle('hidden', !isRecurringCheckbox.checked);
            }

            isRecurringCheckbox.addEventListener('change', toggleRecurrenceFields);
            toggleRecurrenceFields();
        });
    </script>
    @endpush
    @endsection 
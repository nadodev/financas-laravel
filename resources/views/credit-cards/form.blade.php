@extends('layouts.dashboard')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Cabeçalho do formulário -->
                <div class="px-8 py-6 border-b border-gray-200">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ isset($creditCard) ? 'Editar Cartão de Crédito' : 'Novo Cartão de Crédito' }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ isset($creditCard) ? 'Atualize as informações do seu cartão de crédito.' : 'Preencha as informações do seu novo cartão de crédito.' }}
                    </p>
                </div>

                <!-- Formulário -->
                <form action="{{ isset($creditCard) ? route('credit-cards.update', $creditCard) : route('credit-cards.store') }}" 
                      method="POST" 
                      class="px-8 py-6 space-y-6">
                    @csrf
                    @if (isset($creditCard))
                        @method('PUT')
                    @endif

                    <!-- Conta -->
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="account_id">
                                Conta para Fatura
                            </label>
                            <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md @error('account_id') border-red-500 @enderror"
                                id="account_id"
                                name="account_id"
                                required>
                                <option value="">Selecione uma conta</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id', $creditCard->account_id ?? '') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nome e Bandeira -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="name">
                                    Nome no Cartão
                                </label>
                                <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('name') border-red-500 @enderror"
                                    id="name"
                                    type="text"
                                    name="name"
                                    placeholder="Ex: João da Silva"
                                    value="{{ old('name', $creditCard->name ?? '') }}"
                                    required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="brand">
                                    Bandeira
                                </label>
                                <select class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md @error('brand') border-red-500 @enderror"
                                    id="brand"
                                    name="brand"
                                    required>
                                    <option value="">Selecione uma bandeira</option>
                                    @foreach ($brands as $value => $label)
                                        <option value="{{ $value }}" {{ old('brand', $creditCard->brand ?? '') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Número do Cartão -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700" for="number">
                                Número do Cartão
                            </label>
                            <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('number') border-red-500 @enderror"
                                id="number"
                                type="text"
                                name="number"
                                placeholder="•••• •••• •••• ••••"
                                value="{{ old('number', $creditCard->number ?? '') }}"
                                required>
                            @error('number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Limite e Fechamento -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="credit_limit">
                                    Limite de Crédito
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input class="block w-full pl-10 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('credit_limit') border-red-500 @enderror"
                                        id="credit_limit"
                                        type="number"
                                        name="credit_limit"
                                        step="0.01"
                                        placeholder="0,00"
                                        value="{{ old('credit_limit', $creditCard->credit_limit ?? '') }}"
                                        required>
                                </div>
                                @error('credit_limit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="closing_day">
                                    Dia do Fechamento
                                </label>
                                <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('closing_day') border-red-500 @enderror"
                                    id="closing_day"
                                    type="number"
                                    name="closing_day"
                                    min="1"
                                    max="31"
                                    placeholder="DD"
                                    value="{{ old('closing_day', $creditCard->closing_day ?? '') }}"
                                    required>
                                @error('closing_day')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700" for="due_day">
                                    Dia do Vencimento
                                </label>
                                <input class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('due_day') border-red-500 @enderror"
                                    id="due_day"
                                    type="number"
                                    name="due_day"
                                    min="1"
                                    max="31"
                                    placeholder="DD"
                                    value="{{ old('due_day', $creditCard->due_day ?? '') }}"
                                    required>
                                @error('due_day')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('credit-cards.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ isset($creditCard) ? 'Atualizar Cartão' : 'Criar Cartão' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 
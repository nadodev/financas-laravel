@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ isset($creditCard) ? 'Editar Cartão de Crédito' : 'Novo Cartão de Crédito' }}
                </h1>

                <form action="{{ isset($creditCard) ? route('credit-cards.update', $creditCard) : route('credit-cards.store') }}" method="POST">
                    @csrf
                    @if (isset($creditCard))
                        @method('PUT')
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Nome
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $creditCard->name ?? '') }}"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="number">
                            Número do Cartão
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('number') border-red-500 @enderror"
                            id="number"
                            type="text"
                            name="number"
                            value="{{ old('number', $creditCard->number ?? '') }}"
                            required>
                        @error('number')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="brand">
                            Bandeira
                        </label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('brand') border-red-500 @enderror"
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
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="expiration_date">
                            Data de Validade
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('expiration_date') border-red-500 @enderror"
                            id="expiration_date"
                            type="date"
                            name="expiration_date"
                            value="{{ old('expiration_date', isset($creditCard) ? $creditCard->expiration_date->format('Y-m-d') : '') }}"
                            required>
                        @error('expiration_date')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="credit_limit">
                            Limite de Crédito
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('credit_limit') border-red-500 @enderror"
                            id="credit_limit"
                            type="number"
                            name="credit_limit"
                            step="0.01"
                            value="{{ old('credit_limit', $creditCard->credit_limit ?? '') }}"
                            required>
                        @error('credit_limit')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="closing_day">
                            Dia do Fechamento
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('closing_day') border-red-500 @enderror"
                            id="closing_day"
                            type="number"
                            name="closing_day"
                            min="1"
                            max="31"
                            value="{{ old('closing_day', $creditCard->closing_day ?? '') }}"
                            required>
                        @error('closing_day')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="due_day">
                            Dia do Vencimento
                        </label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('due_day') border-red-500 @enderror"
                            id="due_day"
                            type="number"
                            name="due_day"
                            min="1"
                            max="31"
                            value="{{ old('due_day', $creditCard->due_day ?? '') }}"
                            required>
                        @error('due_day')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="account_id">
                            Conta para Pagamento
                        </label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('account_id') border-red-500 @enderror"
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
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                            {{ isset($creditCard) ? 'Atualizar' : 'Criar' }}
                        </button>
                        <a href="{{ route('credit-cards.index') }}" class="text-gray-600 hover:text-gray-800">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 
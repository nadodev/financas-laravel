@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Novo Objetivo Financeiro</h2>
        </div>

        <div class="p-6">
            <form action="{{ route('financial-goals.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome do Objetivo</label>
                    <input type="text" name="name" id="name" required
                           class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('name') }}"
                           placeholder="Ex: Comprar um carro">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição (opcional)</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Descreva seu objetivo...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="target_amount" class="block text-sm font-medium text-gray-700">Valor do Objetivo</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="target_amount" id="target_amount" required
                                   class="pl-7 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ old('target_amount', request('target_amount')) }}"
                                   placeholder="0,00">
                        </div>
                    </div>

                    <div>
                        <label for="monthly_amount" class="block text-sm font-medium text-gray-700">Valor Mensal</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="monthly_amount" id="monthly_amount" required
                                   class="pl-7 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                   value="{{ old('monthly_amount', request('monthly_amount')) }}"
                                   placeholder="0,00">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="target_date" class="block text-sm font-medium text-gray-700">Data Limite</label>
                        <input type="date" name="target_date" id="target_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ old('target_date', request('target_date')) }}">
                    </div>

                    <div>
                        <label for="account_id" class="block text-sm font-medium text-gray-700">Conta Vinculada (opcional)</label>
                        <select name="account_id" id="account_id"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione uma conta</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <a href="{{ route('financial-goals.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Criar Objetivo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
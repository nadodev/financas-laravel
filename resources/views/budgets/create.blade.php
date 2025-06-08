@extends('layouts.dashboard')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-900">Novo Orçamento</h2>
    </div>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('budgets.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white shadow rounded-lg p-6">
                <div class="grid grid-cols-1 gap-6">
                    <!-- Categoria -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <select id="category_id" name="category_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('category_id') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Valor -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount') }}"
                                class="block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="0,00">
                        </div>
                        @error('amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Período -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Data Inicial</label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('start_date') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">Data Final</label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('end_date') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Recorrência -->
                    <div>
                        <label for="recurrence" class="block text-sm font-medium text-gray-700">Recorrência</label>
                        <select id="recurrence" name="recurrence" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md @error('recurrence') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Sem recorrência</option>
                            <option value="monthly" {{ old('recurrence') == 'monthly' ? 'selected' : '' }}>Mensal</option>
                            <option value="quarterly" {{ old('recurrence') == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                            <option value="yearly" {{ old('recurrence') == 'yearly' ? 'selected' : '' }}>Anual</option>
                        </select>
                        @error('recurrence')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                        <textarea id="description" name="description" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('description') border-red-300 text-red-900 placeholder-red-300 focus:outline-none focus:ring-red-500 focus:border-red-500 @enderror"
                            placeholder="Descrição opcional do orçamento">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end space-x-3">
                    <a href="{{ route('budgets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Salvar
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection 
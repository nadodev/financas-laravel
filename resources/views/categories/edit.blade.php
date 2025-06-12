@extends('layouts.dashboard')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Editar Categoria</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="name" id="name" required
                       class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('name', $category->name) }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="type" id="type" required
                        class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="income" {{ old('type', $category->type) === 'income' ? 'selected' : '' }}>Receita</option>
                    <option value="expense" {{ old('type', $category->type) === 'expense' ? 'selected' : '' }}>Despesa</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="color" class="block text-sm font-medium text-gray-700">Cor</label>
                <input type="color" name="color" id="color" required
                       class="mt-1 block w-full h-10 rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                       value="{{ old('color', $category->color) }}">
                @error('color')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700">Ícone</label>
                <x-icon-selector name="icon" :value="old('icon', $category->icon)" required />
                @error('icon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('categories.index') }}"
                   class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 
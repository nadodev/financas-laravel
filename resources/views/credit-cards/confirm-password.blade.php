@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Confirmar Acesso ao Cartão</h2>
            <p class="mt-2 text-sm text-gray-600">
                Por segurança, digite a senha do cartão {{ $creditCard->name }} para continuar.
            </p>
        </div>

        <form method="POST" action="{{ route('credit-cards.confirm-password', $creditCard) }}" class="space-y-6">
            @csrf

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Senha do Cartão</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       required
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('credit-cards.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                    Cancelar
                </a>

                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 
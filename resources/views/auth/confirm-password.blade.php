@extends('layouts.dashboard')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-4 text-sm text-gray-600">
            Esta é uma área segura da aplicação. Por favor, confirme sua senha antes de continuar.
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <label for="password" class="block font-medium text-sm text-gray-700">Senha</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="flex justify-end mt-4">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

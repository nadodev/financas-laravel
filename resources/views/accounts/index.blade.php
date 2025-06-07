@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Contas</h1>
        <a href="{{ route('accounts.create') }}" 
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Nova Conta
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Resumo das Contas -->
    @include('accounts._balance_summary')

    <!-- Filtros -->
    @include('accounts._filters')

    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($accounts as $account)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $account->name }}
                                    <span class="ml-2 text-xs text-gray-500">
                                        {{ $account->bank }} - Ag: {{ $account->agency }} / CC: {{ $account->account_number }}
                                    </span>
                                </p>
                                <div class="ml-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $account->balance >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        R$ {{ number_format($account->balance, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span>{{ $account->type_formatted }}</span>
                                @if($account->notes)
                                    <span class="mx-2">•</span>
                                    <span class="truncate">{{ $account->notes }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex space-x-4">
                            <a href="{{ route('accounts.show', $account) }}" 
                                class="font-medium text-gray-600 hover:text-gray-500">
                                Detalhes
                            </a>
                            <a href="{{ route('accounts.edit', $account) }}" 
                                class="font-medium text-indigo-600 hover:text-indigo-500">
                                Editar
                            </a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" 
                                onsubmit="return confirm('Tem certeza que deseja excluir esta conta? Todas as transações associadas serão excluídas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 hover:text-red-500">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-4">
                    <p class="text-gray-500 text-center">Nenhuma conta cadastrada.</p>
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        {{ $accounts->links() }}
    </div>
</div>
@endsection 
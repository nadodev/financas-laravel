@extends('layouts.dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
  <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Orçamentos</h1>
        <a href="{{ route('budgets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Novo Orçamento
        </a>
    </div>
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif
 <div class="bg-white shadow overflow-hidden sm:rounded-md">
            @if($budgets->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Nenhum orçamento cadastrado.
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($budgets as $budget)
                        <li>
                            <div class="px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $budget->category->name }}</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            @if($budget->start_date && $budget->end_date)
                                                {{ $budget->start_date->format('d/m/Y') }} até {{ $budget->end_date->format('d/m/Y') }}
                                            @else
                                                Sem período definido
                                            @endif
                                            @if($budget->recurrence)
                                                • {{ ucfirst($budget->recurrence) }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <a href="{{ route('budgets.edit', $budget) }}" class="text-blue-600 hover:text-blue-900">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="flex justify-between text-sm text-gray-500 mb-1">
                                        <span>R$ {{ number_format($budget->spent, 2, ',', '.') }} de R$ {{ number_format($budget->amount, 2, ',', '.') }}</span>
                                        <span>{{ number_format($budget->percentage, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $budget->percentage <= 80 ? 'bg-green-600' : ($budget->percentage <= 100 ? 'bg-yellow-600' : 'bg-red-600') }}"
                                            style="width: {{ min($budget->percentage, 100) }}%">
                                        </div>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Restante: R$ {{ number_format($budget->remaining, 2, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
</div>
@endsection 
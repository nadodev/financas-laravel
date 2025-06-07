@extends('layouts.dashboard')

@section('header')
    Orçamentos
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900">Orçamentos - {{ Carbon\Carbon::create()->month($currentMonth)->locale('pt_BR')->monthName }} {{ $currentYear }}</h2>
                <p class="mt-1 text-sm text-gray-600">Gerencie seus orçamentos mensais por categoria</p>
            </div>
            <a href="{{ route('budgets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Novo Orçamento
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Orçamento Total</p>
                        <p class="text-2xl font-semibold text-gray-900">R$ {{ number_format($totalBudget, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-wallet text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Gasto</p>
                        <p class="text-2xl font-semibold text-red-600">R$ {{ number_format($totalSpent, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-chart-line text-red-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Saldo Restante</p>
                        <p class="text-2xl font-semibold {{ $remainingBudget >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($remainingBudget, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-piggy-bank text-green-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Budgets List -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                @if($budgets->isEmpty())
                    <div class="text-center py-8">
                        <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500">Nenhum orçamento definido para este mês.</p>
                        <a href="{{ route('budgets.create') }}" class="mt-2 inline-block text-blue-600 hover:text-blue-800">
                            Criar primeiro orçamento
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($budgets as $budget)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">{{ $budget->category->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $budget->notes }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('budgets.edit', $budget) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este orçamento?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Progresso</span>
                                        <span class="font-medium">{{ $budget->progress_percentage }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="h-2.5 rounded-full {{ $budget->progress_percentage >= 100 ? 'bg-red-600' : 'bg-blue-600' }}"
                                            style="width: {{ $budget->progress_percentage }}%">
                                        </div>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">
                                            Gasto: R$ {{ number_format($budget->spent, 2, ',', '.') }}
                                        </span>
                                        <span class="text-gray-600">
                                            Orçado: R$ {{ number_format($budget->amount, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 
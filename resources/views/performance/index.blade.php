@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Meu Desempenho</h1>
    </div>

    {{-- Métricas do Mês Atual --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {{-- Receitas --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Receitas</h3>
                <span class="text-sm {{ $comparison['income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $comparison['income'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs(round($comparison['income'], 1)) }}%
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900">
                R$ {{ number_format($currentMonthMetrics['total_income'], 2, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">vs mês anterior</p>
        </div>

        {{-- Despesas --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Despesas</h3>
                <span class="text-sm {{ $comparison['expense'] <= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $comparison['expense'] <= 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                    {{ abs(round($comparison['expense'], 1)) }}%
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900">
                R$ {{ number_format($currentMonthMetrics['total_expense'], 2, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">vs mês anterior</p>
        </div>

        {{-- Economias --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Economias</h3>
                <span class="text-sm {{ $comparison['savings'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $comparison['savings'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs(round($comparison['savings'], 1)) }}%
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900">
                R$ {{ number_format($currentMonthMetrics['savings'], 2, ',', '.') }}
            </p>
            <p class="text-sm text-gray-500 mt-1">vs mês anterior</p>
        </div>

        {{-- Taxa de Economia --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500">Taxa de Economia</h3>
                <span class="text-sm {{ $comparison['savings_rate'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas fa-{{ $comparison['savings_rate'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                    {{ abs(round($comparison['savings_rate'], 1)) }}%
                </span>
            </div>
            <p class="text-2xl font-semibold text-gray-900">
                {{ round($currentMonthMetrics['savings_rate'], 1) }}%
            </p>
            <p class="text-sm text-gray-500 mt-1">vs mês anterior</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Evolução dos Últimos 6 Meses --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Evolução dos Últimos 6 Meses</h3>
            <div class="h-80">
                <canvas id="monthlyEvolutionChart"></canvas>
            </div>
        </div>

        {{-- Top Categorias de Despesas --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Categorias de Despesas</h3>
            <div class="space-y-4">
                @php
                    $maxExpense = $topExpenseCategories->max('total');
                @endphp
                @foreach($topExpenseCategories as $category)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center">
                                <span class="w-8 h-8 rounded-full flex items-center justify-center mr-2" style="background-color: {{ $category->color }}">
                                    <i class="{{ $category->icon }} text-white"></i>
                                </span>
                                <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm text-gray-500">
                                    R$ {{ number_format($category->total, 2, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400 block">
                                    {{ number_format(($category->total / $currentMonthMetrics['total_expense']) * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ ($category->total / $maxExpense) * 100 }}%; background-color: {{ $category->color }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const evolution = @json($monthlyEvolution);
    const labels = evolution.map(item => item.month);
    const income = evolution.map(item => item.income);
    const expense = evolution.map(item => item.expense);
    const savings = evolution.map(item => item.savings);

    const ctx = document.getElementById('monthlyEvolutionChart').getContext('2d');
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Receitas',
                data: income,
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                fill: true
            },
            {
                label: 'Despesas',
                data: expense,
                borderColor: '#EF4444',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                fill: true
            },
            {
                label: 'Economias',
                data: savings,
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true
            }
        ]
    };

    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let value = context.parsed.y;
                            return `${label}: R$ ${value.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 
@extends('layouts.dashboard')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @php
            $settings = auth()->user()->dashboardSetting;
            $visibleSections = $settings ? $settings->visible_sections : [];
            // Se não houver configurações, mostrar tudo por padrão
            $showAll = !$settings || empty($visibleSections);
        @endphp

        <!-- Cards de Resumo -->
        @if($showAll || in_array('summary_cards', $visibleSections))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Saldo Total</h3>
                    <p class="text-3xl font-bold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        R$ {{ number_format($totalBalance, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Receitas do Mês</h3>
                    <p class="text-3xl font-bold text-green-600">
                        R$ {{ number_format($monthlyIncome, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Despesas do Mês</h3>
                    <p class="text-3xl font-bold text-red-600">
                        R$ {{ number_format($monthlyExpenses, 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Gráfico Mensal -->
            @if($showAll || in_array('monthly_chart', $visibleSections))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Receitas vs Despesas (Últimos 6 meses)</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
            @endif

            <!-- Gráfico de Categorias -->
            @if($showAll || in_array('categories_chart', $visibleSections))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Despesas por Categoria</h3>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <!-- Resumo das Contas -->
            @if($showAll || in_array('accounts_summary', $visibleSections))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumo das Contas</h3>
                    <div class="space-y-4">
                        @foreach($accounts as $account)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">{{ $account->name }}</span>
                            <span class="font-semibold {{ $account->balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                R$ {{ number_format($account->balance, 2, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Progresso das Metas -->
            @if($showAll || in_array('goals_progress', $visibleSections))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Metas Financeiras</h3>
                    <div class="space-y-4">
                        @foreach($goals as $goal)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-700">{{ $goal->name }}</span>
                                <span class="text-sm text-gray-500">
                                    R$ {{ number_format($goal->current_amount, 2, ',', '.') }} / 
                                    R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $goal->progress_percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Transações Recentes -->
        @if($showAll || in_array('recent_transactions', $visibleSections))
        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Transações Recentes</h3>
                    <div class="space-y-4">
                        @foreach($recentTransactions as $transaction)
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-900">{{ $transaction->description }}</p>
                                <p class="text-sm text-gray-500">{{ $transaction->date->format('d/m/Y') }}</p>
                            </div>
                            <span class="font-semibold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Orçamentos -->
        @if($showAll || in_array('budgets', $visibleSections))
        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Orçamentos do Mês</h3>
                    <div class="space-y-4">
                        @foreach($budgets as $budget)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-gray-700">{{ $budget->category->name }}</span>
                                <span class="text-sm text-gray-500">
                                    R$ {{ number_format($budget->spent, 2, ',', '.') }} / 
                                    R$ {{ number_format($budget->amount, 2, ',', '.') }}
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-blue-600 h-2.5 rounded-full" 
                                     style="width: {{ $budget->percentage }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Métricas de Desempenho -->
        @if($showAll || in_array('performance_metrics', $visibleSections))
        <div class="mt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Métricas de Desempenho</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Taxa de Economia</p>
                            <p class="text-xl font-semibold">{{ number_format($performance['savings_rate'], 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Despesas/Receitas</p>
                            <p class="text-xl font-semibold">{{ number_format($performance['expense_income_ratio'], 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Aderência ao Orçamento</p>
                            <p class="text-xl font-semibold">{{ number_format($performance['budget_adherence'], 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Progresso das Metas</p>
                            <p class="text-xl font-semibold">{{ number_format($performance['goals_progress'], 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuração dos gráficos
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    };

    // Gráfico Mensal (se existir)
    const monthlyChartEl = document.getElementById('monthlyChart');
    if (monthlyChartEl) {
        const ctx = monthlyChartEl.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Receitas',
                    data: [1200, 1900, 3000, 5000, 2000, 3000],
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.1
                },
                {
                    label: 'Despesas',
                    data: [1000, 2000, 2500, 2700, 2200, 2800],
                    borderColor: 'rgb(239, 68, 68)',
                    tension: 0.1
                }]
            },
            options: chartOptions
        });
    }

    // Gráfico de Categorias (se existir)
    const categoryChartEl = document.getElementById('categoryChart');
    if (categoryChartEl) {
        const ctx = categoryChartEl.getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Alimentação', 'Transporte', 'Moradia', 'Lazer', 'Outros'],
                datasets: [{
                    data: [300, 200, 800, 150, 100],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                        'rgb(239, 68, 68)',
                        'rgb(245, 158, 11)',
                        'rgb(168, 85, 247)'
                    ]
                }]
            },
            options: {
                ...chartOptions,
                cutout: '60%'
            }
        });
    }
});
</script>
@endpush
@endsection 
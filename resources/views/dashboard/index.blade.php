@extends('layouts.dashboard')

@section('header')
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold text-gray-900">Dashboard</h2>
        <form method="GET" action="{{ route('dashboard') }}" class="flex space-x-4">
            <div class="flex items-center space-x-2">
                <select name="month" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @foreach($months as $value => $label)
                        <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <select name="year" class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Filtrar
                </button>
            </div>
        </form>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Saldo Total -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Saldo Total</p>
                        <p class="text-2xl font-semibold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($totalBalance, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 {{ $totalBalance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full">
                        <i class="fas fa-wallet {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}"></i>
                    </div>
                </div>
            </div>

            <!-- Receitas do Mês -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Receitas ({{ $months[$selectedMonth] }})</p>
                        <p class="text-2xl font-semibold text-green-600">R$ {{ number_format($monthlyIncome, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-arrow-up text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Despesas do Mês -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Despesas ({{ $months[$selectedMonth] }})</p>
                        <p class="text-2xl font-semibold text-red-600">R$ {{ number_format($monthlyExpenses, 2, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <i class="fas fa-arrow-down text-red-600"></i>
                    </div>
                </div>
            </div>

            <!-- Economia do Mês -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Economia ({{ $months[$selectedMonth] }})</p>
                        <p class="text-2xl font-semibold {{ $monthlySavings >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($monthlySavings, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-piggy-bank text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Despesas por Categoria -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Despesas por Categoria ({{ $months[$selectedMonth] }})</h3>
                <div class="h-80">
                    @if($expensesByCategory->isEmpty())
                        <div class="flex items-center justify-center h-full">
                            <p class="text-gray-500">Nenhuma despesa registrada neste mês</p>
                        </div>
                    @else
                        <canvas id="expensesByCategory"></canvas>
                    @endif
                </div>
            </div>

            <!-- Fluxo de Caixa -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Fluxo de Caixa (Últimos 6 meses)</h3>
                <div class="h-80">
                    <canvas id="cashFlow"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Transações Recentes ({{ $months[$selectedMonth] }})</h3>
                    <a href="{{ route('transactions.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                        Ver Todas
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Nenhuma transação registrada neste mês
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gráfico de Despesas por Categoria
            @if(!$expensesByCategory->isEmpty())
            const expensesByCategoryCtx = document.getElementById('expensesByCategory').getContext('2d');
            new Chart(expensesByCategoryCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($expensesByCategory->pluck('category')) !!},
                    datasets: [{
                        data: {!! json_encode($expensesByCategory->pluck('amount')) !!},
                        backgroundColor: [
                            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#6366F1',
                            '#EC4899', '#8B5CF6', '#14B8A6', '#F97316', '#06B6D4'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });
            @endif

            // Gráfico de Fluxo de Caixa
            const cashFlowCtx = document.getElementById('cashFlow').getContext('2d');
            new Chart(cashFlowCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($cashFlow->pluck('date')) !!},
                    datasets: [{
                        label: 'Receitas',
                        data: {!! json_encode($cashFlow->pluck('income')) !!},
                        borderColor: '#10B981',
                        backgroundColor: '#10B98120',
                        fill: true
                    }, {
                        label: 'Despesas',
                        data: {!! json_encode($cashFlow->pluck('expenses')) !!},
                        borderColor: '#EF4444',
                        backgroundColor: '#EF444420',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection 
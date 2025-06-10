@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Relatórios Financeiros</h1>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form action="/reports" method="GET" class="space-y-6" id="reportForm" onsubmit="return handleSubmit(event)">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="report_type" class="block text-sm font-medium text-gray-700">Tipo de Relatório</label>
                    <select name="report_type" id="report_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Selecione um tipo</option>
                        <option value="income-expense" {{ request('report_type') === 'income-expense' ? 'selected' : '' }}>Receitas e Despesas</option>
                        <option value="categories" {{ request('report_type') === 'categories' ? 'selected' : '' }}>Categorias</option>
                        <option value="goals" {{ request('report_type') === 'goals' ? 'selected' : '' }}>Objetivos</option>
                        <option value="accounts" {{ request('report_type') === 'accounts' ? 'selected' : '' }}>Contas</option>
                    </select>
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Data Inicial</label>
                    <input type="date" name="start_date" id="start_date" value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Data Final</label>
                    <input type="date" name="end_date" id="end_date" value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                @if(isset($accounts) && $accounts->isNotEmpty())
                <div class="md:col-span-3">
                    <label for="account_id" class="block text-sm font-medium text-gray-700">Conta (opcional)</label>
                    <select name="account_id" id="account_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas as contas</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>

            <div class="flex justify-end space-x-3">
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Gerar Relatório
                </button>
            </div>
        </form>
    </div>

    @if(isset($reportType))
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">
                        @if($reportType === 'income-expense')
                            Relatório de Receitas e Despesas
                        @elseif($reportType === 'categories')
                            Relatório por Categorias
                        @elseif($reportType === 'goals')
                            Relatório de Objetivos
                        @elseif($reportType === 'accounts')
                            Relatório de Contas
                        @endif
                    </h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('reports.export', ['type' => $reportType, 'format' => 'pdf'] + request()->all()) }}"
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                            <i class="fas fa-file-pdf mr-2"></i>
                            Exportar PDF
                        </a>
                        <a href="{{ route('reports.export', ['type' => $reportType, 'format' => 'xlsx'] + request()->all()) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                            <i class="fas fa-file-excel mr-2"></i>
                            Exportar Excel
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if($reportType === 'income-expense')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Total de Receitas</h4>
                            <p class="text-2xl font-semibold text-green-600">
                                R$ {{ number_format($totalIncome, 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Total de Despesas</h4>
                            <p class="text-2xl font-semibold text-red-600">
                                R$ {{ number_format($totalExpense, 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Saldo</h4>
                            <p class="text-2xl font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                R$ {{ number_format($balance, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($reportType === 'categories')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($categoryIncome->isNotEmpty())
                            <div class="bg-white shadow rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Receitas por Categoria</h3>
                                <div class="space-y-4">
                                    @foreach($categoryIncome as $category)
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-sm font-medium text-gray-600">{{ $category['name'] }}</span>
                                                <span class="text-sm font-semibold text-green-600">
                                                    R$ {{ number_format($category['total'], 2, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ isset($category['percentage']) ? $category['percentage'] : 0 }}%"></div>
                                            </div>
                                            <div class="flex justify-between items-center mt-1">
                                                <span class="text-xs text-gray-500">{{ $category['count'] }} transações</span>
                                                <span class="text-xs text-gray-500">{{ isset($category['percentage']) ? number_format($category['percentage'], 1) : '0.0' }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($categoryExpense->isNotEmpty())
                            <div class="bg-white shadow rounded-lg p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Despesas por Categoria</h3>
                                <div class="space-y-4">
                                    @foreach($categoryExpense as $category)
                                        <div>
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-sm font-medium text-gray-600">{{ $category['name'] }}</span>
                                                <span class="text-sm font-semibold text-red-600">
                                                    R$ {{ number_format($category['total'], 2, ',', '.') }}
                                                </span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="bg-red-600 h-2 rounded-full" style="width: {{ isset($category['percentage']) ? $category['percentage'] : 0 }}%"></div>
                                            </div>
                                            <div class="flex justify-between items-center mt-1">
                                                <span class="text-xs text-gray-500">{{ $category['count'] }} transações</span>
                                                <span class="text-xs text-gray-500">{{ isset($category['percentage']) ? number_format($category['percentage'], 1) : '0.0' }}%</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                @elseif($reportType === 'goals')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Total de Objetivos</h4>
                            <p class="text-2xl font-semibold text-gray-900">{{ $totalGoals }}</p>
                        </div>
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Valor Total</h4>
                            <p class="text-2xl font-semibold text-gray-900">
                                R$ {{ number_format($totalAmount, 2, ',', '.') }}
                            </p>
                        </div>
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Valor Atual</h4>
                            <p class="text-2xl font-semibold text-gray-900">
                                R$ {{ number_format($currentAmount, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white shadow rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Análise de Progresso</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-600">Em Andamento</span>
                                        <span class="text-sm font-semibold text-blue-600">
                                            {{ $progressAnalysis['in_progress'] }} objetivos
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalGoals > 0 ? ($progressAnalysis['in_progress'] / $totalGoals) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-600">Concluídos</span>
                                        <span class="text-sm font-semibold text-green-600">
                                            {{ $progressAnalysis['completed'] }} objetivos
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $totalGoals > 0 ? ($progressAnalysis['completed'] / $totalGoals) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-sm font-medium text-gray-600">Cancelados</span>
                                        <span class="text-sm font-semibold text-red-600">
                                            {{ $progressAnalysis['cancelled'] }} objetivos
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-600 h-2 rounded-full" style="width: {{ $totalGoals > 0 ? ($progressAnalysis['cancelled'] / $totalGoals) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Meta</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Atual</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($goals as $goal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $goal->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            R$ {{ number_format($goal->current_amount, 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2 max-w-xs">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0 }}%"></div>
                                                </div>
                                                <span class="ml-2 text-sm text-gray-600">
                                                    {{ number_format($goal->target_amount > 0 ? min(100, ($goal->current_amount / $goal->target_amount) * 100) : 0, 1) }}%
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $goal->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($goal->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-blue-100 text-blue-800') }}">
                                                {{ $goal::$statuses[$goal->status] ?? ucfirst($goal->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @elseif($reportType === 'accounts')
                    <div class="grid grid-cols-1 gap-6">
                        <div class="bg-white shadow rounded-lg p-4">
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Saldo Total</h4>
                            <p class="text-2xl font-semibold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                R$ {{ number_format($totalBalance, 2, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 bg-white shadow rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conta</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Atual</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Receitas</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Despesas</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Transações</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Média/Transação</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($balances as $balance)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $balance['name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $balance['current_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            R$ {{ number_format($balance['current_balance'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600">
                                            R$ {{ number_format($balance['total_income'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-red-600">
                                            R$ {{ number_format($balance['total_expense'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                            {{ $balance['transaction_count'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">
                                            R$ {{ number_format($balance['transaction_count'] > 0 ? 
                                                ($balance['total_income'] + $balance['total_expense']) / $balance['transaction_count'] : 0, 
                                                2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center text-gray-500">
                Selecione um tipo de relatório e um período para começar.
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function handleSubmit(e) {
    e.preventDefault();
    
    console.log('Form submitted');
    const form = document.getElementById('reportForm');
    console.log('Action:', form.action);
    console.log('Method:', form.method);
    
    const formData = new FormData(form);
    const params = new URLSearchParams();
    
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
        params.append(key, value);
    }

    // Construir a URL com os parâmetros
    const url = form.action + '?' + params.toString();
    console.log('Redirecting to:', url);
    
    // Redirecionar para a URL construída
    window.location.href = url;
    
    return false;
}

// Adicionar log quando a página carrega
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded');
    console.log('Current URL:', window.location.href);
    const form = document.getElementById('reportForm');
    console.log('Form found:', !!form);
    if (form) {
        console.log('Form action:', form.action);
        console.log('Form method:', form.method);
    }
});
</script>
@endpush 
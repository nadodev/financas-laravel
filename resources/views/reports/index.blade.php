@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Relatórios Financeiros</h1>
        @if(isset($reportType))
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
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Relatório de Receitas e Despesas --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Receitas e Despesas</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.show', 'income-expense') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Data Inicial</label>
                        <input type="date" name="start_date" id="start_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">Data Final</label>
                        <input type="date" name="end_date" id="end_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                        <select name="account_id" id="account_id"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas as Contas</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Gerar Relatório
                    </button>
                </form>
            </div>
        </div>

        {{-- Relatório de Categorias --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Categorias</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.show', 'categories') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="category_start_date" class="block text-sm font-medium text-gray-700">Data Inicial</label>
                        <input type="date" name="start_date" id="category_start_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ request('start_date', now()->startOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="category_end_date" class="block text-sm font-medium text-gray-700">Data Final</label>
                        <input type="date" name="end_date" id="category_end_date" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ request('end_date', now()->endOfMonth()->format('Y-m-d')) }}">
                    </div>
                    <div>
                        <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                        <select name="account_id" id="account_id"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas as Contas</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                        <select name="type" id="type" required
                                class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Todos</option>
                            <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Receitas</option>
                            <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Despesas</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Gerar Relatório
                    </button>
                </form>
            </div>
        </div>

        {{-- Relatório de Objetivos --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Objetivos Financeiros</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.show', 'goals') }}" method="GET" class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">Todos</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Em Andamento</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluídos</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelados</option>
                        </select>
                    </div>
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <i class="fas fa-bullseye mr-2"></i>
                        Gerar Relatório
                    </button>
                </form>
            </div>
        </div>

        {{-- Relatório de Contas --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Contas</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('reports.show', 'accounts') }}" method="GET" class="space-y-4">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        <i class="fas fa-wallet mr-2"></i>
                        Gerar Relatório
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Resultados dos Relatórios --}}
    @if(isset($reportType))
        <div class="mt-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        @if($reportType === 'income-expense')
                            Relatório de Receitas e Despesas
                        @elseif($reportType === 'categories')
                            Relatório por Categorias
                        @elseif($reportType === 'goals')
                            Relatório de Objetivos
                        @elseif($reportType === 'accounts')
                            Relatório de Contas
                        @endif
                    </h3>
                </div>
                <div class="p-6">
                    @if($reportType === 'income-expense')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Total de Receitas</h4>
                                <p class="text-lg font-semibold text-green-600">
                                    R$ {{ number_format($totalIncome, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Total de Despesas</h4>
                                <p class="text-lg font-semibold text-red-600">
                                    R$ {{ number_format($totalExpense, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Saldo</h4>
                                <p class="text-lg font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($balance, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Análise Mensal --}}
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Análise Mensal</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mês</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Receitas</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Despesas</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($monthlyAnalysis as $month => $data)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('M/Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                                    R$ {{ number_format($data['income'], 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                                    R$ {{ number_format($data['expense'], 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $data['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    R$ {{ number_format($data['balance'], 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Lista de Transações --}}
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Transações</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $transaction->date->format('d/m/Y') }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $transaction->description }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-900">
                                                    {{ $transaction->category->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @elseif($reportType === 'categories')
                        <div class="mb-6 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">Relatório por Categorias</h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('reports.export-excel', ['report_type' => 'categories'] + request()->except('_token')) }}" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-file-excel mr-2"></i>
                                    Excel
                                </a>
                                <a href="{{ route('reports.export-pdf', ['report_type' => 'categories'] + request()->except('_token')) }}" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-file-pdf mr-2"></i>
                                    PDF
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            @if($categoryIncome->isNotEmpty())
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-arrow-up text-green-500 mr-2"></i>
                                            Receitas por Categoria
                                        </h3>
                                        <span class="text-2xl font-bold text-green-600">
                                            R$ {{ number_format($totalIncome, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flow-root">
                                        <ul role="list" class="-mb-8">
                                            @foreach($categoryIncome as $category)
                                            <li class="py-3">
                                                <div class="flex items-center justify-between space-x-4">
                                                    <div class="flex items-center min-w-0 space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <span class="flex items-center justify-center h-8 w-8 rounded-full" style="background-color: {{ $category['color'] ?? '#E5E7EB' }}">
                                                                <i class="fas fa-{{ $category['icon'] ?? 'circle' }} text-white"></i>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $category['name'] }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $category['count'] }} transações
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="flex-none text-sm font-medium text-gray-900">
                                                            R$ {{ number_format($category['total'], 2, ',', '.') }}
                                                        </span>
                                                        <span class="flex-none text-sm font-medium text-gray-500">
                                                            {{ number_format($category['percentage'], 1) }}%
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="bg-gray-200 rounded-full h-2">
                                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($categoryExpense->isNotEmpty())
                            <div class="bg-white rounded-lg shadow">
                                <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                            <i class="fas fa-arrow-down text-red-500 mr-2"></i>
                                            Despesas por Categoria
                                        </h3>
                                        <span class="text-2xl font-bold text-red-600">
                                            R$ {{ number_format($totalExpense, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flow-root">
                                        <ul role="list" class="-mb-8">
                                            @foreach($categoryExpense as $category)
                                            <li class="py-3">
                                                <div class="flex items-center justify-between space-x-4">
                                                    <div class="flex items-center min-w-0 space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <span class="flex items-center justify-center h-8 w-8 rounded-full" style="background-color: {{ $category['color'] ?? '#E5E7EB' }}">
                                                                <i class="fas fa-{{ $category['icon'] ?? 'circle' }} text-white"></i>
                                                            </span>
                                                        </div>
                                                        <div class="min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $category['name'] }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $category['count'] }} transações
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="flex-none text-sm font-medium text-gray-900">
                                                            R$ {{ number_format($category['total'], 2, ',', '.') }}
                                                        </span>
                                                        <span class="flex-none text-sm font-medium text-gray-500">
                                                            {{ number_format($category['percentage'], 1) }}%
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="bg-gray-200 rounded-full h-2">
                                                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $category['percentage'] }}%"></div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($trends->isNotEmpty())
                        <div class="mt-6 bg-white rounded-lg shadow">
                            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                                    Análise de Tendências
                                </h3>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <div class="flow-root">
                                    <ul role="list" class="divide-y divide-gray-200">
                                        @foreach($trends as $trend)
                                        <li class="py-4">
                                            <div class="flex items-center justify-between">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900">
                                                        {{ $trend['category_name'] }}
                                                    </p>
                                                    <div class="flex items-center mt-1">
                                                        <span class="text-sm text-gray-500">
                                                            R$ {{ number_format($trend['previous_total'], 2, ',', '.') }}
                                                        </span>
                                                        <i class="fas fa-arrow-right mx-2 text-gray-400"></i>
                                                        <span class="text-sm text-gray-500">
                                                            R$ {{ number_format($trend['current_total'], 2, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $trend['change_percentage'] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $trend['change_percentage'] >= 0 ? '+' : '' }}{{ number_format($trend['change_percentage'], 1) }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                    @elseif($reportType === 'goals')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Total de Objetivos</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $totalGoals }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Valor Total</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    R$ {{ number_format($totalAmount, 2, ',', '.') }}
                                </p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Valor Atual</h4>
                                <p class="text-lg font-semibold text-gray-900">
                                    R$ {{ number_format($currentAmount, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Análise de Progresso --}}
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Análise de Progresso</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="text-sm font-medium text-gray-500 mb-2">Em Andamento</h5>
                                    <p class="text-lg font-semibold text-blue-600">{{ $progressAnalysis['in_progress'] }}</p>
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($progressAnalysis['in_progress'] / $totalGoals) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="text-sm font-medium text-gray-500 mb-2">Concluídos</h5>
                                    <p class="text-lg font-semibold text-green-600">{{ $progressAnalysis['completed'] }}</p>
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ ($progressAnalysis['completed'] / $totalGoals) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h5 class="text-sm font-medium text-gray-500 mb-2">Cancelados</h5>
                                    <p class="text-lg font-semibold text-red-600">{{ $progressAnalysis['cancelled'] }}</p>
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ ($progressAnalysis['cancelled'] / $totalGoals) * 100 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Lista de Objetivos --}}
                        <div class="mt-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Objetivos</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atual</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Início</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Final</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($goals as $goal)
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-gray-900">{{ $goal->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    R$ {{ number_format($goal->current_amount, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $goal->progress_percentage }}%"></div>
                                                    </div>
                                                    <div class="text-xs text-gray-500 mt-1">{{ number_format($goal->progress_percentage, 2) }}%</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($goal->status === 'completed')
                                                            bg-green-100 text-green-800
                                                        @elseif($goal->status === 'cancelled')
                                                            bg-red-100 text-red-800
                                                        @else
                                                            bg-blue-100 text-blue-800
                                                        @endif">
                                                        {{ $goal::$statuses[$goal->status] }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $goal->start_date ? $goal->start_date->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $goal->end_date ? $goal->end_date->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    @elseif($reportType === 'accounts')
                        <div class="mb-6">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Saldo Total</h4>
                                <p class="text-lg font-semibold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($totalBalance, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conta</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Atual</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Receitas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total de Despesas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade de Transações</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Média por Transação</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($balances as $balance)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $balance['name'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $balance['current_balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                R$ {{ number_format($balance['current_balance'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                                                R$ {{ number_format($balance['total_income'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                                R$ {{ number_format($balance['total_expense'], 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $balance['transaction_count'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
        </div>
    @endif
</div>
@endsection 
@extends('layouts.dashboard')
@section('header')
    <div class="">
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
            <h2 class="text-2xl font-bold text-gray-900 ml-8 mr-8">Visão Geral Financeira</h2>
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap gap-3">
                <div class="flex items-center space-x-2">
                    <select name="month" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700">
                        @foreach($months as $value => $label)
                            <option value="{{ $value }}" {{ $selectedMonth == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-gray-700">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out shadow-sm">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filtrar</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Performance Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Taxa de Economia -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Taxa de Economia</p>
                            <div class="tooltip" title="Percentual da renda que está sendo economizada">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold {{ $performance['savings_rate'] >= 20 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ number_format($performance['savings_rate'], 1) }}%
                        </p>
                        <div class="flex items-center space-x-1">
                            <span class="text-sm {{ $performance['savings_rate'] >= 20 ? 'text-green-600' : 'text-yellow-600' }}">
                                Meta: 20%
                            </span>
                            @if($performance['savings_rate'] >= 20)
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 {{ $performance['savings_rate'] >= 20 ? 'bg-green-100' : 'bg-yellow-100' }} rounded-full">
                        <svg class="w-8 h-8 {{ $performance['savings_rate'] >= 20 ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Relação Despesa/Renda -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Despesa/Renda</p>
                            <div class="tooltip" title="Percentual da renda comprometido com despesas">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold {{ $performance['expense_income_ratio'] <= 80 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($performance['expense_income_ratio'], 1) }}%
                        </p>
                        <div class="flex items-center space-x-1">
                            <span class="text-sm {{ $performance['expense_income_ratio'] <= 80 ? 'text-green-600' : 'text-red-600' }}">
                                Meta: Máx 80%
                            </span>
                            @if($performance['expense_income_ratio'] <= 80)
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 {{ $performance['expense_income_ratio'] <= 80 ? 'bg-green-100' : 'bg-red-100' }} rounded-full">
                        <svg class="w-8 h-8 {{ $performance['expense_income_ratio'] <= 80 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2M3 16V6a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Aderência ao Orçamento -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Aderência ao Orçamento</p>
                            <div class="tooltip" title="Quão bem você está seguindo seu orçamento planejado">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold {{ $performance['budget_adherence'] >= 90 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ number_format($performance['budget_adherence'], 1) }}%
                        </p>
                        <div class="flex items-center space-x-1">
                            <span class="text-sm {{ $performance['budget_adherence'] >= 90 ? 'text-green-600' : 'text-yellow-600' }}">
                                Meta: 90%
                            </span>
                            @if($performance['budget_adherence'] >= 90)
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 {{ $performance['budget_adherence'] >= 90 ? 'bg-green-100' : 'bg-yellow-100' }} rounded-full">
                        <svg class="w-8 h-8 {{ $performance['budget_adherence'] >= 90 ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Progresso das Metas -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Progresso das Metas</p>
                            <div class="tooltip" title="Média de progresso de todas as metas financeiras">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($performance['goals_progress'], 1) }}%
                        </p>
                        <p class="text-sm text-gray-500">Média geral</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Saldo Total -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Saldo Total</p>
                            <div class="tooltip" title="Soma de todos os seus recursos financeiros">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($totalBalance, 2, ',', '.') }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $totalBalance >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $totalBalance >= 0 ? 'Positivo' : 'Negativo' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-3 {{ $totalBalance >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full">
                        <svg class="w-8 h-8 {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Receitas do Mês -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Receitas ({{ $months[$selectedMonth] }})</p>
                            <div class="tooltip" title="Total de receitas no mês selecionado">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-green-600">
                            R$ {{ number_format($monthlyIncome, 2, ',', '.') }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Entradas
                            </span>
                        </div>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Despesas do Mês -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Despesas ({{ $months[$selectedMonth] }})</p>
                            <div class="tooltip" title="Total de despesas no mês selecionado">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-red-600">
                            R$ {{ number_format($monthlyExpenses, 2, ',', '.') }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Saídas
                            </span>
                        </div>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Economia do Mês -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium text-gray-600">Economia ({{ $months[$selectedMonth] }})</p>
                            <div class="tooltip" title="Diferença entre receitas e despesas no mês">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold {{ $monthlySavings >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($monthlySavings, 2, ',', '.') }}
                        </p>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $monthlySavings >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $monthlySavings >= 0 ? 'Economia' : 'Déficit' }}
                            </span>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Goals and Budgets -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Active Financial Goals -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-lg font-semibold text-gray-900">Objetivos Financeiros</h3>
                        <div class="tooltip" title="Seus objetivos financeiros ativos e seu progresso">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('financial-goals.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                        Ver Todos
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($activeGoals as $goal)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-200 transition-colors duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $goal->name }}</h4>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <p class="text-sm text-gray-500">Meta: R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</p>
                                        <span class="text-sm text-gray-500">•</span>
                                        <p class="text-sm text-gray-500">{{ $goal->days_remaining }} dias restantes</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $goal->progress_percentage >= 90 ? 'bg-green-100 text-green-800' : 
                                       ($goal->progress_percentage >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $goal->progress_percentage }}%
                                </span>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Progresso</span>
                                    <span>R$ {{ number_format($goal->current_amount, 2, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-500
                                        {{ $goal->progress_percentage >= 90 ? 'bg-green-500' : 
                                           ($goal->progress_percentage >= 50 ? 'bg-yellow-500' : 'bg-blue-500') }}"
                                        style="width: {{ $goal->progress_percentage }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sem objetivos ativos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comece criando um novo objetivo financeiro</p>
                            <div class="mt-6">
                                <a href="{{ route('financial-goals.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Criar Objetivo
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Current Month Budgets -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-lg font-semibold text-gray-900">Orçamentos do Mês</h3>
                        <div class="tooltip" title="Seus orçamentos mensais e o progresso dos gastos">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <a href="{{ route('budgets.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                        Ver Todos
                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($budgets as $budget)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-200 transition-colors duration-200">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $budget->category->name }}</h4>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <p class="text-sm text-gray-500">
                                            R$ {{ number_format($budget->spent, 2, ',', '.') }} 
                                            de R$ {{ number_format($budget->amount, 2, ',', '.') }}
                                        </p>
                                        <span class="text-sm text-gray-500">•</span>
                                        <p class="text-sm text-gray-500">
                                            Restante: R$ {{ number_format($budget->remaining, 2, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full 
                                    {{ $budget->percentage <= 80 ? 'bg-green-100 text-green-800' : 
                                       ($budget->percentage <= 100 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ number_format($budget->percentage, 1) }}%
                                </span>
                            </div>
                            <div class="mt-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Progresso</span>
                                    <span class="{{ $budget->percentage > 100 ? 'text-red-600 font-medium' : '' }}">
                                        {{ $budget->percentage > 100 ? 'Limite excedido' : 'Dentro do limite' }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-2 rounded-full transition-all duration-500
                                        {{ $budget->percentage <= 80 ? 'bg-green-500' : 
                                           ($budget->percentage <= 100 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                        style="width: {{ min($budget->percentage, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2M3 16V6a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sem orçamentos definidos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comece definindo um orçamento mensal</p>
                            <div class="mt-6">
                                <a href="{{ route('budgets.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Criar Orçamento
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Despesas por Categoria -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-lg font-semibold text-gray-900">Despesas por Categoria</h3>
                        <div class="tooltip" title="Distribuição das suas despesas por categoria no mês">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">{{ $months[$selectedMonth] }}</span>
                </div>
                <div class="h-80">
                    @if($expensesByCategory->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-900">Sem despesas registradas</p>
                            <p class="mt-1 text-sm text-gray-500">Nenhuma despesa foi registrada neste mês</p>
                        </div>
                    @else
                        <canvas id="expensesByCategory"></canvas>
                    @endif
                </div>
            </div>

            <!-- Fluxo de Caixa -->
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-lg font-semibold text-gray-900">Fluxo de Caixa</h3>
                        <div class="tooltip" title="Evolução das suas receitas e despesas nos últimos 6 meses">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <span class="text-sm text-gray-500">Últimos 6 meses</span>
                </div>
                <div class="h-80">
                    <canvas id="cashFlow"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-2">
                        <h3 class="text-lg font-semibold text-gray-900">Transações Recentes</h3>
                        <div class="tooltip" title="Suas transações mais recentes no mês selecionado">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-500">{{ $months[$selectedMonth] }}</span>
                        <a href="{{ route('transactions.index') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                            Ver Todas
                            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Descrição</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Categoria</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentTransactions as $transaction)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->description }}</div>
                                    @if($transaction->notes)
                                        <div class="text-sm text-gray-500">{{ $transaction->notes }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $transaction->category->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sem transações</h3>
                                    <p class="mt-1 text-sm text-gray-500">Nenhuma transação registrada neste mês</p>
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
            // Configuração comum para os gráficos
            Chart.defaults.font.family = '"Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
            Chart.defaults.font.size = 13;
            Chart.defaults.color = '#6B7280';

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
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 12,
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `R$ ${value.toLocaleString('pt-BR', {minimumFractionDigits: 2})} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
            @endif

            // Gráfico de Fluxo de Caixa
            const cashFlowCtx = document.getElementById('cashFlow').getContext('2d');
            new Chart(cashFlowCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($cashFlow->pluck('date')) !!},
                    datasets: [
                        {
                            label: 'Receitas',
                            data: {!! json_encode($cashFlow->pluck('income')) !!},
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
                        },
                        {
                            label: 'Despesas',
                            data: {!! json_encode($cashFlow->pluck('expenses')) !!},
                            borderColor: '#EF4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 12,
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    return `${context.dataset.label}: R$ ${value.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Finanças Pessoais') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <nav class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0">
            <div class="h-full px-3 py-4 overflow-y-auto bg-white border-r">
                <div class="mb-8 pl-2.5">
                    <span class="text-xl font-semibold">Finanças Pessoais</span>
                </div>
                <ul class="space-y-2 font-medium">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-home w-6"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('transactions.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-exchange-alt w-6"></i>
                            <span>Transações</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-tags w-6"></i>
                            <span>Categorias</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('financial-goals.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-chart-line w-6"></i>
                            <span>Objetivos Financeiros</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('accounts.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-wallet w-6"></i>
                            <span>Contas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('credit-cards.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-credit-card w-6"></i>
                            <span>Cartões de Crédito</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('budgets.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-chart-pie w-6"></i>
                            <span>Orçamentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('reports.index') }}" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-gray-100">
                            <i class="fas fa-chart-line w-6"></i>
                            <span>Relatórios</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="sm:ml-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="px-4 py-6 mx-auto">
                    <div class="flex justify-between items-center">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            @yield('header')
                        </h2>
                        <div class="flex items-center">
                            <!-- User Dropdown -->
                            <div class="relative">
                                <button class="flex items-center space-x-2 text-gray-700">
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-6">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html> 
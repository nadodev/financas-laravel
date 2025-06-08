<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg transform transition-transform duration-200 ease-in-out" id="sidebar">
            <div class="flex items-center justify-center h-16 border-b border-gray-200">
                <h1 class="text-xl font-semibold text-gray-800">{{ config('app.name', 'Laravel') }}</h1>
            </div>
            <nav class="mt-5 px-2">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('accounts.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('accounts.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-wallet mr-3 {{ request()->routeIs('accounts.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Contas
                </a>

                <a href="{{ route('credit-cards.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('credit-cards.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-credit-card mr-3 {{ request()->routeIs('credit-cards.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Cartões de Crédito
                </a>

                <a href="{{ route('transactions.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('transactions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-exchange-alt mr-3 {{ request()->routeIs('transactions.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Transações
                </a>

                <a href="{{ route('categories.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('categories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-tags mr-3 {{ request()->routeIs('categories.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Categorias
                </a>

                <a href="{{ route('budgets.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('budgets.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-chart-pie mr-3 {{ request()->routeIs('budgets.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Orçamentos
                </a>

                <a href="{{ route('financial-goals.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('financial-goals.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-bullseye mr-3 {{ request()->routeIs('financial-goals.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Objetivos Financeiros
                </a>

                <a href="{{ route('performance.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('performance.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-chart-line mr-3 {{ request()->routeIs('performance.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Meu Desempenho
                </a>

                <a href="{{ route('reports.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('reports.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Relatórios
                </a>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="{{ route('profile.edit') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-user mr-3 {{ request()->routeIs('profile.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="pl-64">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="ml-4 text-lg font-medium text-gray-900">
                            @yield('header', 'Dashboard')
                        </h2>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button type="button" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none" id="userMenuButton">
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-6">
                @if (session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.pl-64');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.remove('pl-0');
                mainContent.classList.add('pl-64');
            } else {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('pl-64');
                mainContent.classList.add('pl-0');
            }
        });

        // Responsive Sidebar
        function handleResize() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.querySelector('.pl-64');
            
            if (window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
                mainContent.classList.remove('pl-64');
                mainContent.classList.add('pl-0');
            } else {
                sidebar.classList.remove('-translate-x-full');
                mainContent.classList.remove('pl-0');
                mainContent.classList.add('pl-64');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize();
    </script>
</body>
</html> 
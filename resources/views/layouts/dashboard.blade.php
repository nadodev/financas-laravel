<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>App Financeiro</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased" 
    x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        handleResize() {
            if (window.innerWidth >= 1024) {
                this.sidebarOpen = true;
            }
        }
    }" 
    x-init="window.addEventListener('resize', handleResize)"
>
    <div class="min-h-screen bg-gray-100">
        <!-- Mobile Overlay -->
        <div
            x-show="sidebarOpen"
            class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity lg:hidden"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
        ></div>

        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div 
            class="transition-all duration-300" 
            :class="{
                'lg:pl-[17rem]': sidebarOpen,
                'lg:pl-0': !sidebarOpen
            }"
        >
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-4 py-4 sm:px-6 lg:px-8">
                    <div class="flex items-center">
                        <button type="button" class="text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600" @click="sidebarOpen = !sidebarOpen">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="ml-4 text-lg font-medium text-gray-900">
                            @yield('header', 'Dashboard')
                        </h2>
                    </div>
                    <div class="flex items-center">
                        <div class="relative" x-data="{ open: false }">
                            <button type="button" 
                                    @click="open = !open" 
                                    @keydown.escape.window="open = false"
                                    @click.away="open = false"
                                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none" 
                                    id="userMenuButton"
                                    aria-expanded="false"
                                    aria-haspopup="true">
                                <span>{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2 transition-transform duration-200" :class="{ 'transform rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu"
                                 aria-orientation="vertical"
                                 aria-labelledby="userMenuButton"
                                 tabindex="-1">
                                
                                <a href="{{ route('profile.edit') }}" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                   role="menuitem" 
                                   tabindex="-1">
                                    <i class="fas fa-user mr-2"></i>
                                    Perfil
                                </a>
                                
                                <a href="{{ route('profile.edit') }}#settings" 
                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" 
                                   role="menuitem" 
                                   tabindex="-1">
                                    <i class="fas fa-cog mr-2"></i>
                                    Configurações
                                </a>
                                
                                <div class="border-t border-gray-100"></div>
                                
                                <!-- Botão de Logout -->
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            role="menuitem"
                                            tabindex="-1">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="py-6 px-4">
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
</body>
</html> 
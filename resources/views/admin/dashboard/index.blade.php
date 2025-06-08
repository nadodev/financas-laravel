<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Administrativo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-admin.stat-card title="Total de Usuários" :value="$totalUsers" />
                <x-admin.stat-card title="Total de Transações" :value="$totalTransactions" />
                <x-admin.stat-card title="Total de Planos" :value="$totalPlans" />
            </div>

            <!-- Usuários Recentes e Transações -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Usuários Recentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Usuários Recentes</h3>
                        <div class="space-y-4">
                            @foreach($recentUsers as $user)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                    <div class="text-sm">
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                            {{ $user->plan->name }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todos os usuários →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Transações Recentes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Transações Recentes</h3>
                        <div class="space-y-4">
                            @foreach($recentTransactions as $transaction)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">{{ $transaction->description }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->user->name }}</div>
                                    </div>
                                    <div class="text-sm">
                                        <span class="@if($transaction->type === 'income') text-green-600 @else text-red-600 @endif font-medium">
                                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.transactions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver todas as transações →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribuição de Planos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Distribuição de Planos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach($planDistribution as $plan)
                            <x-admin.stat-card 
                                :title="$plan->plan->name"
                                :value="$plan->total"
                                description="usuários"
                            />
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.plans.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Gerenciar planos →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
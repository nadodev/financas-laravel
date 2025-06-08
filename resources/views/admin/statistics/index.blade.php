<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Estatísticas do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estatísticas Mensais -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estatísticas Mensais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <x-admin.stat-card 
                            title="Novos Usuários"
                            :value="$monthlyStats['newUsers']"
                            description="este mês"
                        />
                        <x-admin.stat-card 
                            title="Novas Transações"
                            :value="$monthlyStats['newTransactions']"
                            description="este mês"
                        />
                        <x-admin.stat-card 
                            title="Volume Total"
                            :value="'R$ ' . number_format($monthlyStats['totalVolume'], 2, ',', '.')"
                            description="este mês"
                        />
                        <x-admin.stat-card 
                            title="Média por Usuário"
                            :value="'R$ ' . number_format($monthlyStats['averagePerUser'], 2, ',', '.')"
                            description="este mês"
                        />
                    </div>
                </div>
            </div>

            <!-- Crescimento de Usuários -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Crescimento de Usuários</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <x-admin.stat-card 
                            title="Total de Usuários"
                            :value="$userGrowth['total']"
                        />
                        <x-admin.stat-card 
                            title="Crescimento Mensal"
                            :value="$userGrowth['monthlyGrowth'] . '%'"
                            description="em relação ao mês anterior"
                        />
                        <x-admin.stat-card 
                            title="Taxa de Retenção"
                            :value="$userGrowth['retentionRate'] . '%'"
                            description="últimos 30 dias"
                        />
                    </div>
                </div>
            </div>

            <!-- Estatísticas por Plano -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Estatísticas por Plano</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @foreach($planStats as $stat)
                            <div class="bg-white rounded-lg border p-4">
                                <div class="text-lg font-semibold text-gray-800">{{ $stat['name'] }}</div>
                                <div class="mt-2 space-y-2">
                                    <div>
                                        <div class="text-sm text-gray-500">Usuários Ativos</div>
                                        <div class="text-2xl font-bold text-blue-600">{{ $stat['activeUsers'] }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Receita Mensal</div>
                                        <div class="text-lg font-semibold text-green-600">
                                            R$ {{ number_format($stat['monthlyRevenue'], 2, ',', '.') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Média de Transações</div>
                                        <div class="text-lg">{{ $stat['avgTransactions'] }}/mês</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
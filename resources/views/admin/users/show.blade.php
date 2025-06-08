<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes do Usuário') }}
            </h2>
            <x-admin.action-button href="{{ route('admin.users.edit', $user) }}" color="blue">
                Editar Usuário
            </x-admin.action-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações do Usuário -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações Pessoais</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nome</div>
                            <div class="mt-1">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Email</div>
                            <div class="mt-1">{{ $user->email }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Função</div>
                            <div class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $user->role === 'admin' ? 'Administrador' : 'Usuário' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Data de Cadastro</div>
                            <div class="mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plano -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Plano Atual</h3>
                    @if($user->plan)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Nome do Plano</div>
                                <div class="mt-1">{{ $user->plan->name }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Preço</div>
                                <div class="mt-1">R$ {{ number_format($user->plan->price, 2, ',', '.') }}/mês</div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Status</div>
                                <div class="mt-1">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $user->plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->plan->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="text-sm font-medium text-gray-500 mb-2">Recursos do Plano:</div>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($user->plan->features as $feature)
                                    <li class="text-sm text-gray-600">{{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p class="text-gray-500">Este usuário não possui um plano associado.</p>
                    @endif
                </div>
            </div>

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <x-admin.stat-card 
                    title="Total de Contas"
                    :value="$user->accounts()->count()"
                    :description="$user->getRemainingAccounts() === 'Ilimitado' ? 'Ilimitado' : 'Restantes: ' . $user->getRemainingAccounts()"
                />
                <x-admin.stat-card 
                    title="Transações este Mês"
                    :value="$user->transactions()->whereMonth('created_at', now()->month)->count()"
                    :description="$user->getRemainingTransactions() === 'Ilimitado' ? 'Ilimitado' : 'Restantes: ' . $user->getRemainingTransactions()"
                />
                <x-admin.stat-card 
                    title="Objetivos Financeiros"
                    :value="$user->financialGoals()->count()"
                />
            </div>

            <!-- Transações Recentes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Transações Recentes</h3>
                    <x-admin.transactions-table :transactions="$user->transactions()->latest()->paginate(10)" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
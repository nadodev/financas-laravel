<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Transação') }}
            </h2>
            <x-admin.action-button href="{{ route('admin.transactions.index') }}" color="gray">
                Voltar
            </x-admin.action-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informações da Transação -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações da Transação</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Descrição</div>
                            <div class="mt-1">{{ $transaction->description }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Valor</div>
                            <div class="mt-1 {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Tipo</div>
                            <div class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transaction->type === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Data</div>
                            <div class="mt-1">{{ $transaction->date->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Categoria</div>
                            <div class="mt-1">{{ $transaction->category->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Conta</div>
                            <div class="mt-1">{{ $transaction->account->name }}</div>
                        </div>
                        @if($transaction->credit_card_invoice_id)
                            <div>
                                <div class="text-sm font-medium text-gray-500">Fatura do Cartão</div>
                                <div class="mt-1">
                                    {{ $transaction->creditCardInvoice->credit_card->name }} -
                                    {{ $transaction->creditCardInvoice->due_date->format('d/m/Y') }}
                                </div>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-medium text-gray-500">Data de Criação</div>
                            <div class="mt-1">{{ $transaction->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Última Atualização</div>
                            <div class="mt-1">{{ $transaction->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Usuário -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informações do Usuário</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm font-medium text-gray-500">Nome</div>
                            <div class="mt-1">{{ $transaction->user->name }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Email</div>
                            <div class="mt-1">{{ $transaction->user->email }}</div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Plano</div>
                            <div class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $transaction->user->plan->name }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500">Data de Cadastro</div>
                            <div class="mt-1">{{ $transaction->user->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <x-admin.action-button href="{{ route('admin.users.show', $transaction->user) }}" color="blue">
                            Ver Perfil do Usuário
                        </x-admin.action-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
@extends('layouts.dashboard')
@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
<div class="" x-data="{ showModal: false }">
    <div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Modal -->
        <div x-show="showModal" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full md:max-w-2xl"
                     @click.away="showModal = false">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Nova Conta</h3>
                                <form action="{{ route('accounts.store') }}" method="POST" class="space-y-4">
                                    @csrf
                                    
                                    <!-- Nome -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Nome da Conta</label>
                                        <input type="text" name="name" id="name" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            required>
                                    </div>

                                    <!-- Tipo -->
                                    <div>
                                        <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Conta</label>
                                        <select name="type" id="type" 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            required>
                                            <option value="">Selecione um tipo</option>
                                            <option value="checking">Conta Corrente</option>
                                            <option value="savings">Conta Poupança</option>
                                            <option value="investment">Conta Investimento</option>
                                            <option value="wallet">Carteira</option>
                                        </select>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Banco -->
                                        <div>
                                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Banco</label>
                                            <input type="text" name="bank_name" id="bank_name" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- Agência -->
                                        <div>
                                            <label for="agency" class="block text-sm font-medium text-gray-700">Agência</label>
                                            <input type="text" name="agency" id="agency" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Número da Conta -->
                                        <div>
                                            <label for="account_number" class="block text-sm font-medium text-gray-700">Número da Conta</label>
                                            <input type="text" name="account_number" id="account_number" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>

                                        <!-- Saldo Inicial -->
                                        <div>
                                            <label for="balance" class="block text-sm font-medium text-gray-700">Saldo Inicial</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">R$</span>
                                                </div>
                                                <input type="number" 
                                                    name="balance" 
                                                    id="balance" 
                                                    step="0.01" 
                                                    value="0.00"
                                                    class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Observações -->
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                    </div>

                                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Criar Conta
                                        </button>
                                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-hidden shadow-sm sm:rounded-lg">
          
            <div class="text-gray-900 ">
                <h2 class="text-2xl font-bold mb-4">Minhas Contas</h2>
                @if(!auth()->user()->checkAccountLimit())
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Você atingiu o limite de criação de novas contas para seu plano atual. 
                                    <a href="{{ route('plans.index') }}" class="font-medium underline hover:text-yellow-800">
                                        Conheça nossos planos disponíveis
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
            @endif
                <div class="flex justify-between items-center mb-6">
                  
                    <div class="flex items-center space-x-4 bg-white w-full rounded-lg p-4 justify-between shadow-sm">
                        <button 
                        @click="showModal = true"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                        {{ !auth()->user()->checkAccountLimit() ? 'disabled' : '' }}
                        title="{{ !auth()->user()->checkAccountLimit() ? 'Limite de contas atingido' : 'Criar nova conta' }}"
                    >
                        Nova Conta
                    </button>
                        <p class="text-sm text-gray-600">
                            @php
                                $remaining = auth()->user()->getRemainingAccounts();
                                $limit = auth()->user()->plan->slug === 'basic' ? 1 : 
                                        (auth()->user()->plan->slug === 'essential' ? 3 : 'Ilimitado');
                            @endphp
                            Contas disponíveis para criação: 
                            <span class="font-semibold">
                                {{ is_numeric($remaining) ? $remaining : $remaining }}
                            </span>
                            <span class="text-gray-400">(Limite do plano: {{ $limit }})</span>
                        </p>
                     
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Left side: Accounts List -->
                    <div class="lg:w-2/3">
                @if($accounts->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">Você ainda não tem nenhuma conta cadastrada.</p>
                                <button 
                                    @click="showModal = true"
                                    class="text-blue-500 hover:text-blue-700 mt-2 inline-block disabled:opacity-50 disabled:cursor-not-allowed"
                                    {{ !auth()->user()->checkAccountLimit() ? 'disabled' : '' }}
                                >
                            Clique aqui para criar sua primeira conta
                                </button>
                    </div>
                @else
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($accounts as $account)
                                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    <a href="{{ route('accounts.show', $account) }}" class="hover:text-blue-600">
                                                {{ $account->name }}
                                            </a>
                                                </h3>
                                                <p class="text-sm text-gray-600">{{ $account->type_name }}</p>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('accounts.edit', $account) }}" 
                                                   class="text-gray-400 hover:text-indigo-600" 
                                                   title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-gray-400 hover:text-red-600"
                                                            title="Excluir"
                                                            onclick="return confirm('Tem certeza que deseja excluir esta conta?')">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            @if($account->bank)
                                            <div class="flex items-center text-gray-600">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                <span class="text-sm">{{ $account->bank_name ?: 'Banco não informado' }}</span>
                                            </div>
                                            @endif
                                            
                                            @if($account->agency || $account->account_number)
                                            <div class="flex items-center text-gray-600">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                </svg>
                                                <span class="text-sm">
                                                    {{ $account->agency ? 'Ag. ' . $account->agency : '' }}
                                                    {{ $account->agency && $account->account_number ? ' | ' : '' }}
                                                    {{ $account->account_number ? 'Conta ' . $account->account_number : '' }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="mt-4 pt-4 border-t border-gray-100">
                                            <div class="flex justify-between items-center">
                                                <span class="text-sm font-medium text-gray-500">Saldo</span>
                                                <span class="text-lg font-semibold @if($account->balance < 0) text-red-600 @else text-green-600 @endif">
                                                    R$ {{ number_format($account->balance, 2, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-6">
                                {{ $accounts->links() }}
                            </div>
                        @endif
                    </div>

                    <!-- Right side: Financial Cards -->
                    <div class="lg:w-1/3 space-y-4">
                        <!-- Current Balance Card -->
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Saldo Atual</h3>
                            <p class="text-3xl font-bold text-green-600">R$ {{ number_format($totalBalance, 2, ',', '.') }}</p>
                            <p class="text-sm text-gray-500 mt-2">Somatório de todas as contas</p>
                        </div>

                        <!-- Expected Balance Card -->
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Saldo Previsto</h3>
                            <p class="text-3xl font-bold text-blue-600">R$ {{ number_format($totalBalance + ($expectedIncome ?? 0) - ($expectedExpenses ?? 0), 2, ',', '.') }}</p>
                            <p class="text-sm text-gray-500 mt-2">Considerando receitas e despesas futuras</p>
                        </div>

                        <!-- Account Summary Card -->
                        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Resumo das Contas</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total de Contas:</span>
                                    <span class="font-semibold">{{ $totalAccounts }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Contas Negativas:</span>
                                    <span class="font-semibold text-red-600">{{ $negativeAccounts }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Contas Positivas:</span>
                                    <span class="font-semibold text-green-600">{{ $totalAccounts - $negativeAccounts }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection 
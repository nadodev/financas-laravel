<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <form action="{{ route('accounts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Tipo de Conta -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Conta</label>
                <select name="type" id="type" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Todos</option>
                    <option value="checking" {{ request('type') === 'checking' ? 'selected' : '' }}>Conta Corrente</option>
                    <option value="savings" {{ request('type') === 'savings' ? 'selected' : '' }}>Conta Poupança</option>
                    <option value="investment" {{ request('type') === 'investment' ? 'selected' : '' }}>Conta Investimento</option>
                    <option value="wallet" {{ request('type') === 'wallet' ? 'selected' : '' }}>Carteira</option>
                </select>
            </div>

            <!-- Banco -->
            <div>
                <label for="bank" class="block text-sm font-medium text-gray-700">Banco</label>
                <input type="text" name="bank" id="bank" 
                    value="{{ request('bank') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- Saldo -->
            <div>
                <label for="balance" class="block text-sm font-medium text-gray-700">Saldo</label>
                <select name="balance" id="balance" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Todos</option>
                    <option value="positive" {{ request('balance') === 'positive' ? 'selected' : '' }}>Saldo Positivo</option>
                    <option value="negative" {{ request('balance') === 'negative' ? 'selected' : '' }}>Saldo Negativo</option>
                </select>
            </div>

            <!-- Botões -->
            <div class="flex items-end space-x-3">
                <button type="submit" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtrar
                </button>
                <a href="{{ route('accounts.index') }}" 
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Limpar
                </a>
            </div>
        </form>
    </div>
</div> 
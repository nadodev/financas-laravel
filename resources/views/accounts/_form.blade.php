@csrf
@if(isset($account))
    @method('PUT')
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Nome -->
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nome da Conta</label>
        <input type="text" 
            name="name" 
            id="name" 
            value="{{ old('name', $account->name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            required>
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Tipo -->
    <div>
        <label for="type" class="block text-sm font-medium text-gray-700">Tipo de Conta</label>
        <select name="type" 
            id="type" 
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            required>
            <option value="">Selecione um tipo</option>
            <option value="checking" {{ old('type', $account->type ?? '') === 'checking' ? 'selected' : '' }}>Conta Corrente</option>
            <option value="savings" {{ old('type', $account->type ?? '') === 'savings' ? 'selected' : '' }}>Conta Poupança</option>
            <option value="investment" {{ old('type', $account->type ?? '') === 'investment' ? 'selected' : '' }}>Conta Investimento</option>
            <option value="wallet" {{ old('type', $account->type ?? '') === 'wallet' ? 'selected' : '' }}>Carteira</option>
        </select>
        @error('type')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Banco -->
    <div>
        <label for="bank_name" class="block text-sm font-medium text-gray-700">Banco</label>
        <input type="text" 
            name="bank_name" 
            id="bank_name" 
            value="{{ old('bank_name', $account->bank_name ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('bank_name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Agência -->
    <div>
        <label for="agency" class="block text-sm font-medium text-gray-700">Agência</label>
        <input type="text" 
            name="agency" 
            id="agency" 
            value="{{ old('agency', $account->agency ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('agency')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Número da Conta -->
    <div>
        <label for="account_number" class="block text-sm font-medium text-gray-700">Número da Conta</label>
        <input type="text" 
            name="account_number" 
            id="account_number" 
            value="{{ old('account_number', $account->account_number ?? '') }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        @error('account_number')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
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
                value="{{ old('balance',$account->balance ?? '0.00') }}"
                class="pl-8 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        @if(!isset($account))
            <p class="mt-1 text-xs text-gray-500">O saldo inicial só pode ser definido na criação da conta.</p>
        @endif
        @error('balance')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Observações -->
    <div class="md:col-span-2">
        <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
        <textarea name="notes" 
            id="notes" 
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes', $account->notes ?? '') }}</textarea>
        @error('notes')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex justify-end space-x-3 mt-6">
    <a href="{{ route('accounts.index') }}" 
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        Cancelar
    </a>
    <button type="submit" 
        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ isset($account) ? 'Atualizar' : 'Criar' }}
    </button>
</div> 
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="col-span-2">
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nome do Objetivo</label>
        <input type="text" 
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('name') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
               id="name" 
               name="name" 
               value="{{ old('name', $financialGoal->name ?? '') }}" 
               required>
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="col-span-2">
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
        <textarea class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('description') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                  id="description" 
                  name="description" 
                  rows="3">{{ old('description', $financialGoal->description ?? '') }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="target_amount" class="block text-sm font-medium text-gray-700 mb-1">Valor da Meta</label>
        <div class="mt-1 relative rounded-lg shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">R$</span>
            </div>
            <input type="number" 
                   step="0.01" 
                   min="0" 
                   class="pl-8 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('target_amount') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                   id="target_amount" 
                   name="target_amount" 
                   value="{{ old('target_amount', $financialGoal->target_amount ?? '') }}" 
                   required>
        </div>
        @error('target_amount')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="current_amount" class="block text-sm font-medium text-gray-700 mb-1">Valor Atual</label>
        <div class="mt-1 relative rounded-lg shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">R$</span>
            </div>
            <input type="number" 
                   step="0.01" 
                   min="0" 
                   class="pl-8 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('current_amount') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                   id="current_amount" 
                   name="current_amount" 
                   value="{{ old('current_amount', $financialGoal->current_amount ?? '0') }}">
        </div>
        @error('current_amount')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="target_date" class="block text-sm font-medium text-gray-700 mb-1">Data Limite</label>
        <input type="date" 
               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('target_date') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
               id="target_date" 
               name="target_date" 
               value="{{ old('target_date', isset($financialGoal) ? $financialGoal->target_date->format('Y-m-d') : '') }}" 
               required>
        @error('target_date')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="account_id" class="block text-sm font-medium text-gray-700 mb-1">Conta Vinculada (Opcional)</label>
        <select class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('account_id') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                id="account_id" 
                name="account_id">
            <option value="">Selecione uma conta...</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" 
                    {{ old('account_id', $financialGoal->account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ $account->name }}
                </option>
            @endforeach
        </select>
        @error('account_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if(isset($financialGoal))
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm transition-colors duration-200 @error('status') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                    id="status" 
                    name="status" 
                    required>
                @foreach($statuses as $key => $value)
                    <option value="{{ $key }}" 
                        {{ old('status', $financialGoal->status) == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @endif
</div>
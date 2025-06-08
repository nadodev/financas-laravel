@props(['action'])

<form method="GET" action="{{ $action }}">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Todos</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Receita</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Despesa</option>
            </select>
        </div>

        <div>
            <label for="date_from" class="block text-sm font-medium text-gray-700">Data Inicial</label>
            <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="date_to" class="block text-sm font-medium text-gray-700">Data Final</label>
            <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="amount_min" class="block text-sm font-medium text-gray-700">Valor Mínimo</label>
            <input type="number" step="0.01" name="amount_min" id="amount_min" value="{{ request('amount_min') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div>
            <label for="amount_max" class="block text-sm font-medium text-gray-700">Valor Máximo</label>
            <input type="number" step="0.01" name="amount_max" id="amount_max" value="{{ request('amount_max') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>

        <div class="flex items-end">
            <x-admin.action-button type="submit" color="blue">
                Filtrar
            </x-admin.action-button>
        </div>
    </div>
</form> 
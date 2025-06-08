<!-- Right side: Financial Summary Cards -->
<div class="lg:w-1/3 space-y-4">
    <!-- Saldo Atual -->
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Saldo Atual</h3>
        <p class="text-3xl font-bold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
            R$ {{ number_format($currentBalance, 2, ',', '.') }}
        </p>
        <p class="text-sm text-gray-500 mt-2">Saldo total em todas as contas</p>
    </div>

    <!-- Receitas do Mês -->
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Receitas do Mês</h3>
        <p class="text-3xl font-bold text-green-600">
            R$ {{ number_format($income, 2, ',', '.') }}
        </p>
        <p class="text-sm text-gray-500 mt-2">Total de receitas este mês</p>
    </div>

    <!-- Despesas do Mês -->
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Despesas do Mês</h3>
        <p class="text-3xl font-bold text-red-600">
            R$ {{ number_format($expenses, 2, ',', '.') }}
        </p>
        <p class="text-sm text-gray-500 mt-2">Total de despesas este mês</p>
    </div>

    <!-- Balanço Mensal -->
    <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Balanço Mensal</h3>
        <p class="text-3xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
            R$ {{ number_format($balance, 2, ',', '.') }}
        </p>
        <p class="text-sm text-gray-500 mt-2">Receitas - Despesas do mês</p>
    </div>
</div> 
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Resumo das Contas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-sm font-medium text-gray-500">Saldo Total</dt>
                <dd class="mt-1 text-2xl font-semibold {{ $totalBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    R$ {{ number_format($totalBalance, 2, ',', '.') }}
                </dd>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-sm font-medium text-gray-500">Total de Contas</dt>
                <dd class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ $totalAccounts }}
                </dd>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <dt class="text-sm font-medium text-gray-500">Contas Negativas</dt>
                <dd class="mt-1 text-2xl font-semibold text-red-600">
                    {{ $negativeAccounts }}
                </dd>
            </div>
        </div>
    </div>
</div> 
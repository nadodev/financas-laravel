<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transações do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <x-admin.transaction-filters :action="route('admin.transactions.index')" />
                </div>
            </div>

            <!-- Lista de Transações -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <x-admin.transactions-table :transactions="$transactions" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
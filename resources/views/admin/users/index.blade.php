<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciar Usuários') }}
            </h2>
            <x-admin.action-button href="{{ route('admin.users.create') }}" color="blue">
                Novo Usuário
            </x-admin.action-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <x-admin.alert type="success" :message="session('success')" />
            @endif

            @if(session('error'))
                <x-admin.alert type="error" :message="session('error')" />
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <x-admin.users-table :users="$users" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
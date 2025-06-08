<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <x-admin.user-form 
                        :user="$user"
                        :action="route('admin.users.update', $user)"
                        :plans="$plans"
                        method="PUT"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 
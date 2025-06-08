<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gerenciar Planos') }}
            </h2>
            <x-admin.action-button href="{{ route('admin.plans.create') }}" color="blue">
                Novo Plano
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($plans as $plan)
                    <x-admin.plan-card :plan="$plan" />
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout> 
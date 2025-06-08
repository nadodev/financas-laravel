@props(['plan'])

<div class="bg-white rounded-lg border p-6">
    <div class="flex justify-between items-start mb-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
            <p class="text-2xl font-bold text-blue-600 mt-2">
                R$ {{ number_format($plan->price, 2, ',', '.') }}
                <span class="text-sm text-gray-500">/mês</span>
            </p>
        </div>
        <div class="flex items-center">
            <form action="{{ route('admin.plans.toggle-status', $plan) }}" method="POST" class="mr-2">
                @csrf
                <button type="submit" class="text-sm {{ $plan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded">
                    {{ $plan->is_active ? 'Ativo' : 'Inativo' }}
                </button>
            </form>
        </div>
    </div>

    <p class="text-gray-600 mb-4">{{ $plan->description }}</p>

    <div class="mb-4">
        <div class="text-sm font-medium text-gray-500 mb-2">Recursos:</div>
        <ul class="space-y-2">
            @foreach($plan->features as $feature)
                <li class="flex items-center text-sm text-gray-600">
                    <svg class="h-4 w-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $feature }}
                </li>
            @endforeach
        </ul>
    </div>

    <div class="border-t pt-4">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                {{ $plan->users_count }} usuários
            </div>
            <div class="flex space-x-2">
                <x-admin.action-button href="{{ route('admin.plans.edit', $plan) }}" color="blue">
                    Editar
                </x-admin.action-button>
                @if($plan->users_count === 0)
                    <form action="{{ route('admin.plans.destroy', $plan) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <x-admin.action-button type="submit" color="red" onclick="return confirm('Tem certeza que deseja excluir este plano?')">
                            Excluir
                        </x-admin.action-button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div> 
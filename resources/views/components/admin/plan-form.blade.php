@props(['plan' => null, 'action', 'method' => 'POST'])

<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nome do Plano</label>
            <input type="text" name="name" id="name" value="{{ old('name', $plan?->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Preço Mensal (R$)</label>
            <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $plan?->price) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('price')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $plan?->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Recursos do Plano</label>
            <div id="features-container" class="space-y-2">
                @if($plan && $plan->features)
                    @foreach($plan->features as $index => $feature)
                        <div class="flex items-center space-x-2">
                            <input type="text" name="features[]" value="{{ old('features.'.$index, $feature) }}"
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center space-x-2">
                        <input type="text" name="features[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif
            </div>
            <button type="button" onclick="addFeature()" class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                + Adicionar recurso
            </button>
            @error('features')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('features.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <x-admin.action-button href="{{ route('admin.plans.index') }}" color="gray">
                Cancelar
            </x-admin.action-button>
            <x-admin.action-button type="submit" color="blue">
                {{ $plan ? 'Atualizar Plano' : 'Criar Plano' }}
            </x-admin.action-button>
        </div>
    </div>
</form>

<script>
function addFeature() {
    const container = document.getElementById('features-container');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2';
    div.innerHTML = `
        <input type="text" name="features[]" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        <button type="button" onclick="removeFeature(this)" class="text-red-600 hover:text-red-800">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function removeFeature(button) {
    const container = document.getElementById('features-container');
    if (container.children.length > 1) {
        button.parentElement.remove();
    }
}
</script> 
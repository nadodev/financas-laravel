@props(['user' => null, 'action', 'method' => 'POST', 'plans'])

<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-6">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user?->name) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user?->email) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
            <input type="password" name="password" id="password"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @if($user)
                <p class="mt-1 text-sm text-gray-500">Deixe em branco para manter a senha atual</p>
            @endif
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Senha</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="plan_id" class="block text-sm font-medium text-gray-700">Plano</label>
            <select name="plan_id" id="plan_id"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach($plans as $plan)
                    <option value="{{ $plan->id }}" {{ old('plan_id', $user?->plan_id) == $plan->id ? 'selected' : '' }}>
                        {{ $plan->name }} - R$ {{ number_format($plan->price, 2, ',', '.') }}
                    </option>
                @endforeach
            </select>
            @error('plan_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="block text-sm font-medium text-gray-700">Função</label>
            <select name="role" id="role"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="user" {{ old('role', $user?->role) === 'user' ? 'selected' : '' }}>Usuário</option>
                <option value="admin" {{ old('role', $user?->role) === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <x-admin.action-button href="{{ route('admin.users.index') }}" color="gray">
                Cancelar
            </x-admin.action-button>
            <x-admin.action-button type="submit" color="blue">
                {{ $user ? 'Atualizar Usuário' : 'Criar Usuário' }}
            </x-admin.action-button>
        </div>
    </div>
</form> 
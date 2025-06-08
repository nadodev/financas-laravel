@props(['users'])

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead>
            <tr>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nome
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Email
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Plano
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Data de Cadastro
                </th>
                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Ações
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                            {{ $user->plan->name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $user->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <x-admin.action-button href="{{ route('admin.users.show', $user) }}" color="blue">
                                Detalhes
                            </x-admin.action-button>
                            <x-admin.action-button href="{{ route('admin.users.edit', $user) }}" color="gray">
                                Editar
                            </x-admin.action-button>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <x-admin.action-button type="submit" color="red" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                    Excluir
                                </x-admin.action-button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div> 
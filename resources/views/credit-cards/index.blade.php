@extends('layouts.dashboard')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-6" x-data="{ hideValues: false }">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Cartões de Crédito</h1>
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" x-model="hideValues" class="form-checkbox h-4 w-4 text-blue-600">
                    <span class="ml-2 text-sm text-gray-600">Ocultar Valores</span>
                </label>
                <a href="{{ route('credit-cards.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Novo Cartão
                </a>
            </div>
        </div>

       <div class="bg-white shadow rounded-lg overflow-hidden">
           <table class="min-w-full divide-y divide-gray-200">
               <thead class="bg-gray-50">
                    <tr class="bg-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bandeira</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Limite</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conta</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($creditCards as $card)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $card->name }}</div>
                                <div class="text-sm leading-5 text-gray-500">{{ $card->masked_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ App\Models\CreditCard::$brands[$card->brand] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-sm leading-5 text-gray-900">
                                    <span x-show="!hideValues">R$ {{ number_format($card->credit_limit, 2, ',', '.') }}</span>
                                    <span x-show="hideValues">R$ ●●●●●</span>
                                </div>
                                <div class="text-sm leading-5" :class="{ 'text-green-600': {{ $card->getAvailableLimit() }} > 0, 'text-red-600': {{ $card->getAvailableLimit() }} <= 0 }">
                                    Disponível: 
                                    <span x-show="!hideValues">R$ {{ number_format($card->getAvailableLimit(), 2, ',', '.') }}</span>
                                    <span x-show="hideValues">R$ ●●●●●</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-sm leading-5 text-gray-900">
                                    Fecha dia {{ $card->closing_day }}
                                </div>
                                <div class="text-sm leading-5 text-gray-500">
                                    Vence dia {{ $card->due_day }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="text-sm leading-5 text-gray-900">{{ $card->account->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 font-medium">
                                <a href="{{ route('credit-cards.show', $card) }}" class="text-blue-600 hover:text-blue-900 mr-3">Ver Faturas</a>
                                <a href="{{ route('credit-cards.edit', $card) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                <form action="{{ route('credit-cards.destroy', $card) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este cartão?')">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                Nenhum cartão de crédito cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection 
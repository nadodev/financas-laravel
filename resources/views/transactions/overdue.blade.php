@extends('layouts.dashboard')

@section('content')
<div class="py-6">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Transações Atrasadas</h2>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Nova Transação
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            @if($transactions->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sem transações atrasadas</h3>
                    <p class="mt-1 text-sm text-gray-500">Todas as suas transações estão em dia!</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Vencimento</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dias Atrasados</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Ações</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-medium">
                                        {{ $transaction->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->description }}</div>
                                        @if($transaction->notes)
                                            <div class="text-sm text-gray-500">{{ $transaction->notes }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $transaction->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                        R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                                    @php
                                        $dias = $transaction->date->diffInSeconds(now()) / 86400;
                                        $diasInteiros = floor($dias);
                                        $texto = '';

                                        if ($dias - $diasInteiros >= 0.1 && $dias - $diasInteiros < 1) {
                                            $texto = "quase " . ($diasInteiros + 1) . " dias atrasado";
                                        } else {
                                            $texto = ($diasInteiros + (($dias - $diasInteiros) >= 1e-6 ? 1 : 0)) . " dias atrasado";
                                        }
                                    @endphp
                                    {{ $texto }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:text-blue-900">Editar</a>
                                            <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir esta transação?')">
                                                    Excluir
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
             
            @endif
        </div>
    </div>
</div>
@endsection 
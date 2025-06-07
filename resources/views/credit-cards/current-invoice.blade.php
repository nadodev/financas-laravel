@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Fatura Atual - {{ $creditCard->name }}</h1>
                <p class="text-gray-600">
                    Fechamento: {{ $invoice->closing_date->format('d/m/Y') }} |
                    Vencimento: {{ $invoice->due_date->format('d/m/Y') }}
                </p>
            </div>
            <div class="flex space-x-4">
                <form action="{{ route('credit-cards.close-invoice', $creditCard) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Fechar Fatura
                    </button>
                </form>
                <form action="{{ route('credit-cards.pay-invoice', $creditCard) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Marcar como Paga
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-md rounded my-6">
            <div class="p-6">
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-100 p-4 rounded">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Limite Total</h3>
                        <p class="text-2xl text-gray-900">
                            R$ {{ number_format($creditCard->credit_limit, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Valor da Fatura</h3>
                        <p class="text-2xl {{ $invoice->amount > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            R$ {{ number_format($invoice->amount, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="bg-gray-100 p-4 rounded">
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Limite Disponível</h3>
                        <p class="text-2xl {{ $creditCard->getAvailableLimit() > 0 ? 'text-green-600' : 'text-red-600' }}">
                            R$ {{ number_format($creditCard->getAvailableLimit(), 2, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Lançamentos da Fatura</h2>
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Categoria</th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ $transaction->date->format('d/m/Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 text-gray-900">
                                            {{ $transaction->description }}
                                        </div>
                                        @if ($transaction->notes)
                                            <div class="text-sm leading-5 text-gray-500">
                                                {{ $transaction->notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $transaction->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="text-sm leading-5 {{ $transaction->type === 'expense' ? 'text-red-600' : 'text-green-600' }}">
                                            R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-center text-gray-500">
                                        Nenhum lançamento encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection 
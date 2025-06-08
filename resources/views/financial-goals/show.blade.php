@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    {{-- Atualizar Progresso --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Atualizar Progresso</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('financial-goals.update-progress', $financialGoal) }}" method="POST" class="space-y-4">
                @csrf
                @method('POST')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Valor Adicionado</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" step="0.01" min="0" name="amount" id="amount" required
                                   class="pl-7 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0,00">
                        </div>
                    </div>
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Data</label>
                        <input type="date" name="date" id="date" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                               value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Observações (opcional)</label>
                    <textarea name="notes" id="notes" rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Adicione observações sobre este progresso..."></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                        <i class="fas fa-plus mr-2"></i>
                        Registrar Progresso
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Histórico de Progresso --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Histórico de Progresso</h3>
        </div>
        <div class="p-6">
            @if($financialGoal->progress->isEmpty())
                <p class="text-gray-500 text-center py-4">Nenhum registro de progresso encontrado.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($financialGoal->progress as $progress)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $progress->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        R$ {{ number_format($progress->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $progress->notes ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    {{-- Detalhes do Objetivo --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">{{ $financialGoal->name }}</h2>
        </div>
        <div class="p-6">
            @if($financialGoal->description)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Descrição</h3>
                    <p class="text-gray-900">{{ $financialGoal->description }}</p>
                </div>
            @endif

            <div class="mb-6">
                <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                    <div class="bg-blue-600 h-4 rounded-full flex items-center justify-center text-white text-xs font-semibold transition-all duration-500"
                         style="width: {{ $financialGoal->progress_percentage }}%">
                        {{ $financialGoal->progress_percentage }}%
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Meta</h4>
                    <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($financialGoal->target_amount, 2, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Atual</h4>
                    <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($financialGoal->current_amount, 2, ',', '.') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Falta</h4>
                    <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($financialGoal->remaining_amount, 2, ',', '.') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Data Limite</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $financialGoal->target_date->format('d/m/Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Dias Restantes</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $financialGoal->days_remaining }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Mensal Necessário</h4>
                    <p class="text-lg font-semibold text-gray-900">R$ {{ number_format($financialGoal->monthly_required_amount, 2, ',', '.') }}</p>
                </div>
            </div>

            @if($financialGoal->account)
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Conta Vinculada</h3>
                    <p class="text-gray-900">{{ $financialGoal->account->name }}</p>
                </div>
            @endif

            <div class="flex justify-between items-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($financialGoal->status === 'completed')
                        bg-green-100 text-green-800
                    @elseif($financialGoal->status === 'cancelled')
                        bg-red-100 text-red-800
                    @else
                        bg-blue-100 text-blue-800
                    @endif">
                    <i class="fas fa-circle text-xs mr-2"></i>
                    {{ $financialGoal::$statuses[$financialGoal->status] }}
                </span>

                <div class="space-x-2">
                    <a href="{{ route('financial-goals.edit', $financialGoal) }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition">
                        <i class="fas fa-edit mr-2"></i>
                        Editar
                    </a>
                    <a href="{{ route('financial-goals.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
    {{-- Atualizar Progresso --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Atualizar Progresso</h3>
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
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    Registrar Progresso
                </button>
            </div>
        </form>
    </div>

    {{-- Histórico de Progresso --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Histórico de Progresso</h3>
        
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

    {{-- Detalhes do Objetivo --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $financialGoal->name }}</h2>

        @if($financialGoal->description)
            <div class="mb-6">
                <h3 class="text-gray-600 font-medium mb-1">Descrição</h3>
                <p class="text-gray-800">{{ $financialGoal->description }}</p>
            </div>
        @endif

        <div class="w-full bg-gray-200 rounded-full h-6 mb-6">
            <div class="bg-blue-600 h-6 rounded-full flex items-center justify-center text-white text-sm font-semibold transition-all duration-500"
                 style="width: {{ $financialGoal->progress_percentage }}%">
                {{ $financialGoal->progress_percentage }}%
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center text-gray-800 mb-6">
            <div class="bg-gray-100 rounded p-4">
                <h4 class="text-sm text-gray-500">Meta</h4>
                <p class="text-lg font-semibold">R$ {{ number_format($financialGoal->target_amount, 2, ',', '.') }}</p>
            </div>
            <div class="bg-gray-100 rounded p-4">
                <h4 class="text-sm text-gray-500">Atual</h4>
                <p class="text-lg font-semibold">R$ {{ number_format($financialGoal->current_amount, 2, ',', '.') }}</p>
            </div>
            <div class="bg-gray-100 rounded p-4">
                <h4 class="text-sm text-gray-500">Falta</h4>
                <p class="text-lg font-semibold">R$ {{ number_format($financialGoal->remaining_amount, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center text-gray-800 mb-6">
            <div class="bg-gray-100 rounded p-4">
                <h4 class="text-sm text-gray-500">Data Limite</h4>
                <p class="text-lg font-semibold">{{ $financialGoal->target_date->format('d/m/Y') }}</p>
            </div>
            <div class="bg-gray-100 rounded p-4">
                <h4 class="text-sm text-gray-500">Dias Restantes</h4>
                <p class="text-lg font-semibold">{{ $financialGoal->days_remaining }}</p>
            </div>
            <div class="bg-gray-100 rounded p-4">
                <h4 class="text-sm text-gray-500">Mensal Necessário</h4>
                <p class="text-lg font-semibold">R$ {{ number_format($financialGoal->monthly_required_amount, 2, ',', '.') }}</p>
            </div>
        </div>

        @if($financialGoal->account)
            <div class="mb-6">
                <h3 class="text-gray-600 font-medium">Conta Vinculada</h3>
                <p class="text-gray-800">{{ $financialGoal->account->name }}</p>
            </div>
        @endif

        <div class="flex justify-between items-center">
            <span class="text-sm font-medium px-3 py-1 rounded-full
                @if($financialGoal->status === 'completed')
                    bg-green-100 text-green-800
                @elseif($financialGoal->status === 'cancelled')
                    bg-red-100 text-red-800
                @else
                    bg-blue-100 text-blue-800
                @endif">
                {{ $financialGoal::$statuses[$financialGoal->status] }}
            </span>

            <div class="space-x-2">
                <a href="{{ route('financial-goals.edit', $financialGoal) }}"
                   class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white rounded shadow-sm">
                    Editar
                </a>
                <a href="{{ route('financial-goals.index') }}"
                   class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded shadow-sm">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    {{-- Simulador --}}
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Simulador</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Simular por valor mensal --}}
            <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                <h4 class="text-lg font-medium mb-3">Simular por Valor Mensal</h4>
                <form id="simulateByAmount" class="space-y-4">
                    <div>
                        <label for="monthly_amount" class="block text-sm font-medium text-gray-700">Quanto posso guardar por mês?</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md bg-gray-100 text-gray-500">R$</span>
                            <input type="number" step="0.01" min="0" name="monthly_amount" id="monthly_amount"
                                   required class="flex-1 block w-full rounded-r-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                        Simular
                    </button>
                </form>
                <div id="amountResult" class="hidden mt-4 border-t pt-4 text-gray-700">
                    <p>Guardando <strong>R$ <span id="amountValue"></span></strong> por mês:</p>
                    <ul class="list-disc list-inside">
                        <li>Você alcançará seu objetivo em <strong><span id="monthsToReach"></span> meses</strong></li>
                        <li>Data estimada: <strong><span id="estimatedDate"></span></strong></li>
                    </ul>
                </div>
            </div>

            {{-- Simular por tempo --}}
            <div class="bg-gray-50 p-4 rounded-lg shadow-inner">
                <h4 class="text-lg font-medium mb-3">Simular por Tempo</h4>
                <form id="simulateByTime" class="space-y-4">
                    <div>
                        <label for="months" class="block text-sm font-medium text-gray-700">Em quantos meses deseja alcançar o objetivo?</label>
                        <input type="number" min="1" name="months" id="months" required
                               class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit"
                            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                        Simular
                    </button>
                </form>
                <div id="timeResult" class="hidden mt-4 border-t pt-4 text-gray-700">
                    <p>Para alcançar em <strong><span id="timeMonths"></span> meses</strong>:</p>
                    <ul class="list-disc list-inside">
                        <li>Você precisa guardar <strong>R$ <span id="requiredAmount"></span></strong> por mês</li>
                        <li>Data estimada: <strong><span id="timeEstimatedDate"></span></strong></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const simulateByAmount = document.getElementById('simulateByAmount');
    const simulateByTime = document.getElementById('simulateByTime');

    simulateByAmount.addEventListener('submit', async function (e) {
        e.preventDefault();
        const monthly_amount = parseFloat(this.monthly_amount.value);

        try {
            const response = await fetch(`{{ route('financial-goals.simulate', $financialGoal) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ monthly_amount })
            });

            if (!response.ok) {
                throw new Error('Erro na simulação');
            }

            const data = await response.json();
            
            // Formatar os valores monetários
            const formattedMonthlyAmount = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(data.monthly_amount);

            document.getElementById('amountValue').textContent = formattedMonthlyAmount;
            document.getElementById('monthsToReach').textContent = data.months;
            document.getElementById('estimatedDate').textContent = data.estimated_date;
            document.getElementById('amountResult').classList.remove('hidden');
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao realizar a simulação. Por favor, tente novamente.');
        }
    });

    simulateByTime.addEventListener('submit', async function (e) {
        e.preventDefault();
        const months = parseInt(this.months.value);

        try {
            const response = await fetch(`{{ route('financial-goals.simulate', $financialGoal) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ months })
            });

            if (!response.ok) {
                throw new Error('Erro na simulação');
            }

            const data = await response.json();
            
            // Formatar os valores monetários
            const formattedMonthlyAmount = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(data.monthly_amount);

            document.getElementById('timeMonths').textContent = months;
            document.getElementById('requiredAmount').textContent = formattedMonthlyAmount;
            document.getElementById('timeEstimatedDate').textContent = data.estimated_date;
            document.getElementById('timeResult').classList.remove('hidden');
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao realizar a simulação. Por favor, tente novamente.');
        }
    });
});
</script>
@endpush
@endsection

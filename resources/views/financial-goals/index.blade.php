@extends('layouts.dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Objetivos Financeiros</h1>
        <a href="{{ route('financial-goals.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
            <i class="fas fa-plus mr-2"></i>
            Novo Objetivo
        </a>
    </div>

    {{-- Simulador --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Simulador de Objetivos</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Simular por valor mensal --}}
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Simular por Valor Mensal</h4>
                    <form id="simulateByAmount" class="space-y-4">
                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-gray-700">Valor que deseja atingir</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md bg-gray-100 text-gray-500">R$</span>
                                <input type="number" step="0.01" min="0" name="target_amount" id="target_amount"
                                       required class="flex-1 block w-full rounded-r-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0,00">
                            </div>
                        </div>
                        <div>
                            <label for="monthly_amount" class="block text-sm font-medium text-gray-700">Quanto posso guardar por mês?</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md bg-gray-100 text-gray-500">R$</span>
                                <input type="number" step="0.01" min="0" name="monthly_amount" id="monthly_amount"
                                       required class="flex-1 block w-full rounded-r-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0,00">
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <i class="fas fa-calculator mr-2"></i>
                            Simular
                        </button>
                    </form>
                    <div id="amountResult" class="hidden mt-4 border-t pt-4 text-gray-700">
                        <p class="mb-2">Para atingir <strong>R$ <span id="targetValue"></span></strong> guardando <strong>R$ <span id="amountValue"></span></strong> por mês:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Você alcançará seu objetivo em <strong><span id="monthsToReach"></span> meses</strong></li>
                            <li>Data estimada: <strong><span id="estimatedDate"></span></strong></li>
                        </ul>
                        <div class="mt-4">
                            <a href="#" id="createGoalFromAmount" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Meta a partir desta Simulação
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Simular por tempo --}}
                <div class="bg-gray-50 rounded-lg p-6">
                    <h4 class="text-base font-medium text-gray-900 mb-4">Simular por Tempo</h4>
                    <form id="simulateByTime" class="space-y-4">
                        <div>
                            <label for="target_amount_time" class="block text-sm font-medium text-gray-700">Valor que deseja atingir</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md bg-gray-100 text-gray-500">R$</span>
                                <input type="number" step="0.01" min="0" name="target_amount_time" id="target_amount_time"
                                       required class="flex-1 block w-full rounded-r-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0,00">
                            </div>
                        </div>
                        <div>
                            <label for="months" class="block text-sm font-medium text-gray-700">Em quantos meses deseja alcançar o objetivo?</label>
                            <input type="number" min="1" name="months" id="months" required
                                   class="mt-1 block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                            <i class="fas fa-calculator mr-2"></i>
                            Simular
                        </button>
                    </form>
                    <div id="timeResult" class="hidden mt-4 border-t pt-4 text-gray-700">
                        <p class="mb-2">Para atingir <strong>R$ <span id="timeTargetValue"></span></strong> em <strong><span id="timeMonths"></span> meses</strong>:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Você precisa guardar <strong>R$ <span id="requiredAmount"></span></strong> por mês</li>
                            <li>Data estimada: <strong><span id="timeEstimatedDate"></span></strong></li>
                        </ul>
                        <div class="mt-4">
                            <a href="#" id="createGoalFromTime" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Criar Meta a partir desta Simulação
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lista de Objetivos --}}
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meta</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atual</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Limite</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($financialGoals as $goal)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $goal->name }}</div>
                                @if($goal->description)
                                    <div class="text-sm text-gray-500">{{ Str::limit($goal->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($goal->target_amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                R$ {{ number_format($goal->current_amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $goal->progress_percentage }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $goal->progress_percentage }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $goal->target_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($goal->status === 'completed')
                                        bg-green-100 text-green-800
                                    @elseif($goal->status === 'cancelled')
                                        bg-red-100 text-red-800
                                    @else
                                        bg-blue-100 text-blue-800
                                    @endif">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    {{ $goal::$statuses[$goal->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('financial-goals.show', $goal) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('financial-goals.edit', $goal) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('financial-goals.destroy', $goal) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza que deseja excluir este objetivo?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Nenhum objetivo financeiro encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const simulateByAmount = document.getElementById('simulateByAmount');
    const simulateByTime = document.getElementById('simulateByTime');
    const createGoalFromAmount = document.getElementById('createGoalFromAmount');
    const createGoalFromTime = document.getElementById('createGoalFromTime');

    let simulationData = {
        amount: null,
        time: null
    };

    simulateByAmount.addEventListener('submit', async function (e) {
        e.preventDefault();
        const target_amount = parseFloat(this.target_amount.value);
        const monthly_amount = parseFloat(this.monthly_amount.value);

        if (monthly_amount <= 0) {
            alert('O valor mensal deve ser maior que zero.');
            return;
        }

        const months = Math.ceil(target_amount / monthly_amount);
        const estimatedDate = new Date();
        estimatedDate.setMonth(estimatedDate.getMonth() + months);

        // Formatar os valores monetários
        const formattedTargetAmount = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(target_amount);

        const formattedMonthlyAmount = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(monthly_amount);

        document.getElementById('targetValue').textContent = formattedTargetAmount;
        document.getElementById('amountValue').textContent = formattedMonthlyAmount;
        document.getElementById('monthsToReach').textContent = months;
        document.getElementById('estimatedDate').textContent = estimatedDate.toLocaleDateString('pt-BR');
        document.getElementById('amountResult').classList.remove('hidden');

        // Armazenar dados da simulação
        simulationData.amount = {
            target_amount: target_amount,
            monthly_amount: monthly_amount,
            months: months,
            estimated_date: estimatedDate.toLocaleDateString('pt-BR')
        };
    });

    simulateByTime.addEventListener('submit', async function (e) {
        e.preventDefault();
        const target_amount = parseFloat(this.target_amount_time.value);
        const months = parseInt(this.months.value);

        if (months <= 0) {
            alert('O número de meses deve ser maior que zero.');
            return;
        }

        const monthly_amount = target_amount / months;
        const estimatedDate = new Date();
        estimatedDate.setMonth(estimatedDate.getMonth() + months);

        // Formatar os valores monetários
        const formattedTargetAmount = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(target_amount);

        const formattedMonthlyAmount = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(monthly_amount);

        document.getElementById('timeTargetValue').textContent = formattedTargetAmount;
        document.getElementById('timeMonths').textContent = months;
        document.getElementById('requiredAmount').textContent = formattedMonthlyAmount;
        document.getElementById('timeEstimatedDate').textContent = estimatedDate.toLocaleDateString('pt-BR');
        document.getElementById('timeResult').classList.remove('hidden');

        // Armazenar dados da simulação
        simulationData.time = {
            target_amount: target_amount,
            monthly_amount: monthly_amount,
            months: months,
            estimated_date: estimatedDate.toLocaleDateString('pt-BR')
        };
    });

    // Criar meta a partir da simulação por valor
    createGoalFromAmount.addEventListener('click', function(e) {
        e.preventDefault();
        if (!simulationData.amount) {
            alert('Por favor, realize uma simulação primeiro.');
            return;
        }

        const data = simulationData.amount;
        const targetDate = new Date(data.estimated_date.split('/').reverse().join('-'));
        
        window.location.href = `{{ route('financial-goals.create') }}?` + new URLSearchParams({
            target_amount: data.target_amount,
            monthly_amount: data.monthly_amount,
            target_date: targetDate.toISOString().split('T')[0]
        });
    });

    // Criar meta a partir da simulação por tempo
    createGoalFromTime.addEventListener('click', function(e) {
        e.preventDefault();
        if (!simulationData.time) {
            alert('Por favor, realize uma simulação primeiro.');
            return;
        }

        const data = simulationData.time;
        const targetDate = new Date(data.estimated_date.split('/').reverse().join('-'));
        
        window.location.href = `{{ route('financial-goals.create') }}?` + new URLSearchParams({
            target_amount: data.target_amount,
            monthly_amount: data.monthly_amount,
            target_date: targetDate.toISOString().split('T')[0]
        });
    });
});
</script>
@endpush
@endsection

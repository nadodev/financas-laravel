@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Objetivos Financeiros</h1>
        <a href="{{ route('financial-goals.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
            Novo Objetivo
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($goals->isEmpty())
        <div class="p-4 bg-blue-100 border border-blue-300 text-blue-800 rounded">
            Você ainda não tem objetivos financeiros cadastrados.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($goals as $goal)
                <div class="bg-white shadow rounded-lg p-6 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $goal->name }}</h2>

                        <div class="w-full bg-gray-200 rounded-full h-4 mb-4">
                            <div class="bg-blue-500 h-4 rounded-full transition-all duration-500"
                                 style="width: {{ $goal->progress_percentage }}%">
                                <span class="text-white text-xs font-semibold ml-2">{{ $goal->progress_percentage }}%</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 text-sm text-gray-600 mb-4">
                            <div>
                                <p class="font-medium">Meta</p>
                                <p class="text-gray-900">R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Atual</p>
                                <p class="text-gray-900">R$ {{ number_format($goal->current_amount, 2, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Falta</p>
                                <p class="text-gray-900">R$ {{ number_format($goal->remaining_amount, 2, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 text-sm text-gray-600 mb-4">
                            <div>
                                <p class="font-medium">Data Limite</p>
                                <p class="text-gray-900">{{ $goal->target_date->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Dias Restantes</p>
                                <p class="text-gray-900">{{ $goal->days_remaining }}</p>
                            </div>
                            <div>
                                <p class="font-medium">Mensal Necessário</p>
                                <p class="text-gray-900">R$ {{ number_format($goal->monthly_required_amount, 2, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($goal->account)
                            <div class="text-sm text-gray-600 mb-4">
                                <p class="font-medium">Conta Vinculada</p>
                                <p class="text-gray-900">{{ $goal->account->name }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xs font-semibold px-3 py-1 rounded-full
                            @if($goal->status === 'completed')
                                bg-green-100 text-green-800
                            @elseif($goal->status === 'cancelled')
                                bg-red-100 text-red-800
                            @else
                                bg-blue-100 text-blue-800
                            @endif
                        ">
                            {{ $goal::$statuses[$goal->status] }}
                        </span>

                        <div class="space-x-2">
                            <a href="{{ route('financial-goals.show', $goal) }}"
                               class="text-sm px-3 py-1 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded">
                                Detalhes
                            </a>
                            <a href="{{ route('financial-goals.edit', $goal) }}"
                               class="text-sm px-3 py-1 bg-yellow-200 hover:bg-yellow-300 text-yellow-800 rounded">
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

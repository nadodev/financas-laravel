@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-bold mb-6">Planos Disponíveis</h2>
                <p class="mb-8">Seu plano atual: <span class="font-semibold">{{ $currentPlan->name }}</span></p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($plans as $plan)
                        <div class="border rounded-lg p-6 {{ $currentPlan->id === $plan->id ? 'ring-2 ring-blue-500' : '' }}">
                            <h3 class="text-xl font-bold mb-2">{{ $plan->name }}</h3>
                            <div class="text-3xl font-bold mb-4">
                                R$ {{ number_format($plan->price, 2, ',', '.') }}
                                <span class="text-sm font-normal text-gray-600">/mês</span>
                            </div>
                            <div class="mb-6">
                                <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
                                <ul class="space-y-2">
                                    @foreach($plan->features as $feature)
                                        <li class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @if($currentPlan->id !== $plan->id)
                                <form action="{{ route('subscription.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                    <button type="submit" class="w-full bg-blue-500 text-white rounded-md py-2 hover:bg-blue-600 transition-colors">
                                        Mudar para este plano
                                    </button>
                                </form>
                            @else
                                <button disabled class="w-full bg-gray-100 text-gray-600 rounded-md py-2 cursor-not-allowed">
                                    Plano Atual
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
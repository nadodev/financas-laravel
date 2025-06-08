@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Configurações do Dashboard</h1>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('settings.dashboard.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Seções Visíveis</h3>
                            <p class="text-sm text-gray-500 mb-4">
                                Selecione as seções que você deseja ver no seu dashboard. As seções não selecionadas ficarão ocultas.
                            </p>
                            <div class="space-y-4">
                                @foreach($availableSections as $key => $name)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               name="sections[]" 
                                               value="{{ $key }}" 
                                               id="section_{{ $key }}"
                                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                               {{ in_array($key, $settings->visible_sections ?? []) ? 'checked' : '' }}>
                                        <label for="section_{{ $key }}" class="ml-3 text-sm text-gray-700">
                                            {{ $name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md">
                            Salvar Configurações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 
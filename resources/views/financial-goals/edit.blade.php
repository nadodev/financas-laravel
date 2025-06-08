@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Editar Objetivo Financeiro
            </h2>
        </div>

        <div class="px-6 py-6 space-y-6">
            {{-- Formulário de atualização --}}
            <form action="{{ route('financial-goals.update', $financialGoal) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('financial-goals._form')

                <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-4 pt-6 border-t border-gray-200">
                    <div class="flex gap-4 w-full sm:w-auto">
                        <a href="{{ route('financial-goals.index') }}"
                           class="w-full sm:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Voltar
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center shadow-sm hover:shadow">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Atualizar
                        </button>
                    </div>
                </div>
            </form>

            {{-- Formulário de exclusão separado --}}
            <form action="{{ route('financial-goals.destroy', $financialGoal) }}"
                  method="POST"
                  onsubmit="return confirm('Tem certeza que deseja excluir este objetivo? Esta ação não pode ser desfeita.');"
                  class="mt-6 pt-6 border-t border-gray-200">
                @csrf
                @method('DELETE')
                <div class="flex justify-end">
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 flex items-center justify-center hover:shadow-sm group">
                        <svg class="w-4 h-4 mr-2 transition-transform duration-200 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Excluir Objetivo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

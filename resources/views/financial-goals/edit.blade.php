@extends('layouts.dashboard')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-6">
    <div class="bg-white shadow rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Editar Objetivo Financeiro</h2>
        </div>

        <div class="px-6 py-5">
            {{-- Formulário de atualização --}}
            <form action="{{ route('financial-goals.update', $financialGoal) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('financial-goals._form')

                <div class="flex flex-col sm:flex-row sm:justify-between items-center gap-3">
                    <div class="flex gap-3 w-full sm:w-auto">
                        <a href="{{ route('financial-goals.index') }}"
                           class="w-full sm:w-auto text-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">
                            Voltar
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Atualizar
                        </button>
                    </div>
                </div>
            </form>

            {{-- Formulário de exclusão separado --}}
            <form action="{{ route('financial-goals.destroy', $financialGoal) }}"
                  method="POST"
                  onsubmit="return confirm('Tem certeza que deseja excluir este objetivo?');"
                  class="mt-6 text-right">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Excluir
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

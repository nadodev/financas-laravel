@extends('layouts.dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">
                    {{ isset($transaction) ? 'Editar Transação' : 'Nova Transação' }}
                </h1>

                @if ($errors->has('general'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ $errors->first('general') }}</span>
                    </div>
                @endif

                @php
                    $isEdit = isset($transaction);
                    $route = $isEdit ? route('transactions.update', $transaction) : route('transactions.store');
                    $method = $isEdit ? 'PUT' : 'POST';
                @endphp

                <form action="{{ $route }}" method="POST" class="space-y-6">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Descrição -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                            <input type="text" name="description" id="description" 
                                value="{{ old('description', $isEdit ? $transaction->description : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Valor -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                            <input type="number" step="0.01" name="amount" id="amount" 
                                value="{{ old('amount', $isEdit ? $transaction->amount : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Data -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Data</label>
                            <input type="date" name="date" id="date" 
                                value="{{ old('date', $isEdit ? $transaction->date->format('Y-m-d') : date('Y-m-d')) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="type" id="type" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="income" {{ old('type', $isEdit ? $transaction->type : '') === 'income' ? 'selected' : '' }}>Receita</option>
                                <option value="expense" {{ old('type', $isEdit ? $transaction->type : '') === 'expense' ? 'selected' : '' }}>Despesa</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Categoria -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                            <select name="category_id" id="category_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $isEdit ? $transaction->category_id : '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conta ou Cartão de Crédito -->
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pagamento</label>
                            <select name="payment_method" id="payment_method" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                onchange="togglePaymentMethod()">
                                <option value="">Selecione um método</option>
                                <option value="account" {{ old('payment_method', $isEdit && $transaction->account_id ? 'account' : '') === 'account' ? 'selected' : '' }}>Conta Bancária</option>
                                <option value="credit_card" {{ old('payment_method', $isEdit && $transaction->credit_card_id ? 'credit_card' : '') === 'credit_card' ? 'selected' : '' }}>Cartão de Crédito</option>
                            </select>
                        </div>

                        <!-- Conta (mostrado quando payment_method = account) -->
                        <div id="account_section" class="{{ old('payment_method', $isEdit && $transaction->account_id ? 'account' : '') === 'account' ? '' : 'hidden' }}">
                            <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                            <select name="account_id" id="account_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Selecione uma conta</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" 
                                        {{ old('account_id', $isEdit ? $transaction->account_id : '') == $account->id ? 'selected' : '' }}>
                                        {{ $account->name }} (Saldo: R$ {{ number_format($account->balance, 2, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cartão de Crédito (mostrado quando payment_method = credit_card) -->
                        <div id="credit_card_section" class="{{ old('payment_method', $isEdit && $transaction->credit_card_id ? 'credit_card' : '') === 'credit_card' ? '' : 'hidden' }}">
                            <label for="credit_card_id" class="block text-sm font-medium text-gray-700">Cartão de Crédito</label>
                            <select name="credit_card_id" id="credit_card_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Selecione um cartão</option>
                                @foreach($creditCards as $card)
                                    <option value="{{ $card->id }}" 
                                        {{ old('credit_card_id', $isEdit ? $transaction->credit_card_id : '') == $card->id ? 'selected' : '' }}>
                                        {{ $card->name }} (Limite: R$ {{ number_format($card->limit, 2, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('credit_card_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parcelamento -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_recurring" id="is_recurring" 
                                    {{ old('is_recurring', $isEdit ? $transaction->is_recurring : '') ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    onchange="toggleInstallments()">
                                <label for="is_recurring" class="ml-2 block text-sm text-gray-700">Parcelado</label>
                            </div>
                            
                            <div id="installments_section" class="{{ old('is_recurring', $isEdit ? $transaction->is_recurring : '') ? '' : 'hidden' }} mt-4">
                                <label for="installments" class="block text-sm font-medium text-gray-700">Número de Parcelas</label>
                                <input type="number" name="installments" id="installments" min="2" max="24"
                                    value="{{ old('installments', $isEdit ? $transaction->installments : '') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('installments')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
                            <textarea name="notes" id="notes" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes', $isEdit ? $transaction->notes : '') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('transactions.index') }}" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ $isEdit ? 'Atualizar' : 'Criar' }} Transação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePaymentMethod() {
            const paymentMethod = document.getElementById('payment_method').value;
            const accountSection = document.getElementById('account_section');
            const creditCardSection = document.getElementById('credit_card_section');
            
            if (paymentMethod === 'account') {
                accountSection.classList.remove('hidden');
                creditCardSection.classList.add('hidden');
                document.getElementById('credit_card_id').value = '';
            } else if (paymentMethod === 'credit_card') {
                accountSection.classList.add('hidden');
                creditCardSection.classList.remove('hidden');
                document.getElementById('account_id').value = '';
            } else {
                accountSection.classList.add('hidden');
                creditCardSection.classList.add('hidden');
                document.getElementById('account_id').value = '';
                document.getElementById('credit_card_id').value = '';
            }
        }

        function toggleInstallments() {
            const isRecurring = document.getElementById('is_recurring').checked;
            const installmentsSection = document.getElementById('installments_section');
            
            if (isRecurring) {
                installmentsSection.classList.remove('hidden');
            } else {
                installmentsSection.classList.add('hidden');
                document.getElementById('installments').value = '';
            }
        }
    </script>
    @endpush
@endsection 
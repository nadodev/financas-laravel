<!-- Modal de Nova Transação -->
<div x-show="showModal" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
             @click.away="showModal = false">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Nova Transação</h3>
                        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <!-- Descrição -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <input type="text" name="description" id="description" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Valor -->
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">R$</span>
                                        </div>
                                        <input type="number" name="amount" id="amount" 
                                            step="0.01" required
                                            class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Data -->
                                <div>
                                    <label for="date" class="block text-sm font-medium text-gray-700">Data</label>
                                    <input type="date" name="date" id="date" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tipo -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Selecione o tipo</option>
                                        <option value="income">Receita</option>
                                        <option value="expense">Despesa</option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Selecione o status</option>
                                        <option value="pending">Pendente</option>
                                        <option value="paid">Pago</option>
                                        <option value="cancelled">Cancelado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Categoria -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                                    <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Selecione uma categoria</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Conta -->
                                <div>
                                    <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                                    <select name="account_id" id="account_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Selecione uma conta</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Parcelamento -->
                            <div>
                                <div class="flex items-center mb-2">
                                    <input id="has_installments" name="has_installments" type="checkbox" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="has_installments" class="ml-2 block text-sm font-medium text-gray-700">
                                        Parcelado
                                    </label>
                                </div>
                                
                                <div id="installment_fields" class="hidden space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="total_installments" class="block text-sm font-medium text-gray-700">Total de Parcelas</label>
                                            <input type="number" name="total_installments" id="total_installments" min="2" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="current_installment" class="block text-sm font-medium text-gray-700">Parcela Atual</label>
                                            <input type="number" name="current_installment" id="current_installment" min="1" value="1"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recorrência -->
                            <div>
                                <div class="flex items-center">
                                    <input id="is_recurring" name="is_recurring" type="checkbox" 
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="is_recurring" class="ml-2 block text-sm font-medium text-gray-700">
                                        Transação Recorrente
                                    </label>
                                </div>
                            </div>

                            <div id="recurrence_fields" class="hidden space-y-4">
                                <div>
                                    <label for="recurrence_interval" class="block text-sm font-medium text-gray-700">Intervalo de Recorrência</label>
                                    <select id="recurrence_interval" name="recurrence_interval" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Selecione o intervalo</option>
                                        <option value="monthly">Mensal</option>
                                        <option value="weekly">Semanal</option>
                                        <option value="yearly">Anual</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="recurrence_end_date" class="block text-sm font-medium text-gray-700">Data Final da Recorrência</label>
                                    <input type="date" id="recurrence_end_date" name="recurrence_end_date" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <!-- Anexo -->
                            <div>
                                <label for="attachment" class="block text-sm font-medium text-gray-700">Anexo</label>
                                <div class="mt-1 flex items-center">
                                    <input type="file" id="attachment" name="attachment" 
                                        class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100">
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Anexe um comprovante ou documento relacionado (PDF, JPG, PNG)
                                </p>
                            </div>

                            <!-- Observações -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            </div>

                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Criar Transação
                                </button>
                                <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Campos de recorrência
        const isRecurringCheckbox = document.getElementById('is_recurring');
        const recurrenceFields = document.getElementById('recurrence_fields');

        function toggleRecurrenceFields() {
            recurrenceFields.classList.toggle('hidden', !isRecurringCheckbox.checked);
            if (isRecurringCheckbox.checked) {
                document.getElementById('recurrence_interval').required = true;
                document.getElementById('recurrence_end_date').required = true;
            } else {
                document.getElementById('recurrence_interval').required = false;
                document.getElementById('recurrence_end_date').required = false;
            }
        }

        isRecurringCheckbox.addEventListener('change', toggleRecurrenceFields);
        toggleRecurrenceFields();

        // Campos de parcelamento
        const hasInstallmentsCheckbox = document.getElementById('has_installments');
        const installmentFields = document.getElementById('installment_fields');

        function toggleInstallmentFields() {
            installmentFields.classList.toggle('hidden', !hasInstallmentsCheckbox.checked);
            if (hasInstallmentsCheckbox.checked) {
                document.getElementById('total_installments').required = true;
                document.getElementById('current_installment').required = true;
            } else {
                document.getElementById('total_installments').required = false;
                document.getElementById('current_installment').required = false;
            }
        }

        hasInstallmentsCheckbox.addEventListener('change', toggleInstallmentFields);
        toggleInstallmentFields();
    });
</script>
@endpush 
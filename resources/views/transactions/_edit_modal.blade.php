<!-- Modal de Edição de Transação -->
<div x-show="showEditModal" 
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
             @click.away="showEditModal = false">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Editar Transação</h3>
                        <form :action="editUrl" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            
                            <!-- Descrição -->
                            <div>
                                <label for="edit_description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                <input type="text" name="description" id="edit_description" required x-model="editingTransaction.description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Valor -->
                                <div>
                                    <label for="edit_amount" class="block text-sm font-medium text-gray-700">Valor</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">R$</span>
                                        </div>
                                        <input type="number" name="amount" id="edit_amount" 
                                            step="0.01" required x-model="editingTransaction.amount"
                                            class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Data -->
                                <div>
                                    <label for="edit_date" class="block text-sm font-medium text-gray-700">Data</label>
                                    <input type="date" name="date" id="edit_date" required x-model="editingTransaction.date"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Tipo -->
                                <div>
                                    <label for="edit_type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <select name="type" id="edit_type" required x-model="editingTransaction.type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="income">Receita</option>
                                        <option value="expense">Despesa</option>
                                    </select>
                                </div>

                                <!-- Categoria -->
                                <div>
                                    <label for="edit_category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                                    <select name="category_id" id="edit_category_id" required x-model="editingTransaction.category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Conta -->
                            <div>
                                <label for="edit_account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                                <select name="account_id" id="edit_account_id" required x-model="editingTransaction.account_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Observações -->
                            <div>
                                <label for="edit_notes" class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="notes" id="edit_notes" rows="3" x-model="editingTransaction.notes"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                            </div>

                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Atualizar Transação
                                </button>
                                <button type="button" @click="showEditModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
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
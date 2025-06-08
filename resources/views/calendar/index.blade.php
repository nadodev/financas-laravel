@extends('layouts.dashboard')

@section('styles')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<style>
    #calendar {
        background: white;
        padding: 20px;
        border-radius: 8px;
        min-height: 600px;
    }
    .fc-event {
        cursor: pointer;
    }
    .fc-event-title {
        white-space: normal;
        overflow: visible;
        padding: 2px 4px;
    }
    .fc-event.overdue {
        border: 2px dashed #991B1B !important;
    }
    .fc-event.overdue .fc-event-title {
        font-weight: bold;
    }
    /* Ajuste para telas menores */
    @media (max-width: 640px) {
        .fc-toolbar-title {
            font-size: 1.2em !important;
        }
        .fc-toolbar.fc-header-toolbar {
            flex-direction: column;
            gap: 1rem;
        }
        .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="sm:flex sm:items-center mb-6">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Calendário Financeiro</h1>
            <p class="mt-2 text-sm text-gray-700">
                Visualize suas receitas, despesas e faturas de cartão de crédito em um calendário.
            </p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div id="calendar"></div>
    </div>

    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="flex items-center">
            <div class="w-4 h-4 rounded-full bg-[#10B981] mr-2"></div>
            <span class="text-sm text-gray-600">Receitas</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 rounded-full bg-[#EF4444] mr-2"></div>
            <span class="text-sm text-gray-600">Despesas</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 rounded-full bg-[#3B82F6] mr-2"></div>
            <span class="text-sm text-gray-600">Faturas Abertas</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 rounded-full bg-[#F59E0B] mr-2"></div>
            <span class="text-sm text-gray-600">Faturas Fechadas</span>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="flex items-center">
            <div class="w-4 h-4 rounded-full bg-[#991B1B] mr-2"></div>
            <span class="text-sm text-gray-600">Atrasados</span>
        </div>
        <div class="flex items-center">
            <div class="w-8 h-4 border-2 border-dashed border-[#991B1B] mr-2"></div>
            <span class="text-sm text-gray-600">Borda tracejada = Atrasado</span>
        </div>
        <div class="flex items-center">
            <span class="text-sm mr-2">⚠️</span>
            <span class="text-sm text-gray-600">Ícone de alerta = Atrasado</span>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="eventModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Detalhes do Lançamento
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div id="eventDetails" class="text-sm text-gray-500">
                                <!-- Detalhes serão inseridos aqui via JavaScript -->
                            </div>
                            <div id="eventActions" class="mt-4">
                                <!-- Ações serão inseridas aqui via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <a id="eventViewLink" href="#" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Ver Detalhes
                </a>
                <button type="button" onclick="closeEventModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Nova Transação -->
<div id="newTransactionModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="newTransactionForm" action="{{ route('transactions.store') }}" method="POST" onsubmit="return validateForm()">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="newTransactionModalTitle">
                                Nova Transação
                            </h3>
                            <div class="mt-4 space-y-4">
                                <input type="hidden" name="date" id="transactionDate">

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
                                    <input type="text" name="description" id="description" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>

                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700">Valor</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">R$</span>
                                        </div>
                                        <input type="text" name="amount" id="amount" required
                                            class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="0,00">
                                    </div>
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <select name="type" id="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="expense">Despesa</option>
                                        <option value="income">Receita</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                                    <select name="category_id" id="category_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </select>
                                </div>

                                <div>
                                    <label for="account_id" class="block text-sm font-medium text-gray-700">Conta</label>
                                    <select name="account_id" id="account_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach(auth()->user()->accounts as $account)
                                            <option value="{{ $account->id }}" data-balance="{{ $account->balance }}">
                                                {{ $account->name }} - Saldo: R$ {{ number_format($account->balance, 2, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="payment_status" class="block text-sm font-medium text-gray-700">Status do Pagamento</label>
                                    <select name="payment_status" id="payment_status" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="pending">Pendente</option>
                                        <option value="paid">Pago</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Salvar
                    </button>
                    <button type="button" onclick="closeNewTransactionModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/vanilla-masker@1.2.0/build/vanilla-masker.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana'
        },
        events: {
            url: '/calendar/events',
            failure: function() {
                console.error('Erro ao carregar eventos');
            }
        },
        eventDidMount: function(info) {
            info.el.title = info.event.title;
            if (info.event.extendedProps.isOverdue) {
                info.el.classList.add('overdue');
            }
        },
        eventClick: function(info) {
            showEventDetails(info.event);
            info.jsEvent.preventDefault();
        },
        dateClick: function(info) {
            showNewTransactionModal(info.dateStr);
        },
        dayMaxEvents: true,
        displayEventTime: false,
        firstDay: 0,
        themeSystem: 'standard'
    });
    
    calendar.render();

    window.addEventListener('resize', function() {
        calendar.updateSize();
    });

    // Máscara para o campo de valor
    VMasker(document.querySelector("#amount")).maskMoney({
        precision: 2,
        separator: ',',
        delimiter: '.',
        unit: ''
    });

    // Atualiza as categorias baseado no tipo selecionado
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const categories = @json(auth()->user()->categories);
        const categorySelect = document.getElementById('category_id');
        
        categorySelect.innerHTML = '';
        categories.filter(cat => cat.type === type).forEach(category => {
            const option = new Option(category.name, category.id);
            categorySelect.add(option);
        });

        // Mostra ou esconde o aviso de saldo conforme o tipo
        const accountSelect = document.getElementById('account_id');
        updateBalanceWarning(type, accountSelect.options[accountSelect.selectedIndex]);
    });

    // Atualiza aviso de saldo quando a conta é alterada
    document.getElementById('account_id').addEventListener('change', function() {
        const type = document.getElementById('type').value;
        updateBalanceWarning(type, this.options[this.selectedIndex]);
    });

    // Atualiza aviso de saldo quando o valor é alterado
    document.getElementById('amount').addEventListener('input', function() {
        const type = document.getElementById('type').value;
        const accountSelect = document.getElementById('account_id');
        updateBalanceWarning(type, accountSelect.options[accountSelect.selectedIndex]);
    });

    // Dispara o evento change para carregar as categorias iniciais
    document.getElementById('type').dispatchEvent(new Event('change'));

    // Atualiza o calendário após salvar uma transação
    document.getElementById('newTransactionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeNewTransactionModal();
                calendar.refetchEvents();
                showSuccessMessage(data.message);
            } else {
                showErrorMessage(data.message);
            }
        })
        .catch(error => {
            showErrorMessage('Erro ao salvar a transação');
            console.error('Error:', error);
        });
    });
});

function showEventDetails(event) {
    const modal = document.getElementById('eventModal');
    const detailsContainer = document.getElementById('eventDetails');
    const actionsContainer = document.getElementById('eventActions');
    const viewLink = document.getElementById('eventViewLink');
    const modalTitle = document.getElementById('modal-title');

    // Determina se é uma transação ou fatura baseado no ID do evento
    const isTransaction = event.id.startsWith('transaction_');
    const eventData = parseEventTitle(event.title);

    // Atualiza o título da modal
    modalTitle.textContent = isTransaction ? 'Detalhes da Transação' : 'Detalhes da Fatura';

    // Monta o HTML dos detalhes
    let detailsHtml = `
        <div class="grid grid-cols-1 gap-4">
            <div>
                <span class="font-medium text-gray-700">Descrição:</span>
                <span class="ml-2">${eventData.description}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Valor:</span>
                <span class="ml-2">R$ ${eventData.amount}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Status:</span>
                <span class="ml-2">${eventData.status}</span>
            </div>
            <div>
                <span class="font-medium text-gray-700">Data:</span>
                <span class="ml-2">${formatDate(event.start)}</span>
            </div>
    `;

    if (eventData.category) {
        detailsHtml += `
            <div>
                <span class="font-medium text-gray-700">Categoria:</span>
                <span class="ml-2">${eventData.category}</span>
            </div>
        `;
    }

    detailsHtml += '</div>';
    detailsContainer.innerHTML = detailsHtml;

    // Atualiza o link de visualização
    viewLink.href = event.url;

    // Mostra a modal
    modal.classList.remove('hidden');
}

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
}

function parseEventTitle(title) {
    // Exemplo: "Descrição - Categoria - R$ 100,00 (Status)"
    const parts = title.split(' - ');
    const result = {
        description: parts[0],
        amount: '',
        status: '',
        category: ''
    };

    parts.forEach(part => {
        if (part.startsWith('R$ ')) {
            [result.amount, result.status] = part.split(' ');
        } else if (!result.category && parts.length > 2) {
            result.category = part;
        }
    });

    // Remove parênteses do status
    result.status = result.status.replace(/[()]/g, '');

    return result;
}

function formatDate(date) {
    return new Intl.DateTimeFormat('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    }).format(date);
}

function validateForm() {
    const amount = document.getElementById('amount').value;
    if (!amount || amount === '0,00') {
        showErrorMessage('Por favor, insira um valor válido');
        return false;
    }

    const type = document.getElementById('type').value;
    const paymentStatus = document.getElementById('payment_status').value;
    
    if (type === 'expense' && paymentStatus === 'paid') {
        const accountSelect = document.getElementById('account_id');
        const option = accountSelect.options[accountSelect.selectedIndex];
        const balance = parseFloat(option.dataset.balance || '0');
        const value = parseFloat(amount.replace('R$', '').replace('.', '').replace(',', '.'));
        
        if (value > balance) {
            showErrorMessage(`Saldo insuficiente. Saldo disponível: R$ ${balance.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`);
            return false;
        }
    }

    return true;
}

function updateBalanceWarning(type, accountOption) {
    const warningDiv = document.getElementById('balanceWarning') || createBalanceWarning();
    const amount = document.getElementById('amount').value;
    const value = parseFloat(amount.replace('R$', '').replace('.', '').replace(',', '.'));
    const balance = parseFloat(accountOption.dataset.balance || '0');
    const paymentStatus = document.getElementById('payment_status').value;

    if (type === 'expense' && paymentStatus === 'paid') {
        warningDiv.style.display = 'block';
        if (value > balance) {
            warningDiv.className = 'mt-2 text-red-600 text-sm';
            warningDiv.textContent = `Atenção: Valor maior que o saldo disponível (R$ ${balance.toLocaleString('pt-BR', { minimumFractionDigits: 2 })})`;
        } else {
            warningDiv.className = 'mt-2 text-gray-600 text-sm';
            warningDiv.textContent = `Saldo disponível: R$ ${balance.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
        }
    } else {
        warningDiv.style.display = 'none';
    }
}

function createBalanceWarning() {
    const div = document.createElement('div');
    div.id = 'balanceWarning';
    div.className = 'mt-2 text-gray-600 text-sm';
    const amountField = document.querySelector('#amount').parentElement.parentElement;
    amountField.appendChild(div);
    return div;
}

function showSuccessMessage(message) {
    // Implementar mensagem de sucesso
    alert(message || 'Transação salva com sucesso!');
}

function showErrorMessage(message) {
    // Implementar mensagem de erro
    alert(message || 'Erro ao salvar a transação');
}

function showNewTransactionModal(date) {
    document.getElementById('transactionDate').value = date;
    document.getElementById('newTransactionModal').classList.remove('hidden');
}

function closeNewTransactionModal() {
    document.getElementById('newTransactionModal').classList.add('hidden');
    document.getElementById('newTransactionForm').reset();
    document.getElementById('type').dispatchEvent(new Event('change'));
}
</script>
@endpush 
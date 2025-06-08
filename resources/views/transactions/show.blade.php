@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detalhes da Transação</h5>
            <div>
                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-primary btn-sm">Editar</a>
                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir esta transação?')">
                        Excluir
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Descrição:</strong> {{ $transaction->description }}</p>
                    <p><strong>Valor:</strong> R$ {{ number_format($transaction->amount, 2, ',', '.') }}</p>
                    <p><strong>Data:</strong> {{ $transaction->date->format('d/m/Y') }}</p>
                    <p><strong>Tipo:</strong> {{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}</p>
                    <p><strong>Categoria:</strong> {{ $transaction->category->name }}</p>
                    <p><strong>Conta:</strong> {{ $transaction->account->name }}</p>
                    <p><strong>Status:</strong> {{ $transaction->status === 'paid' ? 'Pago' : 'Pendente' }}</p>
                </div>
                <div class="col-md-6">
                    @if($transaction->recurring)
                        <p><strong>Recorrente:</strong> Sim</p>
                        <p><strong>Intervalo:</strong> {{ $transaction->recurrence_interval }} dias</p>
                        @if($transaction->recurrence_end_date)
                            <p><strong>Data Final:</strong> {{ $transaction->recurrence_end_date->format('d/m/Y') }}</p>
                        @endif
                        @if($transaction->next_recurrence_date)
                            <p><strong>Próxima Recorrência:</strong> {{ $transaction->next_recurrence_date->format('d/m/Y') }}</p>
                        @endif
                    @endif

                    @if($transaction->installment)
                        <p><strong>Parcelado:</strong> Sim</p>
                        <p><strong>Parcela:</strong> {{ $transaction->current_installment }}/{{ $transaction->total_installments }}</p>
                        @if($transaction->parent_id)
                            <p><strong>Parcela Principal:</strong> <a href="{{ route('transactions.show', $transaction->parent_id) }}">Ver</a></p>
                        @endif
                    @endif

                    @if($transaction->attachment)
                        <div class="mt-4">
                            <h6>Anexo:</h6>
                            <a href="{{ Storage::url($transaction->attachment) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download"></i> Baixar Anexo
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            @if($transaction->parent_id === null && $transaction->installment)
                <div class="mt-4">
                    <h6>Parcelas:</h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Parcela</th>
                                    <th>Valor</th>
                                    <th>Data</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->installments as $installment)
                                    <tr>
                                        <td>{{ $installment->current_installment }}/{{ $installment->total_installments }}</td>
                                        <td>R$ {{ number_format($installment->amount, 2, ',', '.') }}</td>
                                        <td>{{ $installment->date->format('d/m/Y') }}</td>
                                        <td>{{ $installment->status === 'paid' ? 'Pago' : 'Pendente' }}</td>
                                        <td>
                                            <a href="{{ route('transactions.show', $installment) }}" class="btn btn-link btn-sm">Ver</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 
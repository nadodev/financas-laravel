@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1>Relatório Financeiro</h1>
    <p>Data: {{ now()->format('d/m/Y') }}</p>
    <p>Total de transações: {{ $transactions->count() }}</p>
    <p>Total de receitas: {{ $transactions->where('type', 'income')->sum('amount') }}</p>
    <p>Total de despesas: {{ $transactions->where('type', 'expense')->sum('amount') }}</p>
</div>
@endsection
@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Novo Objetivo Financeiro</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('financial-goals.store') }}" method="POST">
                        @csrf
                        @include('financial-goals._form')

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('financial-goals.index') }}" class="btn btn-secondary">Voltar</a>
                            <button type="submit" class="btn btn-primary">Criar Objetivo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
<div class="mb-3">
    <label for="name" class="form-label">Nome do Objetivo</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" 
           id="name" name="name" value="{{ old('name', $financialGoal->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descrição</label>
    <textarea class="form-control @error('description') is-invalid @enderror" 
              id="description" name="description" rows="3">{{ old('description', $financialGoal->description ?? '') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="target_amount" class="form-label">Valor da Meta</label>
        <div class="input-group">
            <span class="input-group-text">R$</span>
            <input type="number" step="0.01" min="0" 
                   class="form-control @error('target_amount') is-invalid @enderror" 
                   id="target_amount" name="target_amount" 
                   value="{{ old('target_amount', $financialGoal->target_amount ?? '') }}" required>
        </div>
        @error('target_amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="current_amount" class="form-label">Valor Atual</label>
        <div class="input-group">
            <span class="input-group-text">R$</span>
            <input type="number" step="0.01" min="0" 
                   class="form-control @error('current_amount') is-invalid @enderror" 
                   id="current_amount" name="current_amount" 
                   value="{{ old('current_amount', $financialGoal->current_amount ?? '0') }}">
        </div>
        @error('current_amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="target_date" class="form-label">Data Limite</label>
        <input type="date" class="form-control @error('target_date') is-invalid @enderror" 
               id="target_date" name="target_date" 
               value="{{ old('target_date', isset($financialGoal) ? $financialGoal->target_date->format('Y-m-d') : '') }}" required>
        @error('target_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="account_id" class="form-label">Conta Vinculada (Opcional)</label>
        <select class="form-select @error('account_id') is-invalid @enderror" 
                id="account_id" name="account_id">
            <option value="">Selecione uma conta...</option>
            @foreach($accounts as $account)
                <option value="{{ $account->id }}" 
                    {{ old('account_id', $financialGoal->account_id ?? '') == $account->id ? 'selected' : '' }}>
                    {{ $account->name }}
                </option>
            @endforeach
        </select>
        @error('account_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

@if(isset($financialGoal))
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-select @error('status') is-invalid @enderror" 
                id="status" name="status" required>
            @foreach($statuses as $key => $value)
                <option value="{{ $key }}" 
                    {{ old('status', $financialGoal->status) == $key ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif 
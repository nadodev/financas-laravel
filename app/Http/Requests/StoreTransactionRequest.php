<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'status' => 'required|in:paid,pending,cancelled',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            
            // Campos de recorrência
            'recurring' => 'nullable|boolean',
            'recurrence_interval' => 'required_if:recurring,1|nullable|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:date',
            
            // Campos de parcelamento
            'installment' => 'nullable|boolean',
            'total_installments' => 'required_if:installment,1|nullable|integer|min:2|max:48',
        ];
    }

    public function messages()
    {
        return [
            'description.required' => 'A descrição é obrigatória.',
            'amount.required' => 'O valor é obrigatório.',
            'amount.numeric' => 'O valor deve ser um número.',
            'amount.min' => 'O valor deve ser maior que zero.',
            'date.required' => 'A data é obrigatória.',
            'date.date' => 'A data deve ser válida.',
            'type.required' => 'O tipo é obrigatório.',
            'type.in' => 'O tipo deve ser receita ou despesa.',
            'category_id.required' => 'A categoria é obrigatória.',
            'category_id.exists' => 'A categoria selecionada não existe.',
            'account_id.required' => 'A conta é obrigatória.',
            'account_id.exists' => 'A conta selecionada não existe.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser pago, pendente ou cancelado.',
            'attachment.file' => 'O anexo deve ser um arquivo.',
            'attachment.max' => 'O anexo não pode ser maior que 10MB.',
            
            'recurrence_interval.required_if' => 'O intervalo de recorrência é obrigatório para transações recorrentes.',
            'recurrence_interval.integer' => 'O intervalo de recorrência deve ser um número inteiro.',
            'recurrence_interval.min' => 'O intervalo de recorrência deve ser maior que zero.',
            'recurrence_end_date.date' => 'A data final de recorrência deve ser válida.',
            'recurrence_end_date.after' => 'A data final de recorrência deve ser posterior à data da transação.',
            
            'total_installments.required_if' => 'O número de parcelas é obrigatório para transações parceladas.',
            'total_installments.integer' => 'O número de parcelas deve ser um número inteiro.',
            'total_installments.min' => 'O número de parcelas deve ser pelo menos 2.',
            'total_installments.max' => 'O número de parcelas não pode ser maior que 48.',
        ];
    }

    protected function prepareForValidation()
    {
        // Converte valores booleanos
        $this->merge([
            'recurring' => $this->boolean('recurring'),
            'installment' => $this->boolean('installment'),
        ]);

        // Converte o valor monetário para formato decimal
        if ($this->has('amount')) {
            $amount = str_replace(['R$', '.', ','], ['', '', '.'], $this->amount);
            $this->merge(['amount' => $amount]);
        }
    }
} 
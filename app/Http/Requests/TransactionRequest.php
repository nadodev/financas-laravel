<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'account_id' => 'required|exists:accounts,id',
            'status' => 'required|in:pending,paid,cancelled',
            'attachment' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
            'recurring' => 'nullable|boolean',
            'recurrence_interval' => 'required_if:recurring,1|nullable|integer|min:1',
            'recurrence_end_date' => 'required_if:recurring,1|nullable|date|after:date',
            'update_all_recurrences' => 'nullable|boolean',
            'installment' => 'nullable|boolean',
            'total_installments' => 'required_if:installment,1|nullable|integer|min:2',
            'current_installment' => 'nullable|integer|min:1'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'description.required' => 'A descrição é obrigatória',
            'amount.required' => 'O valor é obrigatório',
            'amount.numeric' => 'O valor deve ser um número',
            'amount.min' => 'O valor deve ser maior que zero',
            'date.required' => 'A data é obrigatória',
            'date.date' => 'A data deve ser válida',
            'type.required' => 'O tipo é obrigatório',
            'type.in' => 'O tipo deve ser receita ou despesa',
            'category_id.required' => 'A categoria é obrigatória',
            'category_id.exists' => 'A categoria selecionada não existe',
            'account_id.required' => 'A conta é obrigatória',
            'account_id.exists' => 'A conta selecionada não existe',
            'status.required' => 'O status é obrigatório',
            'status.in' => 'O status deve ser pendente, pago ou cancelado',
            'attachment.file' => 'O anexo deve ser um arquivo',
            'attachment.max' => 'O anexo não pode ter mais que 2MB',
            'attachment.mimes' => 'O anexo deve ser um arquivo PDF, JPG, JPEG ou PNG',
            'recurrence_interval.required_if' => 'O intervalo de recorrência é obrigatório para transações recorrentes',
            'recurrence_interval.integer' => 'O intervalo de recorrência deve ser um número inteiro',
            'recurrence_interval.min' => 'O intervalo de recorrência deve ser maior que zero',
            'recurrence_end_date.required_if' => 'A data final é obrigatória para transações recorrentes',
            'recurrence_end_date.date' => 'A data final deve ser válida',
            'recurrence_end_date.after' => 'A data final deve ser posterior à data inicial',
            'total_installments.required_if' => 'O número de parcelas é obrigatório para transações parceladas',
            'total_installments.integer' => 'O número de parcelas deve ser um número inteiro',
            'total_installments.min' => 'O número de parcelas deve ser maior que 1'
        ];
    }
} 
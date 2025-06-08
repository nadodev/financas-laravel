<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'cpf' => ['nullable', 'string', 'size:14', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            'phone' => ['nullable', 'string', 'max:15', 'regex:/^\(\d{2}\) \d{5}-\d{4}$/'],
            'address' => ['nullable', 'string', 'max:255'],
            'address_number' => ['nullable', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:255'],
            'neighborhood' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'size:2'],
            'zip_code' => ['nullable', 'string', 'size:9', 'regex:/^\d{5}-\d{3}$/']
        ];
    }

    public function messages(): array
    {
        return [
            'cpf.regex' => 'O CPF deve estar no formato 000.000.000-00',
            'phone.regex' => 'O telefone deve estar no formato (00) 00000-0000',
            'zip_code.regex' => 'O CEP deve estar no formato 00000-000',
        ];
    }
}

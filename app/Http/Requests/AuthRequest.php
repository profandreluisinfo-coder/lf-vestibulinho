<?php

namespace App\Http\Requests;

use App\Rules\CpfValidation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Somente se o usuário não estiver logado pode fazer login
        if (!Auth::check()) {
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cpf' => ['required', new CpfValidation()],
            'email' => ['required', 'email'],
        ];
    }

    public function messages()
    {
        return [
            'cpf.required' => 'O campo cpf é obrigatório.',
            'cpf.cpf' => 'O campo cpf deve ser um CPF válido.',
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'O campo email deve ser um endereço de email válido.',
        ];
    }
}
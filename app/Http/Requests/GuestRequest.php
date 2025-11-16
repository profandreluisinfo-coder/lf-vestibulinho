<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class GuestRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            // Aplica manipulação aos valores apenas se forem strings
            $sanitized[$key] = trim($value); // Remove espaços
        }

        // Substitui os valores originais pelos sanitizados
        $this->merge($sanitized);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Campo obrigatório.',
            'email.email' => 'Endereço de e-mail inválido.'
        ];
    }
}

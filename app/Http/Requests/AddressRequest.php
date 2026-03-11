<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (session()->has('step1') && session()->has('step2')) {
            return true;
        }

        return false;
    }

    public function prepareForValidation()
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            $sanitized[$key] = trim($value); // Remove espaços
            $sanitized[$key] = mb_strtoupper($value, 'UTF-8');
        }

        // Substitui os valores originais pelos sanitizados
        $this->merge($sanitized);
    }

    /**
     * Define as regras de validação que se aplicam à request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // endereço
            'zip' => ['required', 'min:10', 'max:10'],
            'street' => ['required', 'max:60', 'regex:/^[\p{L}0-9\s.,-]+$/u'],
            'number' => ['required', 'max:10', 'regex:/^[\p{L}0-9\s\/]*$/u'],
            'complement' => ['nullable', 'max:20', 'regex:/^[\p{L}0-9\s.,-]+$/u'],
            'burgh' => ['required', 'max:60', 'regex:/^[\p{L}0-9\s.,()\-]+$/u'],
            'city' => ['required', 'max:30', 'regex:/^[a-zA-ZÀ-Úà-ú ]+$/'],
            'state' => ['required', 'max:32'],
        ];
    }

    public function messages()
    {
        return [
            // endereço
            'zip.required' => '* Obrigatório',
            'zip.min' => '* No mínimo :min caracteres',
            'zip.max' => '* No máximo :max caracteres',

            'street.required' => '* Obrigatório',
            'street.max' => '* No máximo :max caracteres',
            'street.regex' => '* Apenas letras, números e espaços',

            'number.required' => '* Obrigatório. Caso não tenha, digite S/N.',
            'number.max' => '* No máximo :max caracteres',
            'number.regex' => '* Apenas letras, números e espaços',

            'complement.max' => '* No máximo :max caracteres',
            'complement.regex' => '* Apenas letras, números e espaços',

            'burgh.required' => '* Obrigatório',
            'burgh.max' => '* No máximo :max caracteres',
            'burgh.regex' => '* Apenas letras, números e espaços',

            'city.required' => '* Obrigatório',
            'city.max' => '* No máximo :max caracteres',
            'city.regex' => '* Apenas letras, números e espaços',

            'state.required' => '* Obrigatório',
            'state.max' => '* No máximo :max caracteres',
            'state.regex' => '* Apenas letras, números e espaços',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Rules\CpfRule;
use App\Rules\NameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Autoriza todos os usuários a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Pré-processa os dados antes da validação.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
            collect($this->all())->map(function ($value, $key) {
                if (!is_string($value)) return $value;

                return $key === 'cpf'
                    ? preg_replace('/\D/', '', $value) // Apenas números no CPF
                    : mb_strtoupper(trim($value), 'UTF-8'); // Demais campos: Trim + Upper
            })->toArray()
        );
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        $socialNameRequired = $this->input('socialNameOption') == 1;

        return [
            'cpf' => ['required', new CpfRule(), 'unique:users,cpf'],

            'name' => ['required', 'max:100', 'regex:/^[\p{L}\s]+$/u', new NameRule()],

            'gender' => ['required', Rule::in([1, 2, 3, 4])],

            'socialNameOption' => ['required'],

            'social_name' => [
                Rule::requiredIf($socialNameRequired),
                $socialNameRequired ? new NameRule() : null,
                'nullable',
                'max:100',
                'regex:/^[\p{L}\s]+$/u',
            ],

            'phone' => ['required', 'min:14', 'max:15'],

            'birth' => ['required', 'date', 'before:today'],

            'nationality' => ['required', Rule::in([1, 2])],

            'doc_type' => ['required', Rule::in([1, 2, 3])],

            'doc_number' => [
                'required',
                'regex:/^\d{7}[\dA-Za-z]{0,4}$/',
                'min:7',
                'max:11',
                'unique:user_details,doc_number',
            ],
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            // CPF
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.unique' => 'CPF já cadastrado.',

            // Nome
            'name.required' => 'O campo nome é obrigatório.',
            'name.regex' => 'O campo nome deve conter apenas letras.',
            'name.max' => 'O campo nome deve conter no máximo :max caracteres.',

            // Nome Social
            'socialNameOption.required' => 'Informe se deseja usar o nome social/afetivo.',
            'social_name.required' => 'O campo nome social/afetivo é obrigatório.',
            'social_name.regex' => 'O campo nome social/afetivo deve conter apenas letras.',
            'social_name.max' => 'O campo nome social/afetivo deve conter no máximo :max caracteres.',

            // Nacionalidade
            'nationality.required' => 'O campo nacionalidade é obrigatório.',
            'nationality.in' => 'Nacionalidade inválida.',

            // Tipo de Documento
            'doc_type.required' => 'O tipo de documento é obrigatório.',
            'doc_type.in' => 'Tipo de documento inválido.',

            // Número do Documento
            'doc_number.required' => 'O número do documento é obrigatório.',
            'doc_number.regex' => 'O campo nº do documento deve conter de 7 a 11 caracteres (números e/ou letras).',
            'doc_number.min' => 'O campo nº do documento deve conter no mínimo :min caracteres.',
            'doc_number.max' => 'O campo nº do documento deve conter no máximo :max caracteres.',
            'doc_number.unique' => 'Documento já cadastrado.',

            // Data de Nascimento
            'birth.required' => 'O campo data de nascimento é obrigatório.',
            'birth.date' => 'Data de nascimento inválida.',
            'birth.before' => 'Data de nascimento inválida. Não pode ser maior que a data atual.',

            // Gênero
            'gender.required' => 'O campo gênero é obrigatório.',
            'gender.in' => 'O gênero selecionado não é válido. Por favor, escolha uma opção válida.',

            // Telefone
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.min' => 'O campo telefone deve conter no mínimo :min caracteres.',
            'phone.max' => 'O campo telefone deve conter no máximo :max caracteres.',
        ];
    }
}
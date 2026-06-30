<?php

namespace App\Http\Requests;

use App\Rules\CpfRule;
use App\Rules\NameRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalRequest extends FormRequest
{
    /**
     * Autoriza todos os usuários que não tenham uma inscrição a fazer esta requisição.
     */
    public function authorize(): bool
    {
        // Verifica se o usuário está autenticado
        if (!auth()->check()) {
            return false;
        }

        // Retorna true apenas se NÃO tiver inscrição
        return !auth()->user()->hasInscription();
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
        $socialNameRequired = $this->input('social_name_option') == 1;

        return [

            'cpf' => ['required', new CpfRule(), 'unique:users,cpf'],
            'name' => ['required', 'max:100', 'regex:/^[\p{L}\s]+$/u', new NameRule()],
            'birth' => ['required', 'date', 'before:today'],

            'gender' => ['required', Rule::in([1, 2, 3, 4])], // Relacionamento

            'social_name' => [ // Relacionamento
                Rule::requiredIf($socialNameRequired),
                $socialNameRequired ? new NameRule() : null,
                'nullable',
                'max:100',
                'regex:/^[\p{L}\s]+$/u',
            ],
            // Arquivo (Relacionamento)
            'authorization' => [
                Rule::requiredIf($socialNameRequired),
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120', // limite de 5MB
            ],

            'phone' => ['required', 'min:14', 'max:15'],

            'nationality' => ['required', Rule::in([1, 2, 3, 4])],

            'doc_type' => ['required', Rule::in([1, 2, 3])],

            'doc_number' => [
                'required',
                'regex:/^\d{7}[\dA-Za-z]{0,4}$/',
                'min:7',
                'max:11',
                'unique:documents,number'
            ],
            'expedition' => ['required', 'date', 'before:today'],
        ];
    }

    /**
     * Mensagens de erro personalizadas.
     */
    public function messages(): array
    {
        return [
            // CPF
            'cpf.required' => '* O campo CPF é obrigatório.',
            'cpf.unique' => '* CPF já cadastrado.',

            // Nome
            'name.required' => '* O campo nome é obrigatório.',
            'name.regex' => '* O campo nome deve conter apenas letras.',
            'name.max' => '* O campo nome deve conter no máximo :max caracteres.',

            // Nome Social
            'social_name_option.required' => '* Informe se deseja usar o nome social/afetivo.',
            'social_name.required' => '* O campo nome social/afetivo é obrigatório.',
            'social_name.regex' => '* O campo nome social/afetivo deve conter apenas letras.',
            'social_name.max' => '* O campo nome social/afetivo deve conter no máximo :max caracteres.',

            // Autorização
            'authorization.required' => '* O campo autorização é obrigatório.',
            'authorization.file' => '* O campo autorização deve ser um arquivo PDF.',
            'authorization.mimes' => '* O campo autorização deve ser um arquivo PDF.',
            'authorization.max' => '* O campo autorização deve ter no máximo 5MB.',

            // Nacionalidade
            'nationality.required' => '* O campo nacionalidade é obrigatório.',
            'nationality.in' => '* Nacionalidade inválida.',

            // Tipo de Documento
            'doc_type.required' => '* O tipo de documento é obrigatório.',
            'doc_type.in' => '* Tipo de documento inválido.',

            // Número do Documento
            'doc_number.required' => '* O número do documento é obrigatório.',
            'doc_number.regex' => '* O campo nº do documento deve conter de 7 a 11 caracteres (números e/ou letras).',
            'doc_number.min' => '* O campo nº do documento deve conter no mínimo :min caracteres.',
            'doc_number.max' => '* O campo nº do documento deve conter no máximo :max caracteres.',
            'doc_number.unique' => '* Documento já cadastrado.',

            // Data de Expedição
            'expedition.required' => '* O campo data de expedição é obrigatório.',
            'expedition.date' => '* Data de expedição inválida.',
            'expedition.before' => '* Data de expedição inválida. Não pode ser maior que a data atual.',

            // Data de Nascimento
            'birth.required' => '* O campo data de nascimento é obrigatório.',
            'birth.date' => '* Data de nascimento inválida.',
            'birth.before' => '* Data de nascimento inválida. Não pode ser maior que a data atual.',

            // Gênero
            'gender.required' => '* O campo gênero é obrigatório.',
            'gender.in' => '* O gênero selecionado não é válido. Por favor, escolha uma opção válida.',

            // Telefone
            'phone.required' => '* O campo telefone é obrigatório.',
            'phone.min' => '* O campo telefone deve conter no mínimo :min caracteres.',
            'phone.max' => '* O campo telefone deve conter no máximo :max caracteres.',
        ];
    }
}

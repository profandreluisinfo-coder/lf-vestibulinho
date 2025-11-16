<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (session()->has('step1') && session()->has('step2') && session()->has('step3') && session()->has('step4')) {
            return true;
        }

        return false;
    }

    public function prepareForValidation()
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            // Aplica manipulação aos valores apenas se forem strings
            if (is_string($value)) {
                $sanitized[$key] = trim($value); // Remove espaços
                $sanitized[$key] = mb_strtoupper($value, 'UTF-8');

                // if ($key !== 'email') {
                //     $sanitized[$key] = mb_strtoupper($sanitized[$key]); // Converte para maiúsculas
                // }

            } else {
                $sanitized[$key] = $value; // Mantém o valor original se não for string
            }
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
            // filiação
            'mother' => ['required', 'max:60', new NameRule()],
            'father' => ['nullable', 'max:60', new NameRule()],

            //responsável legal (informar ou não)
            'respLegalOption' => ['required', 'in:1,2'],

            // nome do responsável legal
            'responsible' => [
                'nullable',
                Rule::requiredIf(fn() => $this->input('respLegalOption') == 1),
                'max:60',
                $this->input('respLegalOption') == 1 ? new NameRule() : null,
            ],

            // grau de parentesco
            'degree' => [
                'nullable',
                Rule::requiredIf(fn() => $this->input('respLegalOption') == 1),
                'in:1,2,3,4,5,6,7,8',
            ],

            // descrição do grau de parentesco
            'kinship' => [
                'nullable'
            ],

            // telefone dos pais ou responsável legal 
            'mother_phone' => ['nullable'],
            'father_phone' => ['nullable'],

            'responsible_phone' => [
                'nullable',
                Rule::requiredIf(fn() => $this->input('respLegalOption') == 1)
            ],

            // e-mail dos pais ou responsável legal 
            'parents_email' => ['required', 'email', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            // filiação e responsável legal
            'mother.required' => 'MÃE: Obrigatório',
            'mother.max' => 'MÃE: No máximo :max caracteres',

            'father.max' => 'PAI: No máximo :max caracteres',

            'respLegalOption.required' => 'INFORMAR RESPONSAVEL LEGAL: Obrigatório',
            'respLegalOption.in' => 'INFORMAR RESPONSAVEL LEGAL: Opção inválida',
            'responsible.max' => 'INFORMAR RESPONSAVEL LEGAL: No máximo :max caracteres',
            'responsible.required' => 'INFORMAR RESPONSAVEL LEGAL: Obrigatório',

            'degree.required' => 'GRAU DE PARENTESCO: Obrigatório',
            'degree.in' => 'GRAU DE PARENTESCO: Opção inválida',

            'kinship.required' => 'DESCRIÇÃO DO PARENTESCO: Obrigatório',

            'responsible_phone.required' => 'RESPONSÁVEL LEGAL: Obrigatório',
            'responsible_phone.min' => 'RESPONSÁVEL LEGAL (TELEFONE): No mínimo :min caracteres',
            'responsible_phone.max' => 'RESPONSÁVEL LEGAL (TELEFONE): No máximo :max caracteres',

            'parents_email.required' => 'E-MAIL DOS PAIS/RESPONÁVEL: Obrigatório',
            'parents_email.email' => 'E-MAIL DOS PAIS/RESPONÁVEL: E-mail inválido',
        ];
    }
}
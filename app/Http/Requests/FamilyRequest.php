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
        // Verifica se o usuário está autenticado
        if (!auth()->check()) {
            return false;
        }

        if (session()->has('step1') && session()->has('step2') && session()->has('step3') && session()->has('step4')) {
            return true;
        }

        // Retorna true apenas se NÃO tiver inscrição
        return !auth()->user()->hasInscription();
    }

    public function prepareForValidation()
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            // Aplica manipulação aos valores apenas se forem strings
            if (is_string($value)) {
                $sanitized[$key] = trim($value); // Remove espaços
                $sanitized[$key] = mb_strtoupper($value, 'UTF-8');
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
            // filiação: nome da mãe/pai é obrigatório se o telefone correspondente foi informado
            'mother' => [
                'nullable',
                Rule::requiredIf(fn() => filled($this->input('mother_phone'))),
                'max:60',
                new NameRule(),
            ],
            'father' => [
                'nullable',
                Rule::requiredIf(fn() => filled($this->input('father_phone'))),
                'max:60',
                new NameRule(),
            ],

            //responsável legal (informar ou não) — obrigatório indicar respOption1 (1)
            // quando nem mãe nem pai foram informados
            'respLegalOption' => [
                'required',
                'in:1,2',
                function ($attribute, $value, $fail) {
                    $motherEmpty = blank($this->input('mother'));
                    $fatherEmpty = blank($this->input('father'));

                    if ($motherEmpty && $fatherEmpty && $value != 1) {
                        $fail('* Como nenhum dos pais foi informado, é necessário indicar um responsável legal.');
                    }
                },
            ],

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
            'mother.required' => '* Obrigatório, pois o telefone da mãe foi informado.',
            'mother.max' => '* No máximo :max caracteres',

            'father.required' => '* Obrigatório, pois o telefone do pai foi informado.',
            'father.max' => '* No máximo :max caracteres',

            'respLegalOption.required' => '* Obrigatório',
            'respLegalOption.in' => '* Opção inválida',
            'responsible.max' => '* No máximo :max caracteres',
            'responsible.required' => '* Obrigatório',

            'degree.required' => '* Obrigatório',
            'degree.in' => '* Opção inválida',

            'kinship.required' => '* Obrigatório',

            'responsible_phone.required' => '* Obrigatório',
            'responsible_phone.min' => '* No mínimo :min caracteres',
            'responsible_phone.max' => '* No máximo :max caracteres',

            'parents_email.required' => '* Obrigatório',
            'parents_email.email' => '* E-mail inválido',
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (session()->has('step1') && session()->has('step2') && session()->has('step3')) {
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

        // Remove a máscara apenas do RA escolar
        if (isset($sanitized['school_ra'])) {
            $sanitized['school_ra'] = preg_replace('/[^0-9A-Za-z]/', '', $sanitized['school_ra']);
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
            // Escolaridade
            'school_name' => ['required', 'max:60', 'regex:/^[a-zA-ZÀ-ÿ0-9 ()]*$/'], // Nome da escola
            // 'school_ra' => ['required', 'unique:user_details,school_ra', 'regex:/^\d{3}\.\d{3}\.\d{3}-[A-Za-z0-9]{1}$/'],
            'school_ra' => ['required', 'unique:user_details,school_ra'], // RA escolar
            'school_city' => ['required', 'max:30', 'regex:/^[a-zA-ZÀ-Úà-ú ]+$/'], // Cidade da escola
            'school_state' => [
                'required',
                'max:32',
                'school_state' => ['required', 'in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO'],
            ], // Estado da escola
            'school_year' => ['required', 'date_format:Y', 'before:today'], // Ano de conclusão
        ];
    }

    public function messages()
    {
        return [
            // escola
            'school_name.required' => 'Você deve informar o nome da escola',
            'school_name.max' => 'Para o nome da escola, use, no máximo, :max caracteres',
            'school_name.regex' => 'Para o nome da escola, use apenas letras, números e espaços',

            'school_ra.required' => 'O RA é obrigatório',
            'school_ra.regex' => 'O RA deve estar no formato 000.000.000-A.',
            'school_ra.unique' => 'O número de RA fornecido já foi cadastrado',

            'school_city.required' => 'Informe a cidade da escola.',
            'school_city.max' => 'Para a cidade da escola, use, no máximo :max caracteres',
            'school_city.regex' => 'Para a cidade da escola, use apenas letras, números e espaços',

            'school_state.required' => 'O estado é obrigatório',
            'school_state.max' => 'Para o nome do estado, use, no máximo, :max caracteres',
            'school_state.regex' => 'Para o nome do estado, use, apenas letras, números e espaços',

            'school_year.required' => 'O campo ano de conclusão é obrigatório',
            'school_year.date_format' => 'O ano de conclusão deve estar no formato AAAA. Ex: 2023',
            'school_year.before' => 'O ano de conclusão deve ser anterior ao ano atual',
        ];
    }
}

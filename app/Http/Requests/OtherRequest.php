<?php

namespace App\Http\Requests;

use App\Rules\NisRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class OtherRequest extends FormRequest
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

        if (session()->has('step1') && session()->has('step2') && session()->has('step3') && session()->has('step4') && session()->has('step5') && session()->has('step6')) {
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
            $sanitized[$key] = trim($value); // Remove espaços
            $sanitized[$key] = mb_strtoupper($value, 'UTF-8');
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
            // outras informações
            'health' => ['required', 'in:1,2'],
            // descrição da alergia
            'health_issue' =>
            [
                'nullable',
                Rule::requiredIf(fn() => $this->input('health') == 1),
                'max:60',
                // 'regex:/^[\p{L}\p{N}\s.,\-]+$/u'
                function ($attribute, $value, $fail) {
                    if ($this->input('health') == 1) {
                        $regex = '/^[\p{L}0-9\s.,()\-]+$/u'; // Letras, números, espaço, ponto, vírgula, hífen
                        if (!preg_match($regex, $value)) {
                            $fail("* O campo {$attribute} contém caracteres inválidos.");
                        }
                    }
                }
            ],

            // programas sociais
            'social_program' => ['required', 'in:1,2'],
            // descrição do programa social
            'nis' => [
                'nullable',
                Rule::requiredIf(fn() => $this->input('social_program') == 1),
                $this->input('social_program') == 1 ? new NisRule() : null,
                'unique:users,nis'                
            ],
        ];
    }

    public function messages()
    {
        return [
            // programas sociais
            'social_program.required' => '* O campo Programas Sociais é obrigatório',
            'social_program.in' => '* O campo Programas Sociais apresenta uma opção inválida',

            'nis.required' => '* O Número de Identificação Social é obrigatório',
            'nis.min' => '* Número de Identificação Social deve conter, no mínimo, :min caracteres',
            'nis.max' => '* Número de Identificação Social deve conter, no máximo, :max caracteres',
            'nis.unique' => '* Número de Identificação Social já cadastrado',

            // outras informações
            'health.required' => '* Você deve informar se tem algum problema de saúde ou alergia.',
            'health.in' => '* Você selecionou uma opção inválida ao informar se tem algum problema de saúde ou alergia',

            'health_issue.required' => '* Você deve descrever qual problema de saúde ou alergia possui.',
            'health_issue.max' => '* Ao descrever seu problema de saúde ou alergia, use, no máximo, :max caracteres',
        ];
    }
}
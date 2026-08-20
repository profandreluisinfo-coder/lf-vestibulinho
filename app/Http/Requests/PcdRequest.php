<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PcdRequest extends FormRequest
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

        if (session()->has('step1') && session()->has('step2') && session()->has('step3') && session()->has('step4') && session()->has('step5')) {
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
        $pneRequired = $this->input('pne') == 1;
        $pneDescriptionRequired = $this->input('pne_description') == 1;

        return [
            'pne' => ['required', 'in:1,2'],

            'accessibility_description' => [
                'nullable',
                Rule::requiredIf($pneRequired),
                'max:60',
                function ($attribute, $value, $fail) {
                    if ($this->input('pne') == 1) {
                        $regex = '/^[\p{L}0-9\s.,()\-]+$/u';
                        if (!preg_match($regex, $value)) {
                            $fail("* O campo {$attribute} contém caracteres inválidos.");
                        }
                    }
                }
            ],

            // agora é select Sim/Não
            'pne_description' => [
                'nullable',
                Rule::requiredIf($pneRequired),
                'in:1,2',
            ],

            // novo campo de especificação
            'pne_description_detail' => [
                'nullable',
                Rule::requiredIf($pneDescriptionRequired),
                'max:60',
                function ($attribute, $value, $fail) {
                    if ($this->input('pne_description') == 1) {
                        $regex = '/^[\p{L}0-9\s.,()\-]+$/u';
                        if (!preg_match($regex, $value)) {
                            $fail("* O campo {$attribute} contém caracteres inválidos.");
                        }
                    }
                }
            ],

            'pne_report' => [
                Rule::requiredIf($pneRequired),
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ];
    }

    public function messages()
    {
        return [
            'pne.required' => '* Informe se possui alguma necessidades especiais',
            'pne.in' => '* O campo Acessibilidade apresenta uma opção inválida',

            'pne_report.required' => '* O campo de relatório de educação especial é obrigatório',
            'pne_report.file' => '* O campo de relatório de educação especial deve ser um arquivo PDF',
            'pne_report.max' => '* O campo de relatório de educação especial deve conter, no máximo, :max KB',
            'pne_report.mimes' => '* O campo de relatório de educação especial deve ser um arquivo do tipo: :values',

            'accessibility_description.required' => '* O campo de descrição de acessibilidade é obrigatório',
            'accessibility_description.max' => '* O campo de descrição de acessibilidade deve conter, no máximo, :max caracteres',

            'pne_description.required' => '* Informe se precisa de atendimento com recurso de acessibilidade',
            'pne_description.in' => '* Opção inválida',

            'pne_description_detail.required' => '* O campo de descrição de tipo de ajuda é obrigatório',
            'pne_description_detail.max' => '* O campo deve conter, no máximo, :max caracteres',
        ];
    }
}
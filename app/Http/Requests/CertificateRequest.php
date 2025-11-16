<?php

namespace App\Http\Requests;

use App\Models\UserDetail;
use App\Rules\CertificateRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (session()->has('step1')) {
            return true;
        }

        return false;
    }

    public function prepareForValidation()
    {
        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            if (is_string($value)) {
                // Remove espaços extras
                $value = trim($value);

                // Se for string vazia depois do trim, converte para null
                if ($value === '') {
                    $sanitized[$key] = null;
                } else {
                    // Caso contrário, aplica o strtoupper
                    $sanitized[$key] = mb_strtoupper($value, 'UTF-8');
                }
            } else {
                $sanitized[$key] = $value; // Mantém o valor original se não for string
            }
        }

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
            // Certidão de nascimento (seleção de modelo)
            'certificateModel' => ['required', 'in:1,2'],

            // certidão de nascimento modelo novo
            'new_number' => [
                'nullable',
                // 'unique:user_details,new_number',
                function ($attribute, $value, $fail) {
                    if ($value && UserDetail::where('new_number', $value)->exists()) {
                        $fail('Este número de certidão já foi cadastrado.');
                    }
                },
                'digits:32',
                new CertificateRule()
            ],

            // certidão de nascimento modelo antigo
            'fls' => [
                'nullable',
                $this->requiredIfOldCert(),
                'string',
                'min:1',
                'max:4'
            ],
            'book' => [
                'nullable',
                $this->requiredIfOldCert(),
                'string',
                'max:10',
                // Padrão esperado: Letras e/ou números, de 1 a 10 caracteres (ex: A12, B001, ABC1234567)
                'regex:/^[A-Za-z0-9]{1,10}$/'
            ],
            'old_number' => [
                'nullable',
                $this->requiredIfOldCert(),
                // 'unique:user_details,old_number',
                function ($attribute, $value, $fail) {
                    if ($value && UserDetail::where('old_number', $value)->exists()) {
                        $fail('Este número de certidão já foi cadastrado.');
                    }
                },
                'string',
                'min:1',
                'max:6',
            ],
            'municipality' => [
                'nullable',
                $this->requiredIfOldCert(),
                'max:45',
                'regex:/^[a-zA-ZÀ-Úà-ú ]+$/'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            // Certidão de nascimento (seleção de modelo)
            'certificateModel' => 'O modelo de certidão de nascimento é obrigatório',
            'certificateModel.in' => 'O modelo de certidão de nascimento selecionado é uma opção inválida',

            // certidão de nascimento modelo novo
            'new_number.required' => 'O número de certidão de nascimento é obrigatório',
            'new_number.unique' => 'O número de certidão de nascimento informado já foi cadastrado',
            'new_number.digits' => 'O número de certidão de nascimento deve conter 32 caracteres',

            // certidão de nascimento modelo antigo
            'fls.required' => 'Você deve informar o número da(s) folha(s) da sua certidão de nascimento',
            'fls.min' => 'Para o número da(s) folha(s) da sua certidão de nascimento, use, no mínimo, :min caracteres',
            'fls.max' => 'Para o número da(s) folha(s) da sua certidão de nascimento, use, no máximo, :max caracteres',

            'book.required' => 'O número do livro de registro da sua certidão de nascimento é obrigatório',
            'book.min' => 'Para o número do livro de registro da sua certidão de nascimento, use, no mínimo, :min caracteres',
            'book.max' => 'Para o número do livro de registro da sua certidão de nascimento, use, no máximo, :max caracteres',
            'book.regex' => 'O número do livro de registro da sua certidão de nascimento deve conter apenas letras e números',

            'old_number.required' => 'É obrigatório informar o número/termo da sua certidão de nascimento',
            'old_number.unique' => 'O número/termo da certidão de nascimento informado já foi cadastrado',
            'old_number.min' => 'Para o número/termo da sua certidão de nascimento, use, no mínimo, :min caracteres',
            'old_number.max' => 'Para o número/termo da sua certidão de nascimento, use, no máximo, :max caracteres',

            'municipality.required' => 'É obrigatório informar o municipio de nascimento',
            'municipality.max' => 'Para o municipio de nascimento, use, no máximo, :max caracteres',
            'municipality.regex' => 'O municipio de nascimento deve conter apenas letras',
        ];
    }

    public function attributes()
    {
        return [
            'accessibility_description' => 'descrição de acessibilidade',
            'allergy_description' => 'descrição de alergia',
        ];
    }

    protected function requiredIfOldCert()
    {
        return Rule::requiredIf(fn() => $this->input('certificateModel') == 2);
    }
}

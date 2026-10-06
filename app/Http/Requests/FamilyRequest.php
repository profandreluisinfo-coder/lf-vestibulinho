<?php

namespace App\Http\Requests;

use App\Rules\NameRule;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class FamilyRequest extends FormRequest
{
    /** ID do grau de parentesco "OUTRO" (tabela degrees). */
    private const OTHER_DEGREE_ID = '8';

    /** Domínios de e-mail inválidos (mesma lista do family.js do cliente). */
    private const INVALID_EMAIL_DOMAINS = [
        '@gmail.com.br', '@test.com', '@fakeemail.com', '@invalid.com',
        '@example.com', '@example.com.br', '@email.com', '@email.com.br',
        '@educacaosumare.com', '@hotmail.com.br', '@outlook.com.br',
    ];

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

    /**
     * Normaliza os dados ANTES de validar:
     *  1) trim + caixa alta (e-mails ficam em caixa baixa);
     *  2) descarta valores "fantasmas" do responsável legal quando a opção
     *     não é "Sim" (campos que o cliente apenas ocultou);
     *  3) descarta 'kinship' quando o grau de parentesco não é "OUTRO".
     */
    protected function prepareForValidation(): void
    {
        $emailFields = ['parents_email', 'parents_email_confirmation'];
        $ignored     = ['_token', '_method'];

        $sanitized = [];

        foreach ($this->all() as $key => $value) {
            if (in_array($key, $ignored, true)) {
                continue;
            }

            if (is_string($value)) {
                $value = trim($value);
                $value = in_array($key, $emailFields, true)
                    ? mb_strtolower($value, 'UTF-8')
                    : mb_strtoupper($value, 'UTF-8');
            }

            $sanitized[$key] = $value;
        }

        // 2) Sem responsável legal: zera todo o bloco
        if (($sanitized['respLegalOption'] ?? null) !== '1') {
            $sanitized['responsible']       = null;
            $sanitized['responsible_phone'] = null;
            $sanitized['degree_id']         = null;
            $sanitized['kinship']           = null;
        }

        // 3) 'kinship' só existe quando degree_id = OUTRO
        if ((string) ($sanitized['degree_id'] ?? '') !== self::OTHER_DEGREE_ID) {
            $sanitized['kinship'] = null;
        }

        $this->merge($sanitized);
    }

    /**
     * O usuário optou por informar um responsável legal?
     */
    private function hasLegalResponsible(): bool
    {
        return (string) $this->input('respLegalOption') === '1';
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

            // responsável legal (informar ou não) — obrigatório informar (1)
            // quando nem mãe nem pai foram informados
            'respLegalOption' => [
                'required',
                'in:1,2',
                function ($attribute, $value, $fail) {
                    $motherEmpty = blank($this->input('mother'));
                    $fatherEmpty = blank($this->input('father'));

                    if ($motherEmpty && $fatherEmpty && (string) $value !== '1') {
                        $fail('* Como nenhum dos pais foi informado, é necessário indicar um responsável legal.');
                    }
                },
            ],

            // nome do responsável legal
            // (se respLegalOption != 1, prepareForValidation() já o transformou em null)
            'responsible' => [
                'nullable',
                Rule::requiredIf(fn() => $this->hasLegalResponsible()),
                'max:60',
                new NameRule(),
            ],

            // grau de parentesco
            'degree_id' => [
                'nullable',
                Rule::requiredIf(fn() => $this->hasLegalResponsible()),
                'integer',
                'exists:degrees,id',
            ],

            // descrição do grau de parentesco (somente quando "OUTRO")
            'kinship' => [
                'nullable',
                Rule::requiredIf(fn() => $this->hasLegalResponsible()
                    && (string) $this->input('degree_id') === self::OTHER_DEGREE_ID),
                'string',
                'max:45',
                'regex:/^[a-zA-ZÀ-ÿ ()]*$/u', // mesmo padrão do cliente
            ],

            // telefone dos pais ou responsável legal
            'mother_phone' => ['nullable'],
            'father_phone' => ['nullable'],

            'responsible_phone' => [
                'nullable',
                Rule::requiredIf(fn() => $this->hasLegalResponsible()),
            ],

            // e-mail dos pais ou responsável legal
            'parents_email' => [
                'required',
                'email',
                'confirmed',
                function ($attribute, $value, $fail) {
                    $email = mb_strtolower(trim((string) $value), 'UTF-8');

                    foreach (self::INVALID_EMAIL_DOMAINS as $domain) {
                        if (str_ends_with($email, $domain)) {
                            $fail('* O domínio de e-mail informado é inválido.');
                            return;
                        }
                    }
                },
            ],
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

            'degree_id.required' => '* Obrigatório',
            'degree_id.integer' => '* O grau de parentesco selecionado é inválido.',
            'degree_id.exists' => '* O grau de parentesco selecionado é inválido.',

            // Rule::requiredIf() é convertida em "required", por isso a chave é kinship.required
            'kinship.required' => '* Descreva o grau de parentesco quando selecionar "Outro".',
            'kinship.max' => '* No máximo :max caracteres',
            'kinship.regex' => '* Apenas letras, acentos e espaços.',

            'responsible_phone.required' => '* Obrigatório',

            'parents_email.required' => '* Obrigatório',
            'parents_email.email' => '* E-mail inválido',
            'parents_email.confirmed' => '* Os e-mails não coincidem.',
        ];
    }
}
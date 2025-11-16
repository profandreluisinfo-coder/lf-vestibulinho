<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CertificateRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }
        // Remove espaços ou outros caracteres que não sejam dígitos
        $value = preg_replace('/\D/', '', $value);

        // Verifica se tem exatamente 32 dígitos numéricos
        if (strlen($value) !== 32) {
            $fail("O campo {$attribute} deve conter exatamente 32 dígitos numéricos.");
            return;
        }

        // Verifica se contém apenas dígitos
        if (!preg_match('/^\d+$/', $value)) {
            $fail("O campo {$attribute} deve conter apenas dígitos numéricos.");
            return;
        }
    }
}
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CpfRule implements ValidationRule
{
    /**
     * Executa a validação da regra CPF.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Garante que o CPF tem exatamente 11 números
        if (!preg_match('/^\d{11}$/', $value)) {
            $fail('O CPF informado deve conter exatamente 11 dígitos numéricos.');
            return;
        }

        // Rejeita CPFs com todos os dígitos iguais
        if (preg_match('/^(\d)\1{10}$/', $value)) {
            $fail('O CPF informado não é válido.');
            return;
        }

        // Validação dos dois dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $sum = 0;
            for ($c = 0; $c < $t; $c++) {
                $sum += (int) $value[$c] * (($t + 1) - $c);
            }

            $digit = ((10 * $sum) % 11) % 10;

            if ((int) $value[$t] !== $digit) {
                $fail('O CPF informado não é válido.');
                return;
            }
        }
    }
}
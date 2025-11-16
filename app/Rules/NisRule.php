<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NisRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $nis = preg_replace('/[^0-9]/', '', (string) $value);

        if (strlen($nis) != 11 || preg_match('/^([0-9])\1{10}$/', $nis)) {
            $fail('O :attribute informado é inválido.');
            return;
        }

        $soma = 0;
        $pesos = [3, 2, 9, 8, 7, 6, 5, 4, 3, 2]; // pesos fixos para os 10 primeiros dígitos

        for ($i = 0; $i < 10; $i++) {
            $soma += $nis[$i] * $pesos[$i];
        }

        $resto = $soma % 11;
        $digitoVerificador = 11 - $resto;

        if ($digitoVerificador == 10 || $digitoVerificador == 11) {
            $digitoVerificador = 0;
        }

        if ((int)$nis[10] !== $digitoVerificador) {
            $fail('O :attribute informado é inválido.');
        }
    }
}
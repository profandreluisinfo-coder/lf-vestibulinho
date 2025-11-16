<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NameRule implements ValidationRule
{
    /**
     * Valida nomes: apenas letras, espaços e mínimo de duas palavras.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Garante que só tem letras (inclusive acentuadas) e espaços
        if (!preg_match('/^[\p{L}\s]+$/u', $value)) {
            $fail('O nome deve conter apenas letras e espaços.');
            return;
        }

        // Exige no mínimo duas palavras
        if (str_word_count($value, 0, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðñòóôõöùúûüýÿ') < 2) {
            $fail('O nome deve conter no mínimo duas palavras.');
            return;
        }

        // Rejeita nomes com mais de 2 repetições consecutivas do mesmo caractere
        if (preg_match('/(.)\1{2,}/u', $value)) {
            $fail('O nome não pode conter repetição excessiva de caracteres consecutivos.');
        }
    }
}
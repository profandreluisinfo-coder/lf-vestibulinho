<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AgeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $age = $this->calculateAge($value);

        if ($age < 14) {
            $fail('A idade deve ser maior ou igual a 14 anos.');
        }
    }

    /**
     * Recebe uma string que representa uma data de nascimento no formato "Y-m-d".
     *
     * @param string $data      A string que representa a data de nascimento
     * @return int              A idade calculada
     */
    private function calculateAge($data): int
    {
        // Cria uma instância de DateTime a partir da data de nascimento fornecida
        $birthday = new \DateTime($data);

        // Cria uma instância de DateTime representando a data atual
        $currentDate = new \DateTime();

        // Calcula a diferença entre a data atual e a data de nascimento
        $difference = $currentDate->diff($birthday);

        // Retorna a parte relativa aos anos da diferença, representando a idade
        return $difference->y;
    }
}
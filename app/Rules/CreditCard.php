<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class CreditCard implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        // Remove tudo que não for número
        $number = preg_replace('/\D/', '', $value);

        // Verifica tamanho
        if (strlen($number) < 13 || strlen($number) > 19) {
            $fail('O número do cartão de crédito deve ter entre 13 e 19 dígitos.');
            return;
        }

        // Evita sequência inválida (ex: 0000000000000)
        if (preg_match('/^(\d)\1*$/', $number)) {
            $fail('O número do cartão de crédito informado é inválido.');
            return;
        }

        // Validação pelo Algoritmo de Luhn
        if (! $this->isValidLuhn($number)) {
            $fail('O número do cartão de crédito informado é inválido.');
        }
    }

    /**
     * Valida usando o algoritmo de Luhn
     */
    private function isValidLuhn(string $number): bool
    {
        $sum = 0;
        $alt = false;

        for ($i = strlen($number) - 1; $i >= 0; $i--) {
            $n = (int) $number[$i];

            if ($alt) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }

            $sum += $n;
            $alt = !$alt;
        }

        return $sum % 10 === 0;
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCui implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if(!$this->cuiIsValid($value)){
            $fail('El DPI es invalido');
        }
    }
    
    private function cuiIsValid($cui) {

        $cui = preg_replace('/\s+/', '', $cui);

        if (!preg_match('/^[0-9]{13}$/', $cui)) {
            return false;
        }

        $depto = (int)substr($cui, 9, 2);
        $muni = (int)substr($cui, 11, 2);
        $numero = substr($cui, 0, 8);
        $verificador = (int)substr($cui, 8, 1);

        $munisPorDepto = [ 17, 8, 16, 16, 13, 14, 19, 8, 24, 21, 9, 30, 32, 21, 8, 17, 14, 5, 11, 11, 7,17];

        if ($depto === 0 || $muni === 0 || $depto > count($munisPorDepto) || $muni > $munisPorDepto[$depto - 1]) {
            return false;
        }

        $total = 0;

        for ($i = 0; $i < strlen($numero); $i++) {
            $total += ($numero[$i] * ($i + 2));
        }

        $modulo = $total % 11;

        return $modulo === $verificador;
    }
}

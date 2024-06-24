<?php

namespace App\Rules;

use App\Utils\Card;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CardNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Card::isValid($value)) {
            $fail('invalid_card_number')->translate();
        }
    }
}

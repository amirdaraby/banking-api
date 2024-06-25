<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CardToCardAmount implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $minValue = config("banking.card_to_card.min_amount");
        $maxValue = config("banking.card_to_card.max_amount");

        if ($value < $minValue || $value > $maxValue) {
            $fail(__('validation.between.numeric', ['attribute' => $attribute, 'min' => $minValue, 'max' => $maxValue]));
        }
    }
}

<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CardToCardAmount implements ValidationRule
{
    private const CONFIG_PATH = "banking.card_to_card";
    
    public function __construct(
        private ?float $minAmount = null,
        private ?float $maxAmount = null
    ) {
        $this->minAmount ??= config(self::CONFIG_PATH . ".min_amount");
        $this->maxAmount ??= config(self::CONFIG_PATH . ".max_amount");
    }

    /**
     * Validate the card-to-card transaction amount
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isWithinValidRange($value)) {
            $fail($this->getErrorMessage($attribute));
        }
    }

    private function isWithinValidRange(mixed $value): bool
    {
        $numericValue = (float) $value;
        return $numericValue >= $this->minAmount && $numericValue <= $this->maxAmount;
    }

    private function getErrorMessage(string $attribute): string
    {
        return __('validation.between.numeric', [
            'attribute' => $attribute,
            'min' => $this->minAmount,
            'max' => $this->maxAmount
        ]);
    }
}

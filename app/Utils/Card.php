<?php

namespace App\Utils;

class Card
{
    private const CARD_NUMBER_PATTERN = '/^\d{16}$/';

    public static function isValid(string|int $value): bool
    {
        if (!self::hasValidFormat($value)) {
            return false;
        }

        return self::passesLuhnAlgorithm((string)$value);
    }

    private static function hasValidFormat(string|int $value): bool
    {
        return preg_match(self::CARD_NUMBER_PATTERN, (string)$value) === 1;
    }

    private static function passesLuhnAlgorithm(string $cardNumber): bool
    {
        $sum = 0;

        for ($position = 0; $position < 16; $position++) {
            $digit = (int)$cardNumber[$position];
            $sum += self::processDigit($digit, $position);
        }

        return $sum % 10 === 0;
    }

    private static function processDigit(int $digit, int $position): int
    {
        // Double every odd position (0-indexed)
        if ($position % 2 === 0) {
            $digit *= 2;
        }

        // Subtract 9 if greater than 9
        return $digit > 9 ? $digit - 9 : $digit;
    }
}

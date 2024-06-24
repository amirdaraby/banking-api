<?php

namespace App\Utils;

class Card
{
    public static function isValid(string|int $value): bool
    {
        if (!preg_match('/^\d{16}$/', $value)) {
            return false;
        }

        $sum = 0;

        for ($i = 1; $i <= 16; $i++) {

            $digit = (int)$value[$i - 1];

            if ($i % 2 !== 0) {
                $digit *= 2;
            }

            if ($digit > 9) {
                $digit -= 9;
            }

            $sum += $digit;
        }

        return $sum % 10 === 0;
    }
}

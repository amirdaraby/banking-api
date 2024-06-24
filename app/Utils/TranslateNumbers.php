<?php

namespace App\Utils;

class TranslateNumbers
{
    const PERSIAN_NUMBERS = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    const ARABIC_NUMBERS = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    const ENGLISH_NUMBERS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];


    public static function toEnglish(string $haystack): string
    {
        return str_replace(self::ARABIC_NUMBERS, self::ENGLISH_NUMBERS, str_replace(self::PERSIAN_NUMBERS, self::ENGLISH_NUMBERS, $haystack));
    }

    public static function toPersian(string $haystack): string
    {
        return str_replace(self::ENGLISH_NUMBERS, self::PERSIAN_NUMBERS, $haystack);
    }
}

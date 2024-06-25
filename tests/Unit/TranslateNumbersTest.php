<?php

namespace Tests\Unit;

use App\Utils\TranslateNumbers;
use PHPUnit\Framework\TestCase;


class TranslateNumbersTest extends TestCase
{
    public function testToEnglishMethodWhenHaystackIsEmptyString(): void
    {
        $haystack = '';

        $result = TranslateNumbers::toEnglish($haystack);

        $this->assertEquals($haystack, $result);
    }

    public function testToEnglishMethodWhenHaystackIsPersianNumbers(): void
    {
        $persianNumbers = TranslateNumbers::PERSIAN_NUMBERS;

        $haystack = implode('', $persianNumbers);
        $expected = implode('', range(0, 9));

        $result = TranslateNumbers::toEnglish($haystack);

        $this->assertEquals($expected, $result);
    }

    public function testToEnglishMethodWhenHaystackIsEnglishNumbers(): void
    {
        $englishNumbers = TranslateNumbers::ENGLISH_NUMBERS;

        $haystack = implode('', $englishNumbers);
        $expected = implode('', range(0, 9));

        $result = TranslateNumbers::toEnglish($haystack);

        $this->assertEquals($expected, $result);
    }

    public function testToEnglishMethodWhenHaystackIsArabicNumbers(): void
    {
        $arabicNumbers = TranslateNumbers::ARABIC_NUMBERS;

        $haystack = implode('', $arabicNumbers);
        $expected = implode('', range(0, 9));

        $result = TranslateNumbers::toEnglish($haystack);

        $this->assertEquals($expected, $result);
    }

    public function testToPersianMethodWhenHaystackIsEmptyString(): void
    {
        $haystack = '';

        $result = TranslateNumbers::toPersian($haystack);

        $this->assertEquals($haystack, $result);
    }


    public function testToPersianMethodWhenHaystackIsPersianNumbers(): void
    {
        $persianNumbers = TranslateNumbers::PERSIAN_NUMBERS;
        $haystack = implode('', $persianNumbers);

        $expected = implode('', $persianNumbers);

        $result = TranslateNumbers::toPersian($haystack);

        $this->assertEquals($expected, $result);
    }

    public function testToPersianMethodWhenHaystackIsEnglishNumbers(): void
    {
        $englishNumbers = TranslateNumbers::ENGLISH_NUMBERS;
        $persianNumbers = TranslateNumbers::PERSIAN_NUMBERS;

        $haystack = implode('', $englishNumbers);

        $expected = implode('', $persianNumbers);

        $result = TranslateNumbers::toPersian($haystack);

        $this->assertEquals($expected, $result);
    }

    public function testToPersianMethodWhenHaystackIsArabicNumbers(): void
    {
        $persianNumbers = TranslateNumbers::PERSIAN_NUMBERS;
        $arabicNumbers = TranslateNumbers::ARABIC_NUMBERS;

        $haystack = implode('', $arabicNumbers);

        $expected = implode('', $persianNumbers);

        $result = TranslateNumbers::toPersian($haystack);

        $this->assertEquals($expected, $result);
    }
}

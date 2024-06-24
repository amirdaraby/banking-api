<?php

namespace App\Traits;

use App\Utils\TranslateNumbers;

trait PrepareCardInformation
{
    public function prepareCardNumerics(array $keysToCheck): void
    {
        foreach ($keysToCheck as $key) {
            $this->whenFilled($key, fn() => $this->merge([$key => TranslateNumbers::toEnglish($this->get($key))]));
        }
    }
}

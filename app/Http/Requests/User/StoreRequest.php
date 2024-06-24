<?php

namespace App\Http\Requests\User;

use App\Rules\Number;
use App\Utils\TranslateNumbers;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "between:3,255"],
            "phone_number" => ["required", "string", new Number(), "unique:users,phone_number"]
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'phone_number' => TranslateNumbers::toEnglish($this->phone_number),
        ]);
    }
}

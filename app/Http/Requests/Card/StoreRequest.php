<?php

namespace App\Http\Requests\Card;

use App\Rules\CardNumber;
use App\Traits\PrepareCardInformation;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use PrepareCardInformation;

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
            'account_id' => ['required', 'int', 'exists:accounts,id'],
            'number' => ['required', 'string', 'size:16', 'unique:cards,number', new CardNumber()],
            'expiration_year' => ['required', 'int', 'between:2000,3000'],
            'expiration_month' => ['required', 'int', 'between:1,12'],
            'cvv2' => ['required', 'string', 'between:3,4'],
            'password' => ['required', 'string', 'min:4'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareCardNumerics(['number', 'expiration_year', 'expiration_month', 'cvv2', 'password']);
    }
}

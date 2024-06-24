<?php

namespace App\Http\Requests\Card;

use App\Rules\CardNumber;
use App\Traits\PrepareCardInformation;
use App\Utils\TranslateNumbers;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'account_id' => ['int', 'exists:accounts,id'],
            'number' => ['string', 'size:16', Rule::unique('cards')->ignore($this->route('id')), new CardNumber()],
            'expiration_year' => ['int', 'between:2000,3000'],
            'expiration_month' => ['int', 'between:1,12'],
            'cvv2' => ['string', 'between:3,4'],
            'password' => ['string', 'min:4'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareCardNumerics(['number', 'expiration_year', 'expiration_month', 'cvv2', 'password']);
    }
}

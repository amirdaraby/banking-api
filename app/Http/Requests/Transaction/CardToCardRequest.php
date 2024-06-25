<?php

namespace App\Http\Requests\Transaction;

use App\Rules\CardNumber;
use App\Rules\CardToCardAmount;
use App\Traits\PrepareCardInformation;
use Illuminate\Foundation\Http\FormRequest;

class CardToCardRequest extends FormRequest
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
            "source_card" => ["required", "string", new CardNumber()],
            "destination_card" => ["required", "string", new CardNumber()],
            "amount" => ["required", "int", new CardToCardAmount()],
        ];
    }

    protected function prepareForValidation()
    {
        $this->prepareCardNumerics(['from_card_number', 'to_card_number', 'amount']);
    }
}

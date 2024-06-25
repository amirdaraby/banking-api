<?php

namespace App\Http\Requests\Account;

use App\Rules\Number;
use App\Traits\PrepareCardInformation;
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
            'user_id' => ['int', 'exists:users,id'],
            'balance' => ['int'],
            'number' => ['string', new Number(), Rule::unique('accounts', 'number')->ignore($this->route('id'))],
        ];
    }

    protected function prepareForValidation()
    {
        $this->prepareCardNumerics(['balance', 'number']);
    }
}

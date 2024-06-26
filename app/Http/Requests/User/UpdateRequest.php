<?php

namespace App\Http\Requests\User;

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
            'name' => ['string', 'between:3,255'],
            'phone_number' => ['string', Rule::unique('users')->ignore($this->route('id'))],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareCardNumerics(['phone_number']);
    }
}

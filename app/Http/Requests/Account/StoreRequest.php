<?php

namespace App\Http\Requests\Account;

use App\Traits\PrepareCardInformation;
use App\Utils\TranslateNumbers;
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
            'user_id' => ['required', 'int', 'exists:users,id'],
            'number' => ['required', 'string', 'size:16', 'unique:accounts,number'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareCardNumerics(['number']);
    }
}

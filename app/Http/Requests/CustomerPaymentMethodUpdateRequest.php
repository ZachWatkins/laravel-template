<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPaymentMethodUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'card_name' => ['required', 'string', 'max:100'],
            'card_number' => ['required', 'string', 'max:20'],
            'card_expiration' => ['required', 'string', 'max:5'],
            'street_1' => ['required', 'string', 'max:100'],
            'street_2' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'zip_code' => ['required', 'string', 'max:10'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
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
            'phone' => ['required', 'string', 'max:20'],
            'shipping_street_1' => ['required', 'string', 'max:100'],
            'shipping_street_2' => ['required', 'string', 'max:100'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_state' => ['required', 'string', 'max:100'],
            'shipping_zip_code' => ['required', 'string', 'max:10'],
            'shipping_instructions' => ['required', 'string', 'max:500'],
            'billing_street_1' => ['required', 'string', 'max:100'],
            'billing_street_2' => ['required', 'string', 'max:100'],
            'billing_city' => ['required', 'string', 'max:100'],
            'billing_state' => ['required', 'string', 'max:100'],
            'billing_zip_code' => ['required', 'string', 'max:10'],
            'billing_card_name' => ['required', 'string', 'max:100'],
            'billing_card_number' => ['required', 'string', 'max:20'],
            'billing_card_expiration' => ['required', 'string', 'max:5'],
            'billing_card_cvv' => ['required', 'string', 'max:3'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
        ];
    }
}

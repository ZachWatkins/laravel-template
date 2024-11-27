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
            'birthday' => ['nullable', 'date'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'subscribed_to_newsletter' => ['required'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'shipping_address_id' => ['required', 'integer', 'exists:shipping_addresses,id'],
            'billing_address_id' => ['required', 'integer', 'exists:billing_addresses,id'],
        ];
    }
}

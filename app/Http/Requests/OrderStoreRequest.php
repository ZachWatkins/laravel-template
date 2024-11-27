<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
            'number' => ['required', 'integer'],
            'payment_state' => ['required', 'in:pending,paid,partially_refunded,refunded'],
            'shipping_state' => ['required', 'in:pending,shipped,delivered,returned'],
            'items_total' => ['required', 'integer'],
            'total' => ['required', 'numeric', 'between:-99999999.99,99999999.99'],
            'token_value' => ['nullable', 'string', 'max:255'],
            'customer_ip' => ['nullable', 'string', 'max:255'],
            'created_by_guest' => ['required'],
            'notes' => ['required', 'string'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'locale_id' => ['required', 'integer', 'exists:locales,id'],
        ];
    }
}

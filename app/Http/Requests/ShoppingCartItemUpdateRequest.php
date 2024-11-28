<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShoppingCartItemUpdateRequest extends FormRequest
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
            'quantity' => ['required', 'integer'],
            'unit_price' => ['required', 'integer'],
            'shopping_cart_id' => ['required', 'integer', 'exists:shopping_carts,id'],
            'part_id' => ['required', 'integer', 'exists:parts,id'],
        ];
    }
}

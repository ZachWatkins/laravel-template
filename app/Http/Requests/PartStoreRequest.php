<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartStoreRequest extends FormRequest
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
            'status' => ['required', 'in:active,inactive'],
            'number' => ['required', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:100'],
            'sku' => ['required', 'string', 'max:100'],
            'enabled' => ['required'],
            'inventory' => ['required', 'integer'],
            'unit_price' => ['required', 'integer'],
            'weight' => ['required', 'integer'],
            'weight_unit' => ['required', 'string', 'max:10'],
            'filename' => ['required', 'string', 'max:100'],
            'published_at' => ['nullable'],
            'part_type_id' => ['required', 'integer', 'exists:part_types,id'],
            'manufacturer_id' => ['required', 'integer', 'exists:manufacturers,id'],
        ];
    }
}

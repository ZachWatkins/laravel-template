<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartWebpageUpdateRequest extends FormRequest
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
            'path' => ['required', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:100'],
            'meta_title' => ['required', 'string', 'max:100'],
            'meta_description' => ['required', 'string', 'max:255'],
            'meta_keywords' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'part_id' => ['required', 'integer', 'exists:parts,id'],
        ];
    }
}

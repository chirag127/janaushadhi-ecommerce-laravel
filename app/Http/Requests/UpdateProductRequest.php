<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'drug_code' => ['nullable', 'string', 'max:100'],
            'category_id' => ['sometimes', 'required', 'exists:pgsql.categories,id'],
            'unit_size' => ['nullable', 'string', 'max:100'],
            'mrp' => ['sometimes', 'required', 'numeric', 'min:0'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
        ];
    }
}

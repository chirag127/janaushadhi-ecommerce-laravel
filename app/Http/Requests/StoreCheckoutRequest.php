<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hasAddressId = ! empty($this->input('address_id'));

        return [
            'payment_method' => ['required', Rule::in(['cod', 'online'])],
            'address_id' => ['nullable', 'uuid'],
            'full_name' => [Rule::requiredIf(! $hasAddressId), 'nullable', 'string', 'max:255'],
            'phone' => [Rule::requiredIf(! $hasAddressId), 'nullable', 'string', 'max:20'],
            'line1' => [Rule::requiredIf(! $hasAddressId), 'nullable', 'string', 'max:255'],
            'city' => [Rule::requiredIf(! $hasAddressId), 'nullable', 'string', 'max:100'],
            'state' => [Rule::requiredIf(! $hasAddressId), 'nullable', 'string', 'max:100'],
            'pincode' => [Rule::requiredIf(! $hasAddressId), 'nullable', 'digits:6'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}

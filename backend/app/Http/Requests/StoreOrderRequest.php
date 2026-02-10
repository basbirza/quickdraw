<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'items' => 'required|array|min:1|max:50',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.size' => 'required|string|max:50',
            'items.*.quantity' => 'required|integer|min:1|max:20',

            'customer_email' => 'required|email|max:255',
            'customer_first_name' => 'required|string|max:100',
            'customer_last_name' => 'required|string|max:100',
            'customer_phone' => 'nullable|string|max:20',

            'billing_address_line1' => 'required|string|max:255',
            'billing_address_line2' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'billing_country' => 'required|string|max:2',

            'payment_method' => 'required|in:stripe', // Only Stripe is implemented
            'payment_token' => 'required|string',
        ];
    }
}

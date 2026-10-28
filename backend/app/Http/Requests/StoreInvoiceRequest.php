<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recipient_name' => ['required', 'string', 'max:150'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:5000'],

            'payment_method_id' => [
                'required',
                'integer',
                Rule::exists('payment_methods', 'id')
                    ->where('is_active', true),
            ],

            'expires_at' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:' . now()->addDays(30)->toDateString(),
            ],

            'items' => ['required', 'array', 'min:1', 'max:100'],

            'items.*.product_name' => [
                'required',
                'string',
                'max:255',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:1000000',
            ],

            'items.*.unit_price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
                'max:9999999999999.99',
            ],
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'min:0'],
            'description' => ['required', 'string'],
            'is_active' => ['nullable', 'string'],
            'cycle' => ['nullable', 'string'],
            'is_best_seller' => ['nullable', 'string'],
            'billing_type' => ['nullable', 'string'],
            'allowed_billing_types' => ['required', 'array', 'min:1'],
            'allowed_billing_types.*' => ['string', 'in:CREDIT_CARD,PIX,BOLETO'],
            'free_for_days' => ['integer'],
            'packages' => ['required', 'array']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}

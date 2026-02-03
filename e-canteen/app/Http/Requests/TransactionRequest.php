<?php

namespace App\Http\Requests;

class TransactionRequest extends ApiRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => 'required|in:cash,digital',
            'method_code' => 'required_if:payment_method,digital',
            'menus' => 'required|array',
            'menus.*.menu_id' => 'required|exists:menus,id',
            'menus.*.quantity' => 'required|integer|min:1',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreReviewRequest extends ApiRequest
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
            'canteen_id' => [
                'required',
                'exists:canteens,id',
                Rule::unique('reviews')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })
            ],
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'canteen_id.unique' => 'You have already reviewed this canteen.'
        ];
    }
}

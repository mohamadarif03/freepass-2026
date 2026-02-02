<?php

namespace App\Http\Requests;

use App\Helpers\ResponseHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

abstract class ApiRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    abstract public function rules(): array;



    protected function failedValidation(Validator $validator): void
    {
        $errors = $validator->errors()->messages();
        ResponseHelper::error($errors, "Form validation errors", ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
    }


    protected function failedAuthorization(): void
    {
        ResponseHelper::error(null, "Form validation errors", ResponseCode::HTTP_UNAUTHORIZED);
    }
}

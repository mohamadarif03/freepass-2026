<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Interfaces\AuthInterface;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Helpers\ResponseHelper;
use Illuminate\Http\Response as ResponseCode;

class RegisterController extends Controller
{
    private AuthInterface $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['role'] = RoleEnum::USER->value;
        $user = $this->auth->store($data);
        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], "User registered successfully", ResponseCode::HTTP_CREATED);
    }
}

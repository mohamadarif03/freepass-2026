<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return ResponseHelper::error(null, "Invalid credentials", Response::HTTP_UNAUTHORIZED);
        }

        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ResponseHelper::success([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], "User logged in successfully", Response::HTTP_OK);
    }
}

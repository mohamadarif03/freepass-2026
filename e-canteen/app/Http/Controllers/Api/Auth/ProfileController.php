<?php

namespace App\Http\Controllers\Api\Auth;

use App\Contracts\Interfaces\AuthInterface;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    private AuthInterface $auth;

    public function __construct(AuthInterface $auth)
    {
        $this->auth = $auth;
    }

    public function index()
    {
        return ResponseHelper::success(AuthResource::make(Auth::user()), "User profile", Response::HTTP_OK);
    }

    public function update(ProfileRequest $request) {
        $this->auth->update(Auth::user()->id, $request->validated());
        return ResponseHelper::success(AuthResource::make(Auth::user()), "User profile updated", Response::HTTP_OK);
    }
}

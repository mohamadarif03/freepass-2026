<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated. Please login first.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($request->user()->role !== RoleEnum::USER->value) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. User only.',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanteenMiddleware
{
   
    public function handle(Request $request, Closure $next): Response
    {
       
        if (!$request->user()) {
            return ResponseHelper::error('Unauthenticated. Please login first.', Response::HTTP_UNAUTHORIZED);
        }

        if ($request->user()->role !== RoleEnum::CANTEEN->value) {
            return ResponseHelper::error('Access denied. Canteen only.', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

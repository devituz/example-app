<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;

class BearerAdminTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // HTTP so'rovnoma sarlavhasidan Bearer tokenni olish
        $token = $request->bearerToken();

        // Agar token bo'sh yoki Device modelida shu token bilan biriktirilgan qurilma mavjud emas bo'lsa
        if (!$token || !Admin::where('token', $token)->exists()) {
            // 401 Unauthorized xatolik qaytarish
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check for all HTTP methods (GET, POST, PUT, DELETE)
        if (in_array($request->method(), ['GET', 'POST', 'PUT', 'DELETE'])) {
            // Keyingi Middleware yoki Controller o'rtasiga so'rovnoma yuborish
            return $next($request);
        }

        // If method is not supported return error (optional)
        // return response()->json(['error' => 'Method not allowed'], 405);

        // By default, return next request (applicable for exotic methods)
        return $next($request);
    }
}

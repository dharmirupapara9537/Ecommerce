<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

          if (!$user->roles()->where('name', 'admin')->exists()) {
            return response()->json(['message' => 'Admins only'], 403);
        }

        return $next($request);
    }
}

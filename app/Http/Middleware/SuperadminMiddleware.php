<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'super admin') {
            return response()->json([
                'message' => 'Akses ditolak. Hanya Super Admin yang dapat mengakses fitur ini.'
            ], 403);
        }

        return $next($request);
    }
}
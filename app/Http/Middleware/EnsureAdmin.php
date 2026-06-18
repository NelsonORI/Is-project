<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the admin guard is authenticated
        if (!Auth::guard('admin')->check()) {

            // If a student is trying to access admin routes
            if (Auth::guard('student')->check()) {
                abort(403, 'Access denied. Admins only.');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
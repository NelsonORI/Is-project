<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStudentIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $student = Auth::guard('student')->user();

        // Not logged in as a student
        if (!$student) {
            return redirect()->route('login');
        }

        // Account is suspended
        if ($student->status === 'suspended') {
            Auth::guard('student')->logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Please contact support.',
            ]);
        }

        // Account is still pending (not verified)
        if ($student->status === 'pending') {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
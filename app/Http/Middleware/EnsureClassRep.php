<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureClassRep
{
    public function handle(Request $request, Closure $next)
    {
        $student = Auth::guard('student')->user();

        // Not logged in as a student
        if (!$student) {
            return redirect()->route('login');
        }

        // Logged in but not a class rep
        if ($student->role !== 'class_rep') {
            abort(403, 'Access denied. Class Representatives only.');
        }

        // Make sure the class rep record actually exists and is approved
        if (!$student->classRep || !$student->classRep->approved) {
            abort(403, 'Your Class Representative status is not yet approved.');
        }

        return $next($request);
    }
}
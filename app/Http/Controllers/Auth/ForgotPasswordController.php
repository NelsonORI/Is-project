<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // Show forgot password form
    public function show()
    {
        return view('auth.forgot-password');
    }

    // Handle forgot password form submission
    public function store(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/@strathmore\.edu$/',
            ],
        ], [
            'email.regex' => 'Only @strathmore.edu email addresses are accepted.',
        ]);

        $status = Password::broker('students')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'A password reset link has been sent to your email address.');
        }

        return back()->withErrors([
            'email' => 'We could not find a student account with that email address.',
        ]);
    }
}
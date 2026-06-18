<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    // Show the "please verify your email" notice
    public function notice()
    {
        $student = auth()->guard('student')->user();

        if ($student && $student->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }

        return view('auth.verify-email');
    }

    // Handle the verification link click
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user('student')->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }

        if ($request->user('student')->markEmailAsVerified()) {
            // Update status to active on verification
            $request->user('student')->update(['status' => 'active']);

            event(new Verified($request->user('student')));
        }

        return redirect()->route('student.dashboard')->with('verified', true);
    }

    // Resend the verification email
    public function resend(Request $request)
    {
        $student = auth()->guard('student')->user();

        if ($student->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }

        $student->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
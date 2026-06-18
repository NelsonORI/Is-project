<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Show the login form
    public function show()
    {
        return view('auth.login');
    }

    // Handle login form submission
    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First check if the credentials match an admin
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && Hash::check($request->password, $admin->password_hash)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // Then check if the credentials match a student
        $student = Student::where('email', $request->email)->first();

        if ($student && Hash::check($request->password, $student->password_hash)) {

            // Block suspended students
            if ($student->status === 'suspended') {
                return back()->withErrors([
                    'email' => 'Your account has been suspended. Please contact support.',
                ]);
            }

            // Block unverified students
            if ($student->status === 'pending') {
                Auth::guard('student')->login($student);
                return redirect()->route('verification.notice');
            }

            Auth::guard('student')->login($student);
            $request->session()->regenerate();

            // Redirect based on role
            if ($student->role === 'class_rep') {
                return redirect()->intended(route('classrep.dashboard'));
            }

            return redirect()->intended(route('student.dashboard'));
        }

        // Neither matched
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }
}
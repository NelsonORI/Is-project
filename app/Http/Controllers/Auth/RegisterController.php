<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Show the registration form
    public function show()
    {
        return view('auth.register');
    }

    // Handle registration form submission
    public function store(Request $request)
    {
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:students,email',
                'regex:/@strathmore\.edu$/'
            ],
            'password'       => ['required', 'confirmed', Password::min(8)],
            'student_number' => ['required', 'string', 'unique:students,student_number'],
            'school'         => ['required', 'string', 'max:255'],
            'programme'      => ['required', 'string', 'max:255'],
            'year_of_study'  => ['required', 'integer', 'min:1', 'max:6'],
        ], [
            'email.regex' => 'Only @strathmore.edu email addresses are allowed.',
        ]);

        $student = Student::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password_hash'  => Hash::make($request->password),
            'role'           => 'student',
            'student_number' => $request->student_number,
            'school'         => $request->school,
            'programme'      => $request->programme,
            'year_of_study'  => $request->year_of_study,
            'status'         => 'pending',
        ]);

        // Fire the Registered event — triggers email verification
        event(new Registered($student));

        // Log the student in immediately after registration
        Auth::guard('student')->login($student);

        return redirect()->route('verification.notice');
    }
}
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Student\StudentDashboardController;

/*
|--------------------------------------------------------------------------
| Guest Routes — only accessible when not logged in
|--------------------------------------------------------------------------
*/

Route::middleware('guest:student')->group(function () {

    // Registration
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Login
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

});

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:student')->group(function () {

    // Show "please verify your email" page
    Route::get('/email/verify', [VerificationController::class, 'notice'])
        ->name('verification.notice');

    // Handle verification link click
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    // Resend verification email
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LogoutController::class, 'store'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes — must be logged in and active
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:student', 'student.active'])->group(function () {

    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');

});

/*
|--------------------------------------------------------------------------
| Class Rep Routes — must be active student AND approved class rep
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:student', 'student.active', 'class.rep'])->group(function () {

    Route::get('/classrep/dashboard', function () {
        return view('classrep.dashboard');
    })->name('classrep.dashboard');

    Route::get('/classrep/upload', function () {
        return view('classrep.upload');
    })->name('classrep.upload');

});

/*
|--------------------------------------------------------------------------
| Admin Routes — must be logged in as admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin', 'admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

});

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});
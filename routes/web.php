<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Student\StudentDashboardController;
use App\Http\Controllers\ClassRep\ClassRepUploadController;
use App\Http\Controllers\Student\StudyController;
use App\Http\Controllers\ClassRep\ClassRepDashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminDocumentController;
use App\Http\Controllers\Admin\AdminGapReportController;

/*
|--------------------------------------------------------------------------
| Root redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes — only accessible when not logged in
|--------------------------------------------------------------------------
*/

Route::middleware('guest:student')->group(function () {

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);

});

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:student')->group(function () {

    Route::get('/email/verify', [VerificationController::class, 'notice'])
        ->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LogoutController::class, 'store'])->name('logout');

/*
|--------------------------------------------------------------------------
| Student Routes — must be logged in and active
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:student', 'student.active'])->group(function () {

    Route::get('/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');

});

/*
|--------------------------------------------------------------------------
| Class Rep Routes — must be active student AND approved class rep
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:student', 'student.active', 'class.rep'])->group(function () {

    Route::get('/classrep/dashboard', [ClassRepDashboardController::class, 'index'])
        ->name('classrep.dashboard');

    Route::get('/classrep/upload',            [ClassRepUploadController::class, 'step1'])->name('classrep.upload.step1');
    Route::post('/classrep/upload',           [ClassRepUploadController::class, 'step1Store']);
    Route::get('/classrep/upload/metadata',   [ClassRepUploadController::class, 'step2'])->name('classrep.upload.step2');
    Route::post('/classrep/upload/metadata',  [ClassRepUploadController::class, 'step2Store']);
    Route::get('/classrep/upload/processing', [ClassRepUploadController::class, 'step3'])->name('classrep.upload.step3');
    Route::post('/classrep/upload/process',   [ClassRepUploadController::class, 'process'])->name('classrep.upload.process');
    Route::get('/classrep/upload/done',       [ClassRepUploadController::class, 'step4'])->name('classrep.upload.step4');

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

Route::middleware(['auth:student', 'student.active'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/study/{document}', [StudyController::class, 'show'])->name('student.study');
});

Route::middleware(['auth:admin', 'admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Class rep approvals (from dashboard)
    Route::post('/admin/classreps/{classRep}/approve', [AdminDashboardController::class, 'approveClassRep'])->name('admin.classreps.approve');
    Route::post('/admin/classreps/{classRep}/reject', [AdminDashboardController::class, 'rejectClassRep'])->name('admin.classreps.reject');

    // Users
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{student}/suspend', [AdminUserController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/admin/users/{student}/activate', [AdminUserController::class, 'activate'])->name('admin.users.activate');
    Route::post('/admin/users/{student}/revoke-classrep', [AdminUserController::class, 'revokeClassRep'])->name('admin.users.revoke-classrep');
    Route::post('/admin/users/{student}/promote-classrep', [AdminUserController::class, 'promoteToClassRep'])->name('admin.users.promote-classrep');

    // Documents
    Route::get('/admin/documents', [AdminDocumentController::class, 'index'])->name('admin.documents');
    Route::delete('/admin/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('admin.documents.destroy');

    // Class Reps page (full list, not just pending)
    Route::get('/admin/classreps', [AdminUserController::class, 'index'])->name('admin.classreps');

    // Gap Report
    Route::get('/admin/gap-report', [AdminGapReportController::class, 'index'])->name('admin.gap-report');

});
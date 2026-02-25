<?php

use app\Http\Controllers\Auth\AuthenticatedSessionController;
use app\Http\Controllers\Auth\ConfirmablePasswordController;
use app\Http\Controllers\Auth\NewPasswordController;
use app\Http\Controllers\Auth\PasswordController;
use app\Http\Controllers\Auth\PasswordResetLinkController;
use app\Http\Controllers\Auth\RegisteredUserController;
use app\Http\Controllers\Auth\EmailVerificationController;
use Illuminate\Support\Facades\Route;
use app\Http\Controllers\RoleController;
use app\Http\Controllers\TeamController;
use app\Http\Controllers\TeamLogsController;
Route::middleware('guest')->group(function () {
    // Registration routes
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Forgot password routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password routes
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Email verification routes
Route::middleware('auth')->group(function () {
    Route::get('email/verify', [EmailVerificationController::class, 'showVerificationForm'])->name('verification.notice');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'resendVerification'])->name('verification.send');

    Route::post('email/verification/resend', [EmailVerificationController::class, 'resendVerification'])->name('verification.resend');
    Route::post('/send-verification', [EmailVerificationController::class, 'sendVerification']);
});
Route::get('email/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

// Onboarding route (accessible only after registration)
Route::middleware('auth')->get('onboarding', function () {
    return view('auth.onboarding');
})->name('auth.onboarding');
Route::middleware('auth')->group(function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});



Route::middleware(['auth',])->group(function () {
    Route::get('/team', [TeamController::class, 'index'])->name('admin.team.index');
    Route::post('/team/storeOrUpdate', [TeamController::class, 'storeOrUpdate'])->name('admin.team.storeOrUpdate');
    Route::delete('/team/{user}', [TeamController::class, 'destroy'])->name('admin.team.destroy');


    Route::get('/TeamLogs', [TeamLogsController::class, 'showTeamLogs'])->name('admin.team.logs.show');    
});

/*
Route::group(['middleware' => ['role:SuperAdmin']], function () {
    Route::get('/admin', [AdminController::class, 'index']);
});
*/



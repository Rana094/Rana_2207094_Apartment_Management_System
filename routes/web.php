<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Manager\ResidentApprovalController;
use Illuminate\Support\Facades\Route;

// Public Front-Facing Routes
Route::get('/', function () {
    return view('landing');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/waiting-approval', [EmailVerificationController::class, 'notice'])->name('approval.pending');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// Authenticated Portal Entry Routes
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/resident/dashboard', [DashboardController::class, 'resident'])
        ->middleware('role:resident')
        ->name('resident.dashboard');

    Route::get('/manager/dashboard', [DashboardController::class, 'manager'])
        ->middleware('role:manager')
        ->name('manager.dashboard');

    Route::get('/security/dashboard', [DashboardController::class, 'security'])
        ->middleware('role:security')
        ->name('security.dashboard');

    Route::get('/maintenance/dashboard', [DashboardController::class, 'maintenance'])
        ->middleware('role:staff')
        ->name('maintenance.dashboard');

    Route::prefix('manager')
        ->name('manager.')
        ->middleware('role:manager')
        ->group(function () {
            Route::get('/resident-approvals', [ResidentApprovalController::class, 'index'])
                ->name('resident-approvals.index');
            Route::post('/resident-approvals/{resident}/approve', [ResidentApprovalController::class, 'approve'])
                ->name('resident-approvals.approve');
            Route::post('/resident-approvals/{resident}/reject', [ResidentApprovalController::class, 'reject'])
                ->name('resident-approvals.reject');
        });
});

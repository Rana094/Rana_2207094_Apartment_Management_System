<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Manager\ResidentApprovalController;
use App\Http\Controllers\Resident\ResidentPortalController;
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

Route::get('/dashboard-preview', function () {
    $role = request('role', 'resident');
    return view('dashboard-preview', compact('role'));
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

    Route::prefix('resident')
        ->name('resident.')
        ->middleware('role:resident')
        ->group(function () {
            Route::get('/flat', [ResidentPortalController::class, 'flat'])->name('flat');
            Route::get('/bills', [ResidentPortalController::class, 'bills'])->name('bills.index');
            Route::get('/bills/{bill}', [ResidentPortalController::class, 'bill'])->name('bills.show');
            Route::get('/bills/{bill}/upload', [ResidentPortalController::class, 'uploadPaymentProof'])->name('bills.upload');
            Route::post('/bills/{bill}/payment-proofs', [ResidentPortalController::class, 'storePaymentProof'])->name('bills.payment-proofs.store');
            Route::get('/complaints', [ResidentPortalController::class, 'complaints'])->name('complaints.index');
            Route::get('/complaints/create', [ResidentPortalController::class, 'createComplaint'])->name('complaints.create');
            Route::post('/complaints', [ResidentPortalController::class, 'storeComplaint'])->name('complaints.store');
            Route::get('/complaints/{complaint}', [ResidentPortalController::class, 'complaint'])->name('complaints.show');
            Route::get('/visitors', [ResidentPortalController::class, 'visitors'])->name('visitors.index');
            Route::get('/visitors/create', [ResidentPortalController::class, 'createVisitor'])->name('visitors.create');
            Route::post('/visitors', [ResidentPortalController::class, 'storeVisitor'])->name('visitors.store');
            Route::get('/bookings', [ResidentPortalController::class, 'bookings'])->name('bookings.index');
            Route::get('/bookings/create', [ResidentPortalController::class, 'createBooking'])->name('bookings.create');
            Route::post('/bookings', [ResidentPortalController::class, 'storeBooking'])->name('bookings.store');
            Route::get('/polls', [ResidentPortalController::class, 'polls'])->name('polls');
            Route::post('/polls/{poll}/vote', [ResidentPortalController::class, 'vote'])->name('polls.vote');
            Route::get('/emergency', [ResidentPortalController::class, 'emergency'])->name('emergency');
            Route::post('/emergency', [ResidentPortalController::class, 'storeEmergency'])->name('emergency.store');
            Route::get('/documents', [ResidentPortalController::class, 'documents'])->name('documents');
            Route::post('/documents', [ResidentPortalController::class, 'storeDocument'])->name('documents.store');
            Route::get('/move-out', [ResidentPortalController::class, 'moveOut'])->name('move-out');
            Route::post('/move-out', [ResidentPortalController::class, 'storeMoveOut'])->name('move-out.store');
            Route::get('/profile', [ResidentPortalController::class, 'profile'])->name('profile');
            Route::put('/profile', [ResidentPortalController::class, 'updateProfile'])->name('profile.update');
            Route::put('/profile/password', [ResidentPortalController::class, 'updatePassword'])->name('profile.password.update');
        });

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

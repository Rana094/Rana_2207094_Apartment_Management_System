<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\Manager\ManagerPortalController;
use App\Http\Controllers\Manager\ResidentApprovalController;
use App\Http\Controllers\Resident\ResidentPortalController;
use App\Http\Controllers\Security\SecurityPortalController;
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

    Route::get('/manager/dashboard', [ManagerPortalController::class, 'dashboard'])
        ->middleware('role:manager')
        ->name('manager.dashboard');

    Route::prefix('security')
        ->name('security.')
        ->middleware('role:security')
        ->group(function () {
            Route::get('/dashboard', [SecurityPortalController::class, 'dashboard'])->name('dashboard');
            Route::get('/checkin', [SecurityPortalController::class, 'checkin'])->name('checkin');
            Route::post('/checkin', [SecurityPortalController::class, 'storeCheckin'])->name('checkin.store');
            Route::get('/checkout', [SecurityPortalController::class, 'checkout'])->name('checkout');
            Route::post('/checkout', [SecurityPortalController::class, 'storeCheckout'])->name('checkout.store');
            Route::get('/logs', [SecurityPortalController::class, 'logs'])->name('logs');
            Route::get('/emergency', [SecurityPortalController::class, 'emergency'])->name('emergency');
            Route::post('/emergency', [SecurityPortalController::class, 'triggerEmergency'])->name('emergency.store');
            Route::get('/incidents', [SecurityPortalController::class, 'incidents'])->name('incidents');
            Route::post('/incidents', [SecurityPortalController::class, 'storeIncident'])->name('incidents.store');
        });

    Route::prefix('maintenance')
        ->name('maintenance.')
        ->middleware('role:staff')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'maintenance'])->name('dashboard');
            Route::get('/dashboard', [DashboardController::class, 'maintenance']);
            Route::get('/work-orders', function () { return view('maintenance.dashboard'); })->name('work-orders');
            Route::get('/work-orders/in-progress', function () { return view('maintenance.dashboard'); })->name('work-orders.in-progress');
            Route::get('/work-orders/completed', function () { return view('maintenance.history'); })->name('work-orders.completed');
            Route::get('/notes', function () { return view('maintenance.history'); })->name('notes');
            Route::get('/profile', function () { return view('resident.profile'); })->name('profile');
            
            Route::get('/orders/{id}', function () { return view('maintenance.show'); })->name('show');
            Route::get('/orders/{id}/update', function () { return view('maintenance.update'); })->name('update');
            Route::get('/history', function () { return view('maintenance.history'); })->name('history');
        });

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

            Route::get('/residents', [ManagerPortalController::class, 'residents'])->name('residents.index');
            Route::get('/residents/{resident}', [ManagerPortalController::class, 'resident'])->name('residents.show');
            Route::get('/flats', [ManagerPortalController::class, 'flats'])->name('flats.index');
            Route::get('/flats/create', [ManagerPortalController::class, 'createFlat'])->name('flats.create');
            Route::post('/flats', [ManagerPortalController::class, 'storeFlat'])->name('flats.store');
            Route::get('/flats/{flat}/edit', [ManagerPortalController::class, 'editFlat'])->name('flats.edit');
            Route::put('/flats/{flat}', [ManagerPortalController::class, 'updateFlat'])->name('flats.update');
            Route::get('/bills/generate', [ManagerPortalController::class, 'generateBill'])->name('bills.generate');
            Route::post('/bills/generate', [ManagerPortalController::class, 'storeBill'])->name('bills.store');
            Route::get('/bills', [ManagerPortalController::class, 'bills'])->name('bills.index');
            Route::get('/payments', [ManagerPortalController::class, 'payments'])->name('payments.index');
            Route::get('/payments/{payment}', [ManagerPortalController::class, 'payment'])->name('payments.show');
            Route::post('/payments/{paymentProof}/verify', [ManagerPortalController::class, 'verifyPayment'])->name('payments.verify');
            Route::get('/reports', [ManagerPortalController::class, 'reports'])->name('reports.financial');
            Route::get('/complaints', [ManagerPortalController::class, 'complaints'])->name('complaints.index');
            Route::get('/complaints/{complaint}/assign', [ManagerPortalController::class, 'assignComplaint'])->name('complaints.assign');
            Route::post('/complaints/{complaint}/assign', [ManagerPortalController::class, 'storeWorkOrder'])->name('complaints.work-orders.store');
            Route::get('/staff', [ManagerPortalController::class, 'staff'])->name('staff');
            Route::post('/staff', [ManagerPortalController::class, 'storeStaff'])->name('staff.store');
            Route::get('/bookings', [ManagerPortalController::class, 'bookings'])->name('bookings.index');
            Route::post('/bookings/{booking}/status', [ManagerPortalController::class, 'updateBooking'])->name('bookings.status');
            Route::get('/polls', [ManagerPortalController::class, 'polls'])->name('polls');
            Route::post('/polls', [ManagerPortalController::class, 'storePoll'])->name('polls.store');
            Route::get('/polls/create', [ManagerPortalController::class, 'polls'])->name('polls.create');
            Route::get('/emergencies', [ManagerPortalController::class, 'emergencies'])->name('emergencies.index');
            Route::post('/emergencies/{emergency}/status', [ManagerPortalController::class, 'updateEmergency'])->name('emergencies.status');
            Route::get('/notices', [ManagerPortalController::class, 'notices'])->name('notices.index');
            Route::post('/notices', [ManagerPortalController::class, 'storeNotice'])->name('notices.store');
        });
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReceptionistController;
use Illuminate\Http\Request;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Landing Page (Root URL)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingPageController::class, 'index'])->name('landing');

Route::get('/kamar', [RoomController::class, 'index'])->name('kamar.index');

// Add gallery route
Route::get('/gallery', [GaleriController::class, 'index'])->name('gallery');

Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-pemesanan', [BookingController::class, 'riwayat'])->name('bookings.riwayat');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
    Route::post('/transactions/{transaction}/pay', [TransactionController::class, 'pay'])->name('transactions.pay');
    Route::get('/transactions/{transaction}/invoice', [TransactionController::class, 'downloadInvoice'])->name('transactions.invoice');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    
    // Transaction routes
    Route::get('/transactions/status/{orderId}', [BookingController::class, 'getTransactionStatus'])->name('transactions.status');
    Route::get('/payment/finish-ajax', [BookingController::class, 'finishAjax'])->name('payment.finish-ajax');

    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('status', 'Your email has been verified!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Room selection and booking routes
    Route::get('/bookings/create/room/{room}', [BookingController::class, 'createWithRoom'])->name('bookings.create.room');
    Route::post('/bookings/check-availability', [BookingController::class, 'checkAvailability'])->name('bookings.check-availability');
});

// Add email verification middleware to booking routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Booking Routes
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    
    // Payment Routes
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'show'])->name('bookings.payment');
    Route::post('/bookings/finalize', [BookingController::class, 'finalizeBooking'])->name('bookings.finalize');
    Route::post('/bookings/process-payment', [PaymentController::class, 'process'])->name('bookings.process-payment');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Guest Only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
        ->name('password.request');

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetCode'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (User Login Required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::patch('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications.update');
    Route::get('profile/photo/check-storage-link', [ProfileController::class, 'checkStorageLink'])
        ->name('profile.photo.check-storage-link');

    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Room creation (Create & Store)
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');

    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('status', 'Your email has been verified!');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'Verification link sent!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Existing routes...
    Route::get('/riwayat-pemesanan', [BookingController::class, 'riwayat'])->name('bookings.riwayat');
    // ... rest of the existing routes ...
});

/*
|--------------------------------------------------------------------------
| Room Resource Routes (CRUD, kecuali index yang sudah diambil alih oleh '/')
|--------------------------------------------------------------------------
*/
Route::resource('rooms', RoomController::class)->except(['index']);

/*
|--------------------------------------------------------------------------
| API Route untuk user login (optional)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Room Management
    Route::resource('rooms', App\Http\Controllers\Admin\RoomController::class);
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('index');
        Route::get('/daily', [App\Http\Controllers\Admin\ReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [App\Http\Controllers\Admin\ReportController::class, 'monthly'])->name('monthly');
        Route::get('/yearly', [App\Http\Controllers\Admin\ReportController::class, 'yearly'])->name('yearly');
        Route::get('/export/excel/{type}', [App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf/{type}', [App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('export.pdf');
    });
});

// Receptionist Routes
Route::middleware(['auth', 'receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    Route::get('/dashboard', [ReceptionistController::class, 'dashboard'])->name('dashboard');
    
    // Offline Booking
    Route::get('/offline-booking', [App\Http\Controllers\Receptionist\OfflineBookingController::class, 'create'])->name('offline-booking');
    Route::post('/offline-booking', [App\Http\Controllers\Receptionist\OfflineBookingController::class, 'store'])->name('offline-booking.store');
    Route::get('/get-booked-dates/{room}', [App\Http\Controllers\Receptionist\OfflineBookingController::class, 'getBookedDates'])->name('get-booked-dates');
    
    // Booking Management
    Route::get('/bookings', [App\Http\Controllers\Receptionist\BookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/{booking}/invoice', [App\Http\Controllers\Receptionist\BookingController::class, 'generateInvoice'])->name('bookings.invoice');
    Route::patch('/bookings/{booking}/status', [App\Http\Controllers\Receptionist\BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::patch('/bookings/{booking}/payment', [App\Http\Controllers\Receptionist\BookingController::class, 'updatePayment'])->name('bookings.update-payment');
    Route::get('/bookings/completed', [App\Http\Controllers\Receptionist\CheckInOutController::class, 'completedList'])->name('bookings.completed');
    
    // Check-in/Check-out Management
    Route::get('/check-in', [App\Http\Controllers\Receptionist\CheckInOutController::class, 'checkInList'])->name('check-in');
    Route::post('/check-in/{booking}', [App\Http\Controllers\Receptionist\CheckInOutController::class, 'checkIn'])->name('check-in.process');
    Route::get('/check-out', [App\Http\Controllers\Receptionist\CheckInOutController::class, 'checkOutList'])->name('check-out');
    Route::post('/check-out/{booking}', [App\Http\Controllers\Receptionist\CheckInOutController::class, 'checkOut'])->name('check-out.process');
    
    // Room Management
    Route::get('/rooms', [ReceptionistController::class, 'rooms'])->name('rooms');
    Route::patch('/rooms/{room}/status', [ReceptionistController::class, 'updateRoomStatus'])->name('rooms.status');
    
    // Guest Management
    Route::get('/guests', [ReceptionistController::class, 'guests'])->name('guests');
    
    // Transaction Management
    Route::get('/transactions', [App\Http\Controllers\Receptionist\TransactionController::class, 'index'])->name('transactions');
    
    // Reports
    Route::get('/reports', [ReceptionistController::class, 'reports'])->name('reports');
    Route::get('/reports/download', [ReceptionistController::class, 'downloadReports'])->name('reports.download');
});

Route::get('/get-booked-dates/{room_id}', [App\Http\Controllers\BookingController::class, 'getBookedDates'])->name('bookings.getBookedDates');

// Test endpoint for Midtrans
Route::get('/midtrans/test', function() {
    return response()->json(['status' => 'success', 'message' => 'Webhook endpoint is accessible']);
});

// Test webhook route
Route::get('/test-webhook', function() {
    $data = [
        'transaction_status' => 'settlement',
        'status_code' => '200',
        'status_message' => 'Success, transaction is found',
        'order_id' => 'ORDER-' . time() . '-14',
        'payment_type' => 'bank_transfer',
        'transaction_id' => 'test-' . time(),
        'transaction_time' => date('Y-m-d H:i:s'),
        'payment_code' => '12345',
        'gross_amount' => '850000.00',
        'currency' => 'IDR',
        'fraud_status' => 'accept',
        'settlement_time' => date('Y-m-d H:i:s')
    ];

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'X-Midtrans-Notification' => 'true'
    ])->post(url('/api/midtrans/webhook'), $data);

    return response()->json([
        'webhook_response' => $response->json(),
        'webhook_status' => $response->status()
    ]);
});

// Hotel Features Routes
Route::get('/activities', [ActivitiesController::class, 'index'])->name('activities');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');

// Midtrans Routes
Route::post('/api/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');
Route::get('/bookings/finish', [BookingController::class, 'finish'])->name('bookings.finish');
Route::get('/bookings/error', [BookingController::class, 'error'])->name('bookings.error');
Route::get('/bookings/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

// Payment callback routes
Route::get('/payment/finish', [TransactionController::class, 'finish'])->name('payment.finish');
Route::get('/payment/finish/ajax', [TransactionController::class, 'finishAjax'])->name('payment.finish.ajax');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Midtrans webhook
Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('midtrans.webhook');

// Google Login Routes
Route::get('auth/google', [SocialiteController::class, 'redirectToGoogle'])
    ->name('google.login')
    ->middleware('guest');
Route::get('auth/google/callback', [SocialiteController::class, 'handleGoogleCallback'])
    ->name('google.callback')
    ->middleware('guest');

// Password Reset Routes
Route::get('/reset-password/verify', [PasswordResetController::class, 'showCodeVerificationForm'])->name('password.code');
Route::post('/reset-password/verify', [PasswordResetController::class, 'verifyCode'])->name('password.verify-code');

// Password Reset Routes
Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])
    ->name('password.verify');

Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])
    ->name('password.verify-code');

Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset.form');

Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword'])
    ->name('password.update');

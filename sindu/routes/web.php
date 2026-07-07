<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ChildController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpApiController;
use App\Http\Controllers\ChatController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend-otp');

    // API Routes for mobile/external integration
    Route::post('/api/send-otp', [OtpApiController::class, 'sendOtp']);
    Route::post('/api/verify-otp', [OtpApiController::class, 'verifyOtp']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/', [ChildController::class, 'index']);
    Route::get('/admin', [ChildController::class, 'adminIndex']);
    
    Route::get('/api/children', [ChildController::class, 'getChildren']);
    Route::post('/api/children', [ChildController::class, 'storeChild']);
    Route::put('/api/children/{child}', [ChildController::class, 'updateChild']);
    Route::delete('/api/children/{child}', [ChildController::class, 'destroyChild']);
    
    Route::post('/api/records', [ChildController::class, 'storeRecord']);
    Route::delete('/api/records/{record}', [ChildController::class, 'destroyRecord']);
    
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/messages', [ChatController::class, 'store'])->name('chat.store');
    Route::get('/chat/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');

    Route::post('/logout', [AuthController::class, 'logout']);
});

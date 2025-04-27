<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PrescriptionController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register',   [AuthController::class, 'register']);
Route::post('verify-2fa', [AuthController::class, 'verify2FA']);
Route::post('login',      [AuthController::class, 'login']);

// Protected doctor routes
Route::middleware(['auth:sanctum', 'role:doctor'])->group(function () {
    Route::post('prescriptions', [PrescriptionController::class, 'store']);
});

// Test route
Route::get('test', function () {
    return response()->json([
        'message' => 'Po funksionon React + Laravel API! 🎉'
    ]);
});

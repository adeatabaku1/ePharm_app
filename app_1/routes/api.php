<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\ChatController;
use App\Http\Controllers\Doctor\NotificationController;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Doctor\PrescriptionController;
use App\Http\Controllers\Doctor\SettingsController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Po funksionon React + Laravel API! 🎉'
    ]);
});

// 🩺 All routes for authenticated doctors
Route::prefix('doctor')->middleware(['auth:sanctum', 'role:doctor'])->group(function () {

    // Profile
    Route::get('/profile', [DoctorController::class, 'show']);
    Route::put('/profile', [DoctorController::class, 'update']);

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index']);
    Route::post('/appointments', [AppointmentController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentController::class, 'show']);
    Route::put('/appointments/{id}', [AppointmentController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentController::class, 'destroy']);

    // Patients
    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/patients/{id}/history', [PatientController::class, 'history']);

    // Prescriptions
    Route::get('/prescriptions', [PrescriptionController::class, 'index']);
    Route::post('/prescriptions', [PrescriptionController::class, 'store']);

    // Chat
    Route::get('/chat-rooms', [ChatController::class, 'index']);
    Route::post('/chat-rooms/{id}/messages', [ChatController::class, 'sendMessage']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Settings
    Route::put('/settings/password', [SettingsController::class, 'updatePassword']);
});

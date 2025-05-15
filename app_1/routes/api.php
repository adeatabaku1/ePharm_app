<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\PharmacyRegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pharmacy\PharmacyController;
use App\Http\Controllers\Pharmacy\BillingController;
use App\Http\Controllers\Pharmacy\MedicineController;
use App\Http\Controllers\Pharmacy\DoctorController;
use App\Http\Controllers\Pharmacy\PatientController;
use App\Http\Controllers\Pharmacy\NotificationController;
use App\Http\Controllers\Pharmacy\PrescriptionController;
use App\Http\Controllers\Pharmacy\OrdersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider within the "api"
| middleware group.
|
*/

Route::get('ping', fn() => response()->json(['pong']));

// 1) ARBK-backed pharmacy signup
Route::post('pharmacies/register', [PharmacyRegistrationController::class, 'register']);

// 2) Authentication
Route::post('login',  [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

// 3) Email verification
Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return response()->json(['message' => 'Email verified']);
})->middleware('auth:sanctum')->name('verification.verify');

Route::post('email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verification e-mail sent']);
})->middleware('auth:sanctum')->name('verification.send');

// 4) Pharmacy API Routes
Route::middleware(['auth:sanctum'])->group(function () {
    // Pharmacy Dashboard
    Route::prefix('pharmacies/{pharmacyId}')->group(function () {
        // Dashboard
        Route::get('/dashboard', [PharmacyController::class, 'getDashboardStats']);
        
        // Settings
        Route::get('/settings', [PharmacyController::class, 'getSettings']);
        Route::put('/settings', [PharmacyController::class, 'updateSettings']);
        
        // Billing
        Route::get('/sales', [BillingController::class, 'getSales']);
        Route::get('/bills', [BillingController::class, 'getBills']);
        Route::get('/billing/stats', [BillingController::class, 'getBillingStats']);
        Route::post('/bills', [BillingController::class, 'createBill']);
        
        // Medicines
        Route::get('/medicines', [MedicineController::class, 'getMedicines']);
        Route::get('/medicines/{medicineId}', [MedicineController::class, 'getMedicine']);
        Route::post('/medicines', [MedicineController::class, 'createMedicine']);
        Route::put('/medicines/{medicineId}', [MedicineController::class, 'updateMedicine']);
        Route::delete('/medicines/{medicineId}', [MedicineController::class, 'deleteMedicine']);
        Route::get('/medicine-categories', [MedicineController::class, 'getMedicineCategories']);
        
        // Doctors
        Route::get('/doctors', [DoctorController::class, 'getDoctors']);
        Route::get('/doctors/{doctorId}', [DoctorController::class, 'getDoctor']);
        Route::get('/doctor-specializations', [DoctorController::class, 'getSpecializations']);
        
        // Patients
        Route::get('/patients', [PatientController::class, 'getPatients']);
        Route::get('/patients/{patientId}', [PatientController::class, 'getPatient']);
        Route::get('/patients/{patientId}/purchase-history', [PatientController::class, 'getPatientPurchaseHistory']);
        Route::get('/patients/{patientId}/credit-points', [PatientController::class, 'getPatientCreditPoints']);
        
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'getNotifications']);
        Route::post('/notifications/{notificationId}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
        Route::get('/notification-count', [NotificationController::class, 'getNotificationCount']);
        
        // Prescriptions
        Route::get('/prescriptions', [PrescriptionController::class, 'getPrescriptions']);
        Route::get('/prescriptions/{prescriptionId}', [PrescriptionController::class, 'getPrescription']);
        Route::put('/prescriptions/{prescriptionId}/status', [PrescriptionController::class, 'updatePrescriptionStatus']);
        Route::post('/prescriptions/{prescriptionId}/process', [PrescriptionController::class, 'processPrescription']);
        
        // Orders
        Route::get('/orders', [OrdersController::class, 'getOrders']);
        Route::get('/orders/{orderId}', [OrdersController::class, 'getOrder']);
        Route::put('/orders/{orderId}/status', [OrdersController::class, 'updateOrderStatus']);
        Route::get('/order-stats', [OrdersController::class, 'getOrderStats']);
    });
});

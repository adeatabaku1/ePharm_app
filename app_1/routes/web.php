<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;    // ← add this
use App\Models\Tenant;
use App\Models\Pharmacy;                // ← make sure you import Pharmacy too

// Enable all of Laravel's auth routes, including email verification:
//Auth::routes(['verify' => true]);

//veq testim a shfaqet json ne POSTMAN edhe me kqyr a futet ne databazeRoute::get('/test-insert', function () {
Route::get('/test-insert', function () {
    $pharmacy = Pharmacy::create([
        'name'    => 'SunMed Pharmacy',
        'email'   => 'sunmedpharmacy@gmail.com',
        'phone'   => '555-123-4567',
        'address' => '789 Oak Avenue',
    ]);

    return response()->json($pharmacy);
});

// Example of a protected route that requires login *and* verified email
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', function(){
        return view('dashboard');
    });
    // …put any other auth/verified routes here…
});

//veq testim a shfaqet json ne POSTMAN edhe me kqyr a futet ne databaze

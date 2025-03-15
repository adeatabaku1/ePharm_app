<?php

use Illuminate\Support\Facades\Route;

use App\Models\Tenant;

//veq testim a shfaqet json ne POSTMAN edhe me kqyr a futet ne databaze
Route::get('/test-insert', function () {
    $pharmacy = Pharmacy::create([
        'name' => 'SunMed Pharmacy',
        'email' => 'sunmedpharmacy@gmail.com',
        'phone' => '555-123-4567',
        'address' => '789 Oak Avenue'
    ]);

    return response()->json($pharmacy);
});

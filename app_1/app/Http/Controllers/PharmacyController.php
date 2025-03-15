<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;

class PharmacyController extends Controller
{
    public function store(Request $request)
    {
        $pharmacy = Tenant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return response()->json($pharmacy, 201);
    }
}


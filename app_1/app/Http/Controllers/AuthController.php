<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Pharmacist;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users',
            'username'              => 'required|string|unique:users',
            'password'              => 'required|string|min:6|confirmed',
            'phone'                 => 'required|string',
            'user_type'             => 'required|in:patient,doctor,pharmacist',
            'tenant_id'             => 'required|exists:tenants,id',
        ]);

        // create base user
        $user = User::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'username'   => $data['username'],
            'password'   => Hash::make($data['password']),
            'phone'      => $data['phone'],
            'user_type'  => $data['user_type'],
            'tenant_id'  => $data['tenant_id'],
        ]);

        // create profile based on role
        if ($data['user_type'] === 'patient') {
            Patient::create([
                'user_id'        => $user->id,
                'license_number' => Str::uuid(),
                'birthdate'      => now(),
                'gender'         => 'other',
                'address'        => '',
            ]);
        } elseif ($data['user_type'] === 'doctor') {
            Doctor::create([
                'user_id'        => $user->id,
                'license_number' => Str::uuid(),
                'specialization' => '',
                'is_verified'    => false,
            ]);
        } else { // pharmacist
            Pharmacist::create([
                'user_id'        => $user->id,
                'pharmacy_id'    => 1,
                'license_number' => Str::uuid(),
            ]);
        }

        // generate & cache 2FA code
        $code = rand(100000, 999999);
        Cache::put("2fa_code_{$user->id}", $code, now()->addMinutes(5));

        // store in notifications table
        Notification::create([
            'user_id'  => $user->id,
            'type'     => '2fa',
            'title'    => 'Your verification code',
            'message'  => "Your ePharm verification code is: $code",
            'is_read'  => false,
        ]);

        return response()->json([
            'message' => 'Registration successful. Check your notifications for the 2FA code.',
            'user_id' => $user->id,
        ], 201);
    }


    public function verify2FA(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'code'    => 'required|digits:6',
        ]);

        $cached = Cache::get("2fa_code_{$data['user_id']}");
        if (! $cached || $cached != $data['code']) {
            return response()->json(['message' => 'Invalid or expired 2FA code.'], 401);
        }

        // clear out code
        Cache::forget("2fa_code_{$data['user_id']}");

        // issue Sanctum token
        $user  = User::findOrFail($data['user_id']);
        $token = $user->createToken('epharm-token')->plainTextToken;

        return response()->json([
            'message'      => '2FA verified, here is your token.',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 200);
    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // issue Sanctum token
        $token = $user->createToken('epharm-token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
        ], 200);
    }
}

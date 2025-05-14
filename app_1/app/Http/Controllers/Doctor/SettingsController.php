<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Doctor\UpdatePasswordRequest;
use App\Http\Requests\Doctor\UpdateProfileRequest;
use App\Http\Resources\Doctor\UserResource;
use App\Services\Doctor\SettingsService;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function profile()
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $result = $this->settingsService->updateProfile($user, $request->validated());

        return response()->json($result);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $result = $this->settingsService->updatePassword($user, $request->validated());

        return response()->json($result);
    }
}

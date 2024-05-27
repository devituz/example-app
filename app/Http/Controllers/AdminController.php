<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;

class AdminController extends Controller
{
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator($request->all(), [
            'phone_number' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = Admin::where('phone_number', $request->phone_number)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['error' => 'Login parol xato'], 401);
        }

        $admin->token = Str::random(60);
        $admin->save();

        return response()->json([
            'message' => 'Login successfully',
            'token' => $admin->token,
        ]);
    }

    public function getAdminMe(Request $request): \Illuminate\Http\JsonResponse
    {
        $token = $request->bearerToken();

        $admin = Admin::where('token', $token)->firstOrFail();

        $response = [
            'id' => $admin->id,
            'name' => $admin->name,
            'last_name' => $admin->last_name,
            'phone_number' => $admin->phone_number,
        ];

        return response()->json($response);
    }

    public function updateProfile(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator($request->all(), [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = $request->bearerToken();
        $admin = Admin::where('token', $token)->firstOrFail();

        $admin->name = $request->input('name');
        $admin->last_name = $request->input('last_name');
        $admin->phone_number = $request->input('phone_number');
        $admin->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'admin' => $admin
        ]);
    }

    public function updatePassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = validator($request->all(), [
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $token = $request->bearerToken();
        $admin = Admin::where('token', $token)->firstOrFail();

        if (!Hash::check($request->input('old_password'), $admin->password)) {
            return response()->json(['error' => 'Old password is incorrect'], 401);
        }

        $admin->password = Hash::make($request->input('new_password'));
        $admin->save();

        return response()->json([
            'message' => 'Password updated successfully',
        ]);
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $token = $request->bearerToken();
        $admin = Admin::where('token', $token)->first();

        if ($admin) {
            $admin->token = null;
            $admin->save();

            return response()->json([
                'message' => 'Logged out successfully',
            ]);
        }

        return response()->json(['error' => 'Invalid token'], 401);
    }



}

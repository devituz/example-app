<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Admin;

class AdminController extends Controller
{
    public function login(Request $request)
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
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $admin->token = Str::random(60);
        $admin->save();

        return response()->json([
            'message' => 'Login successfully',
            'token' => $admin->token,
        ]);
    }

    public function getAdminMe(Request $request)
    {
        $token = $request->bearerToken();

        $admin = Admin::where('token', $token)->firstOrFail();

        $response = [
            'id' => $admin->id,
            'name' => $admin->name,
            'last_name' => $admin->last_name,
            'phone_number' => $admin->phone_number,
            'password' => $admin->password,
        ];

        return response()->json($response);
    }

}

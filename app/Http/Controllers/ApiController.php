<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use function PHPUnit\Framework\isEmpty;

// For validation

class ApiController extends Controller
{

    public function getAllDevices(Request $request)
    {
        $devices = Device::all(['id', 'androidId', 'windowsId', 'token']);
        $devices = $devices->map(function ($device) {
            return [
                'id' => $device->id,
                'androidId' => $device->androidId,
                'windowsId' => $device->windowsId,
                'token' => [
                    'bearer_token' => $device->token
                ],
            ];
        });

        $response = response()->json($devices);


        if ($request->header('User-Agent') && strpos($request->header('User-Agent'), 'Mozilla') !== false) {
            return response('<h1 align="center">404 Not Found</h1>', 404);
        }

        return $response;
    }


    public function updateProfile(Request $request)
    {


        // Log all incoming request data
        Log::info("Request data: " . json_encode($request->all()));

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'userimg' => 'sometimes|file', // Faylni yuklashga ruxsat berish
        ]);

        if ($validator->fails()) {
            Log::error("Validation errors: " . json_encode($validator->errors()));
            return response()->json($validator->errors(), 422);
        }

        $token = $request->bearerToken();

        Log::info("Bearer token: " . $token);

        $device = Device::where('token', $token)->first();

        if (!$device) {
            Log::error("Device not found for token: " . $token);
            return response()->json(['message' => 'Token not found'], 404);
        }

        $device->firstname = $request->input('firstname');
        $device->lastname = $request->input('lastname');

        if ($request->hasFile('userimg')) {
            if ($device->userimg) {
                $oldImagePath = 'public/' . $device->userimg;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            // Upload new user image
            $path = $request->file('userimg')->store('images', 'public');
            // Fayl nomini JSON javobida saqlash uchun
            $device->userimg = $path;
        }

        $device->save();

        Log::info("Profile updated successfully for device ID: " . $device->id);

        // Fayl nomini JSON javobida qaytarish
        return response()->json(['message' => 'Profile updated successfully']);
    }


    public function getme(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token topilmadi'], 400);
        }

        try {
            $device = Device::where('token', $token)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Qurilma topilmadi'], 404);
        }

        $response = [
            'id' => $device->id,
            'lastname' => $device->lastname,
            'firstname' => $device->firstname,
            'userimg' => url('storage/' . $device->userimg),
        ];

        return response()->json($response);
    }


    public function kurslarget(Request $request)
    {
        $token = $request->bearerToken();
        $device = Device::where('token', $token)->firstOrFail();

        $courses = $device->kurslars->map(function ($kurs) {
            return [
                'id' => $kurs->id,
                'courses_name' => $kurs->courses_name,
                'teachers_name' => $kurs->teachers_name,
                'teachers_img' => url('storage/' . $kurs->teachers_img),
                'Category' => $kurs->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'category_name' => $category->category_name,
                        'category_img' => url('storage/' . $category->category_img),
                        'Lessons' => $category->lessons->map(function ($lesson) {
                            return [
                                'title' => $lesson->title,
                                'video' => url('storage/' . $lesson->video),
                                'description' => $lesson->description,
                            ];
                        }),
                    ];
                }),
            ];
        });


//        if ($courses->isEmpty()) {
//            $courses = [['empty' => 'Sizda hechqanday kurs yo\'q']];
//        }

        return response()->json($courses);
    }

}

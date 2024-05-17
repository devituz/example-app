<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;


class ApiController extends Controller
{
    public function getAllDevices()
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

        // Check if the request is coming from a browser
        if ($response->getStatusCode() == 200 && strpos(request()->header('User-Agent'), 'Mozilla') !== false) {
            return response('<h1 align="center">404 Not Found</h1>', 404);
        }

        return $response;
    }


    public function getDeviceWithToken(Request $request)
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


        if ($courses->isEmpty()) {
            $courses = [['empty' => 'Sizda hechqanday kurs yo\'q']];
        }

        return response()->json($courses);
    }

}

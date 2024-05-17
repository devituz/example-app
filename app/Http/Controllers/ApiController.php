<?php
namespace App\Http\Controllers;

use App\Models\Device;

class ApiController extends Controller
{
    // Method to fetch all devices
    public function getAllDevices()
    {
        $devices = Device::all(['id', 'androidId', 'windowsId']);
        return response()->json($devices);
    }

    // Method to fetch a specific device by ID with its associated data
    public function getDeviceWithCourses($id)
    {
        $device = Device::with(['kurslars.categories.lessons'])->findOrFail($id);
        $courses = $device->kurslars->map(function ($kurs) {
            return [
                'id' => $kurs->id,
                'courses_name' => $kurs->courses_name,
                'teachers_name' => $kurs->teachers_name,
                'teachers_img' => $kurs->teachers_img,
                'Category' => $kurs->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'category_name' => $category->category_name,
                        'category_img' => $category->category_img,
                        'Lessons' => $category->lessons->map(function ($lesson) {
                            return [
                                'title' => $lesson->title,
                                'video' => $lesson->video,
                                'description' => $lesson->description,
                            ];
                        }),
                    ];
                }),
            ];
        });

        return response()->json($courses);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kurslar;
use App\Models\Category;
use App\Models\Lessons;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getData()
    {
        $kurslar = Kurslar::all();

        $data = [];

        foreach ($kurslar as $kurs) {
            $categoryData = [];

            foreach ($kurs->categories as $category) {
                $lessonData = [];

                foreach ($category->lessons as $lesson) {
                    $lessonData[] = [
                        'id' => $category->id,
                        'title' => $lesson->title,
                        'video' => url('storage/' . $lesson->video), // Video URL ni to'g'rilash
                        'description' => $lesson->description,
                    ];
                }

                $categoryData[] = [
                    'id' => $category->id,
                    'category_name' => $category->category_name,
                    'category_img' => url('storage/' . $category->category_img), // Rasm URL ni to'g'rilash
                    'lessons' => $lessonData,
                ];
            }

            $data[] = [
                'id' => $kurs->id,
                'courses_name' => $kurs->courses_name,
                'teachers_name' => $kurs->teachers_name,
                'teachers_img' => url('storage/' . $kurs->teachers_img), // Rasm URL ni to'g'rilash
                'categories' => $categoryData,
            ];
        }

        return response()->json($data);
    }

}

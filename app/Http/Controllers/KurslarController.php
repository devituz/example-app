<?php

namespace App\Http\Controllers;

use App\Models\Kurslar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurslarController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $kurslar = Kurslar::all();

        // Har bir kursning 'teachers_img' maydonini to'liq URL bilan yangilash
        foreach ($kurslar as $kurs) {
            $kurs->teachers_img = url('storage/' . $kurs->teachers_img);
        }

        return response()->json($kurslar, 200);
    }


    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'teachers_name' => 'required|string|max:255',
            'teachers_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'courses_name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('teachers_img')) {
            $path = $request->file('teachers_img')->store('images', 'public');
            $validatedData['teachers_img'] = $path;
        }

        $kurs = Kurslar::create($validatedData);

        return response()->json([
            'message' => 'Kurs created successfully',
            'kurs' => $kurs,
        ], 201);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $kurs = Kurslar::findOrFail($id);

        if ($kurs->teachers_img) {
            $kurs->teachers_img = url('storage/' . $kurs->teachers_img);
        }

        return response()->json($kurs, 200);
    }


    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'teachers_name' => 'required|string|max:255',
            'teachers_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'courses_name' => 'required|string|max:255',
        ]);

        $kurs = Kurslar::findOrFail($id);

        if ($request->hasFile('teachers_img')) {
            if ($kurs->teachers_img) {
                $oldImagePath = 'public/' . $kurs->teachers_img;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            $path = $request->file('teachers_img')->store('images', 'public');
            $validatedData['teachers_img'] = $path;
        }

        $kurs->update($validatedData);

        // 'teachers_img' maydonini to'liq URL bilan yangilash
        if ($kurs->teachers_img) {
            $kurs->teachers_img = url('storage/' . $kurs->teachers_img);
        }

        return response()->json([
            'message' => 'Kurs updated successfully',
        ], 200); // 200 OK
    }


    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $kurs = Kurslar::findOrFail($id);

        // Eski rasmni o'chirish
        if ($kurs->teachers_img) {
            Storage::delete('public/' . $kurs->teachers_img);
        }

        // Kursni o'chirish
        $kurs->delete();

        return response()->json([
            'message' => 'Kurs deleted successfully',
        ], 200); // 200 No Content
    }

}

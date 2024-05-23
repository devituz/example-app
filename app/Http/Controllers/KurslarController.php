<?php

namespace App\Http\Controllers;

use App\Models\Kurslar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KurslarController extends Controller
{
    public function index()
    {
        $kurslar = Kurslar::all();
        return view('kurslar.index', compact('kurslar'));
    }

    public function create()
    {
        return view('kurslar.create');
    }

    public function store(Request $request)
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

        Kurslar::create($validatedData);
        return redirect()->route('kurslar.index');
    }


    public function show($id)
    {
        $kurs = Kurslar::findOrFail($id);
        return view('kurslar.show', compact('kurs'));
    }

    public function edit($id)
    {
        $kurs = Kurslar::findOrFail($id);

        return view('kurslar.edit', compact('kurs'));
    }


    public function update(Request $request, $id)
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
        return redirect()->route('kurslar.index')->with('success', 'Course updated successfully.');
    }


    public function destroy($id)
    {
        $kurs = Kurslar::findOrFail($id);
        if ($kurs->teachers_img) {
            Storage::delete('public/' . $kurs->teachers_img);
        }
        $kurs->delete();
        return redirect()->route('kurslar.index');
    }
}

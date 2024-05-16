<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Kurslar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        $kurslar = Kurslar::all();
        return view('category.create', compact('kurslar'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kurslar_id' => 'required|integer|exists:kurslar,id',
        ]);

        if ($request->hasFile('category_img')) {
            $path = $request->file('category_img')->store('images', 'public');
            $validatedData['category_img'] = $path;
        }

        Category::create($validatedData);
        return redirect()->route('category.index');
    }

    public function show($id)
    {
        $category = Category::find($id);
        return view('category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $kurslar = Kurslar::all(); // Barcha kurslarni olish
        return view('category.edit', compact('category', 'kurslar'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kurslar_id' => 'required|integer|exists:kurslar,id',
        ]);

        $category = Category::findOrFail($id);

        if ($request->hasFile('category_img')) {
            if ($category->category_img) {
                $oldImagePath = 'public/' . $category->category_img;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            $path = $request->file('category_img')->store('images', 'public');
            $validatedData['category_img'] = $path;
        }

        $category->update($validatedData);
        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if ($category) {
            if ($category->category_img) {
                Storage::delete('public/' . $category->category_img);
            }
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
        } else {
            return redirect()->route('category.index')->withErrors(['category' => 'Category not found.']);
        }
    }


}

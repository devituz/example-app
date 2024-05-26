<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::all();


        foreach ($categories as $cate) {
            $cate->category_img = url('storage/' . $cate->category_img);
        }

        return response()->json($categories);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kurslar_id' => 'required|integer|exists:kurslar,id',
        ], [
            'kurslar_id.exists' => 'Kurslar tanlanmagan',
        ]);


        if ($request->hasFile('category_img')) {
            $path = $request->file('category_img')->store('category', 'public');
            $validatedData['category_img'] = $path;
        }

        $category = Category::create($validatedData);


        return response()->json([
            'message' => 'Category created successfully',
            '$category' => $category,
        ], 201);

    }


    public function show($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'category_img' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kurslar_id' => 'required|integer|exists:kurslar,id',
        ], [
            'kurslar_id.exists' => 'Kurslar tanlanmagan',
        ]);

        $category = Category::findOrFail($id);

        if ($request->hasFile('category_img')) {
            $oldImagePath = 'public/' . $category->category_img;

            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            $newImagePath = $request->file('category_img')->store('images', 'public');
            $validatedData['category_img'] = $newImagePath;
        }

        $category->update($validatedData);

        return response()->json([
            'message' => 'Category updated successfully',
        ], 200); // 200 OK
    }



    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}

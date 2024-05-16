<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Lessons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;


class LessonsController extends Controller
{
    public function index()
    {
        $lessons = Lessons::all();
        return view('lessons.index', compact('lessons'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('lessons.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'video' => 'required|mimes:mp4,mov,ogg,webm',
                'category_id' => 'required|integer|exists:category,id',
            ]);

            $category = Category::find($validatedData['category_id']);
            if (!$category) {
                return redirect()->back()->withErrors(['category' => 'Invalid category ID']);
            }

            if ($request->hasFile('video')) {
                $path = $request->file('video')->store('videos', 'public');
                $validatedData['video'] = $path;
            }

            Lessons::create($validatedData);
            return redirect()->route('lessons.index');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors());
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }


    public function show($id)
    {
        $lesson = Lessons::findOrFail($id);
        return view('lessons.show', compact('lesson'));
    }

    public function edit($id)
    {
        $lesson = Lessons::findOrFail($id);
        $categories = Category::all(); // Corrected to 'categories'
        return view('lessons.edit', compact('lesson', 'categories'));
    }


    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'video' => 'nullable|mimes:mp4,mov,ogg,webm',
                'category_id' => 'required|integer|exists:category,id',
            ]);

            $lesson = Lessons::findOrFail($id);

            $category = Category::find($validatedData['category_id']);
            if (!$category) {
                return redirect()->back()->withErrors(['category' => 'Invalid category ID']);
            }

            if ($request->hasFile('video')) {
                if ($lesson->video) {
                    $oldVideoPath = storage_path('app/public/' . $lesson->video);
                    if (File::exists($oldVideoPath)) {
                        File::delete($oldVideoPath);
                    }
                }
                $path = $request->file('video')->store('videos', 'public');
                $validatedData['video'] = $path;
            }

            $lesson->update($validatedData);
            return redirect()->route('lessons.index')->with('success', 'Lesson updated successfully.');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator->errors());
        } catch (ModelNotFoundException $e) {
            return back()->withErrors(['lesson' => 'Lesson not found.']);
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['error' => 'An error occurred. Please try again.']);
        }
    }


    public function destroy($id)
    {
        $lesson = Lessons::findOrFail($id);
        $lesson->delete();
        return redirect()->route('lessons.index');
    }
}

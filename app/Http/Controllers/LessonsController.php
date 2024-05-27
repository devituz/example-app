<?php

namespace App\Http\Controllers;

use App\Models\Lessons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class LessonsController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {


        $lessons = Lessons::all();

        foreach ($lessons as $less) {
            $less->video = url('storage/' . $less->video);
        }

        return response()->json($lessons, 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'video' => 'required|mimes:mp4,mov,ogg,webm',
                'category_id' => 'required|integer|exists:category,id',
            ], [
                'category_id.exists' => 'Kategory tanlanmagan',
            ]);

            if ($request->hasFile('video')) {
                $file = $request->file('video');
                $path = $file->store('videos', 'public');

                // FFmpeg ni sozlash
                $ffmpegPath = env('FFMPEG_BINARIES');
                $ffprobePath = env('FFPROBE_BINARIES');

                $ffmpeg = \FFMpeg\FFMpeg::create([
                    'ffmpeg.binaries' => $ffmpegPath,
                    'ffprobe.binaries' => $ffprobePath,
                ]);

                $video = $ffmpeg->open(storage_path('app/public/' . $path));
                $dimensions = $video->getStreams()->videos()->first()->getDimensions();
                $width = $dimensions->getWidth();
                $height = $dimensions->getHeight();

                if ($width != 1920 || $height != 1080) {
                    return response()->json(['message' => 'Videoning o\'lchami noto\'g\'ri. Kenglik 1920 va balandlik 1080 bo\'lishi kerak.'], 422);
                }

                $validatedData['video'] = $path;
            }

            $lesson = Lessons::create($validatedData);

            return response()->json(['message' => 'Dars muvaffaqiyatli yuklandi'], 201);


        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validatsiya xatosi', 'errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }


    public function show($id): \Illuminate\Http\JsonResponse
    {
        try {
            $lesson = Lessons::findOrFail($id);

            // 'storage/' dan o'lib beryapti
            $lesson->video = url('storage/' . $lesson->video);

            return response()->json(['message' => 'Success', 'data' => $lesson], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
    }


    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'video' => 'nullable|mimes:mp4,mov,ogg,webm',
                'category_id' => 'required|integer|exists:category,id',
            ], [
                'category_id.exists' => 'Kategoriya tanlang',
            ]);

            $lesson = Lessons::findOrFail($id);

            if ($request->hasFile('video')) {
                if ($lesson->video) {
                    Storage::delete('public/' . $lesson->video);
                }

                $path = $request->file('video')->store('videos', 'public');
                $validatedData['video'] = $path;

                // FFmpeg ni sozlash
                $ffmpegPath = env('FFMPEG_BINARIES');
                $ffprobePath = env('FFPROBE_BINARIES');

                $ffmpeg = \FFMpeg\FFMpeg::create([
                    'ffmpeg.binaries' => $ffmpegPath,
                    'ffprobe.binaries' => $ffprobePath,
                ]);

                $video = $ffmpeg->open(storage_path('app/public/' . $path));
                $dimensions = $video->getStreams()->videos()->first()->getDimensions();
                $width = $dimensions->getWidth();
                $height = $dimensions->getHeight();

                if ($width != 1920 || $height != 1080) {
                    // Agar o'lcham noto'g'ri bo'lsa, eski videoni o'chirib tashlaymiz
                    Storage::delete('public/' . $path);
                    return response()->json(['message' => 'Videoning o\'lchami noto\'g\'ri. Kenglik 1920 va balandlik 1080 bo\'lishi kerak.'], 422);
                }
            }

            $lesson->update($validatedData);

            return response()->json(['message' => 'Dars muvaffaqiyatli yangilandi', 'data' => $lesson], 200);

        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validatsiya xatosi', 'errors' => $e->errors()], 422);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }



    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        try {
            $lesson = Lessons::findOrFail($id);
            if ($lesson->video) {
                Storage::delete('public/' . $lesson->video);
            }
            $lesson->delete();
            return response()->json(['message' => 'Lesson deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Lesson not found'], 404);
        } catch (Throwable $e) {
            report($e);
            return response()->json(['message' => 'An error occurred. Please try again.'], 500);
        }
    }
}

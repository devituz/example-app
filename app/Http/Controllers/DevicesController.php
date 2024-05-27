<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Kurslar;

class DevicesController extends Controller
{
    public function index()
    {
        $devices = Device::with('kurslars')->get();

        $formattedDevices = $devices->map(function ($device) {
            $courses = $device->kurslars->pluck('courses_name')->toArray();
            return [
                'id' => $device->id,
                'lastname' => $device->lastname,
                'firstname' => $device->firstname,
                'userimg' => url('storage/' . $device->userimg),
                'androidId' => $device->androidId,
                'windowsId' => $device->windowsId,
                'token' => $device->token,
//                'created_at' => $device->created_at,
//                'updated_at' => $device->updated_at,
                'kurslars' => $courses,
            ];
        });

        return response()->json($formattedDevices, 200);
    }






    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'userimg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'androidId' => 'required|string',
            'windowsId' => 'required|string',
            'kurslar_ids' => 'sometimes|array', // Make kurslar_ids optional
        ]);

        DB::beginTransaction();

        try {
            $imagePath = $request->file('userimg')->store('userimages', 'public');

            $device = Device::create([
                'lastname' => $validatedData['lastname'],
                'firstname' => $validatedData['firstname'],
                'userimg' => $imagePath,
                'androidId' => $validatedData['androidId'],
                'windowsId' => $validatedData['windowsId'],
                'token' => Str::random(40),
            ]);

            if (isset($validatedData['kurslar_ids'])) {
                $device->kurslars()->sync($validatedData['kurslar_ids']);
            }

            DB::commit();

            return response()->json(['message' => 'Device created successfully', 'data' => $device], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create device', 'error' => $e->getMessage()], 500);
        }
    }





    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'androidId' => 'required|string|max:255',
            'windowsId' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'userimg' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'kurslar_ids' => 'sometimes|array', // Make kurslar_ids optional
        ]);

        $device = Device::findOrFail($id);

        $kurslarIds = isset($validatedData['kurslar_ids']) ? $validatedData['kurslar_ids'] : null;
        $device->kurslars()->sync($kurslarIds);

        if ($request->hasFile('userimg')) {
            // Delete old image if exists
            if ($device->userimg) {
                Storage::delete('public/' . $device->userimg);
            }
            $path = $request->file('userimg')->store('images', 'public');
            $validatedData['userimg'] = $path;
        }

        $device->update($validatedData);
        return response()->json(['message' => 'Device updated successfully', 'data' => $device], 200);
    }



    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();
        return response()->json(['message' => 'Device deleted successfully'], 200);
    }

}

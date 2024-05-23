<?php

namespace App\Http\Controllers;

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
        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        $courses = Kurslar::all();
        return view('devices.store', compact('courses'));
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

        return redirect()->route('devices.index')->with('success', 'Device created successfully!');
    }

    public function edit($id)
    {
        $device = Device::findOrFail($id);
        $courses = Kurslar::all();
        return view('devices.edit', compact('device', 'courses'));
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

        // Find the device by ID
        $device = Device::findOrFail($id);


        // Check if kurslar_ids is set, otherwise use null
        $kurslarIds = isset($validatedData['kurslar_ids']) ? $validatedData['kurslar_ids'] : null;
        $device->kurslars()->sync($kurslarIds);


        if ($request->hasFile('userimg')) {
            if ($device->category_img) {
                $oldImagePath = 'public/' . $device->userimg;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            $path = $request->file('userimg')->store('images', 'public');
            $validatedData['userimg'] = $path;
        }

        $device->update($validatedData);
        return redirect()->route('devices.index')->with('success', 'Device updated successfully.');
    }


    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully!');
    }
}

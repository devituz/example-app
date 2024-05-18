<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Kurslar;

class DevicesController extends Controller
{



//    public function list()
//    {
//        $devices = Device::with('kurslars')->get();
//        $totalDevices = Device::count(); // Umumiy sonini hisoblash
//        return view('devices.list', compact('devices', 'totalDevices'));
//    }

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
            'androidId' => 'required|string',
            'windowsId' => 'required|string',
            'kurslar_ids' => 'sometimes|array', // Make kurslar_ids optional
        ]);

        $device = Device::create([
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
            'androidId' => 'required|string',
            'windowsId' => 'required|string',
            'kurslar_ids' => 'sometimes|array', // Make kurslar_ids optional
        ]);

        $device = Device::findOrFail($id);
        $device->update([
            'androidId' => $validatedData['androidId'],
            'windowsId' => $validatedData['windowsId'],
        ]);

        // Check if kurslar_ids is set, otherwise use null
        $kurslarIds = isset($validatedData['kurslar_ids']) ? $validatedData['kurslar_ids'] : null;
        $device->kurslars()->sync($kurslarIds);

        return redirect()->route('devices.index')->with('success', 'Device updated successfully!');
    }


    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully!');
    }
}






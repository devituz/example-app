<?php

namespace App\Http\Controllers;
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
            'androidId' => 'required|string',
            'windowsId' => 'required|string',
            'kurslar_ids' => 'required|array',
        ]);

        $device = Device::create([
            'androidId' => $validatedData['androidId'],
            'windowsId' => $validatedData['windowsId'],
            'token' => Str::random(40),


        ]);



        $device->kurslars()->sync($validatedData['kurslar_ids']);

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
            'kurslar_ids' => 'required|array',
        ]);

        $device = Device::findOrFail($id);
        $device->update([
            'androidId' => $validatedData['androidId'],
            'windowsId' => $validatedData['windowsId'],
        ]);

        $device->kurslars()->sync($validatedData['kurslar_ids']);

        return redirect()->route('devices.index')->with('success', 'Device updated successfully!');
    }

    public function destroy($id)
    {
        $device = Device::findOrFail($id);
        $device->delete();
        return redirect()->route('devices.index')->with('success', 'Device deleted successfully!');
    }
}






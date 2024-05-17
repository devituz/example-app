<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Kurslar;

class DevicesController extends Controller
{
    public function index()
    {
        $devices = Device::all();
        $courses = Kurslar::all();

        return view('devices.index', compact('devices', 'courses'));
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
            'kurslar_id' => 'required|int',
        ]);

        $device = Device::create([
            'androidId' => $validatedData['androidId'],
            'windowsId' => $validatedData['windowsId'],
            'kurslar_id' => $validatedData['kurslar_id'],


        ]);

        return redirect()->route('devices.index')->with('success', 'Device created successfully!');
    }





}

<?php


use App\Http\Controllers\DevicesController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('admin.layouts.main');
});


//Route::resource('devices', DevicesController::class);

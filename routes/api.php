<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AdminController::class, 'login']);

Route::middleware(['auth.admin.token'])->group(function () {
    Route::get('/getme', [AdminController::class, 'getAdminMe'])->name('malumotlarni olish');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/devices', [ApiController::class, 'getAllDevices']);


Route::group(['middleware' => 'api'], function () {

    //    devices bo'limi boshi'
    Route::post('/devices/update', [ApiController::class, 'updateProfile']);
    Route::get('/devices/kurslarget', [ApiController::class, 'kurslarget']);
    Route::get('/devices/getme', [ApiController::class, 'getme']);
    //    devices bo'limi tugashi'

});

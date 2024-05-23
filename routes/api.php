<?php


use App\Http\Controllers\ApiController;
use App\Http\Controllers\KeyController;
use App\Http\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/devices', [ApiController::class, 'getAllDevices']);

Route::group(['middleware' => 'api'], function () {
    Route::post('/devices/update', [ApiController::class, 'updateProfile']);
    Route::get('/devices/kurslarget', [ApiController::class, 'kurslarget']);
    Route::get('/devices/getme', [ApiController::class, 'getme']);
});

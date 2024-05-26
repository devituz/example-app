<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\KurslarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AdminController::class, 'login']);

Route::middleware(['auth.admin.token'])->group(function () {
    Route::get('/getme', [AdminController::class, 'getAdminMe'])->name('malumotlarni olish');
    Route::post('update-profile', [AdminController::class, 'updateProfile']);
    Route::post('update-password', [AdminController::class, 'updatePassword']);
    Route::post('logout', [AdminController::class, 'logout']);


    Route::get('/kurslar', [KurslarController::class, 'index']);
    Route::post('/kurslar', [KurslarController::class, 'store']);
    Route::get('/kurslar/{id}', [KurslarController::class, 'show']);
    Route::post('/kurslar/update/{id}', [KurslarController::class, 'update']);
    Route::delete('/kurslar/{id}', [KurslarController::class, 'destroy']);



    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::post('/categories/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);



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

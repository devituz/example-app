<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\KurslarController;
use App\Http\Controllers\LessonsController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AdminController::class, 'login']);

Route::middleware(['admin'])->group(function () {
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


    Route::get('/lessons', [LessonsController::class, 'index']);
    Route::post('/lessons', [LessonsController::class, 'store']);
    Route::get('/lessons/{id}', [LessonsController::class, 'show']);
    Route::post('/lessons/update/{id}', [LessonsController::class, 'update']);
    Route::delete('/lessons/{id}', [LessonsController::class, 'destroy']);

    Route::get('/admin/devices', [DevicesController::class, 'index']);
    Route::post('/admin/devices', [DevicesController::class, 'store']);
    Route::post('/admin/devices/update/{id}', [DevicesController::class, 'update']);
    Route::delete('/admin/devices/{id}', [DevicesController::class, 'destroy']);






});


Route::get('/devices', [ApiController::class, 'getAllDevices']);


Route::middleware(['auth.bearer'])->group(function () {

    Route::post('/devices/update', [ApiController::class, 'updateProfile']);
    Route::get('/devices/kurslarget', [ApiController::class, 'getDeviceWithToken']);
    Route::get('/devices/getme', [ApiController::class, 'getme']);


});


<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DevicesController;
use App\Http\Controllers\KurslarController;
use App\Http\Controllers\LessonsController;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});




Route::resource('devices', DevicesController::class);
Route::resource('kurslar', KurslarController::class);
Route::resource('category', CategoryController::class);
Route::resource('lessons', LessonsController::class);

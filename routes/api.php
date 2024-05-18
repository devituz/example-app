<?php


use App\Http\Controllers\ApiController;
use App\Http\Controllers\SmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::get('/devices', [ApiController::class, 'getAllDevices']);
Route::get('/devices/login', [ApiController::class, 'getDeviceWithToken'])->middleware('auth.bearer');
Route::post('/sms', [SmsController::class, 'sendSms']);
Route::post('/smscheck', [SmsController::class, 'checkSms']);
Route::post('/api/admin', [SmsController::class, 'adminSendSms']);



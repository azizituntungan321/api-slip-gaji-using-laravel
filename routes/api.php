<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeesController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\OvertimesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1'], function() {
    Route::patch('/settings', [SettingsController::class,'patchSetting']);
    Route::post('/employees' , [EmployeesController::class,'createEmployee']);
    Route::post('/overtimes', [OvertimesController::class,'createOvertime']);
    Route::post('/overtime-pay/calculate', [OvertimesController::class,'calculateOvertime']);
});

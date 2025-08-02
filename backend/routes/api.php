<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\StatusLogController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //appointmets
    Route::apiResource('appointments', AppointmentController::class)->except(['store']);
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus']);
    
    //materials
    Route::apiResource('materials', MaterialController::class)->except('index');

    //logs
    Route::get('appointments/{appointment}/logs', [StatusLogController::class, 'index']);
    Route::get('appointments/:id', [AppointmentController::class, 'show']);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('appointments', [AppointmentController::class, 'store']);
Route::get('materials', [MaterialController::class, 'index']);
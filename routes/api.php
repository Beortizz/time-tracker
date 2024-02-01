<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TimeslotController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('timer', [TimeslotController::class, 'index']);
Route::post('timer', [TimeslotController::class, 'store']);
Route::put('timer/{id}', [TimeslotController::class, 'update']);
Route::delete('timer/{id}', [TimeslotController::class, 'destroy']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\SubtaskController;
use App\Http\Controllers\Api\TimelogController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::apiResource('tasks', TaskController::class);
Route::post('subtasks', [SubtaskController::class, 'store']);
Route::put('subtasks/{subtask}', [SubtaskController::class, 'update']);
Route::delete('subtasks/{subtask}', [SubtaskController::class, 'destroy']);
Route::post('/timelogs', [TimelogController::class, 'store']);


Route::put('timelogs/{timelog}', [TimelogController::class, 'update']);
Route::delete('timelogs/{timelog}', [TimelogController::class, 'destroy']);

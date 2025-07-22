<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\GameModeController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/assign-teacher-role', [UserController::class, 'assignTeacherRole']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Children routes
    Route::apiResource('children', ChildController::class);
    Route::get('children/{child}/map', [ChildController::class, 'getMap']);

    // Lessons routes
    Route::get('lessons/{lesson}', [LessonController::class, 'show']);
    Route::get('maps/{mapId}/lessons', [LessonController::class, 'getByMap']);
    Route::post('lessons', [LessonController::class, 'store']);
    Route::post('lessons/{lesson}/contents', [LessonController::class, 'addContent']);

    // Game modes routes
    Route::get('game-modes/{gameMode}', [GameModeController::class, 'show']);
    Route::get('lessons/{lessonId}/game-mode', [GameModeController::class, 'getByLesson']);
});

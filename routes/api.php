<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\Api\GameModeController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\PhotoGameController;
use App\Http\Controllers\Api\MathTetrisController;
use App\Http\Controllers\Api\StageProgressController;
use App\Http\Controllers\DashboardController;
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
Route::post('/authenticate', 'authenticate')->name('authenticate');
// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Children routes
    Route::apiResource('children', ChildController::class);
    Route::get('children/{child}/map', [ChildController::class, 'getMap']);
    Route::get('children/{child}/current-stage', [ChildController::class, 'getCurrentStage']);

    // Lessons routes
    Route::get('lessons/{lesson}', [LessonController::class, 'show']);
    Route::get('maps/{mapId}/lessons', [LessonController::class, 'getByMap']);
    Route::post('lessons', [LessonController::class, 'store']);
    Route::post('lessons/{lesson}/contents', [LessonController::class, 'addContent']);
    
    // Game modes routes
    Route::get('game-modes/{gameMode}', [GameModeController::class, 'show']);
    Route::get('lessons/{lessonId}/game-mode', [GameModeController::class, 'getByLesson']);

    // Quiz questions routes
    Route::apiResource('quiz-questions', QuizController::class);
    Route::get('quiz-questions', [QuizController::class, 'index']);

    // Photo game questions routes
    Route::apiResource('photo-game-questions', PhotoGameController::class);
    Route::get('photo-game-questions', [PhotoGameController::class, 'index']);

    // Math tetris questions routes
    Route::apiResource('math-tetris-questions', MathTetrisController::class);
    Route::get('math-tetris-questions', [MathTetrisController::class, 'index']);

    // Stage progress routes
    Route::get('children/{child}/progress', [StageProgressController::class, 'index']);
    Route::get('children/{child}/progress/{stage}', [StageProgressController::class, 'show']);
    Route::post('children/{child}/progress', [StageProgressController::class, 'store']);
    Route::get('children/{child}/total-score', [StageProgressController::class, 'getTotalScore']);
    Route::get('stages/{stage}/leaderboard', [StageProgressController::class, 'getStageLeaderboard']);
    Route::get('stages/{stageId}/stage-content', [StageProgressController::class, 'getLessonsAndGameModes']);
    Route::put('/children/{child}/progress', [ChildController::class, 'updateProgress']);    // Dashboard API routes (for external access)
    Route::prefix('dashboard')->group(function () {
        Route::post('grades', [DashboardController::class, 'createGrade']);
        Route::get('grades', [DashboardController::class, 'getGrades']);
        Route::post('maps', [DashboardController::class, 'createMap']);
        Route::get('maps', [DashboardController::class, 'getMaps']);
        Route::post('stages', [DashboardController::class, 'createStage']);
        Route::get('stages', [DashboardController::class, 'getStages']);
        Route::post('lessons', [DashboardController::class, 'createLesson']);
        Route::get('lessons', [DashboardController::class, 'getLessons']);
        Route::post('game-modes', [DashboardController::class, 'createGameMode']);
        Route::get('game-modes', [DashboardController::class, 'getGameModes']);
    });

});

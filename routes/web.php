<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard Analytics Routes
    Route::get('/dashboard/players', [DashboardController::class, 'getAllPlayers'])->name('dashboard.players');
    Route::get('/dashboard/children-by-grade', [DashboardController::class, 'getChildrenByGrade'])->name('dashboard.children.by.grade');
    Route::get('/dashboard/stats', [DashboardController::class, 'getDashboardStats'])->name('dashboard.stats');
    Route::get('/dashboard/grades-with-maps', [DashboardController::class, 'getGradesWithMaps'])->name('dashboard.grades.maps');
    
    // Dashboard Creation Routes
    Route::post('/dashboard/grades', [DashboardController::class, 'createGrade'])->name('dashboard.grades.create');
    Route::post('/dashboard/maps', [DashboardController::class, 'createMap'])->name('dashboard.maps.create');
    Route::post('/dashboard/stages', [DashboardController::class, 'createStage'])->name('dashboard.stages.create');
    Route::post('/dashboard/lessons', [DashboardController::class, 'createLesson'])->name('dashboard.lessons.create');
    Route::post('/dashboard/game-modes', [DashboardController::class, 'createGameMode'])->name('dashboard.game.modes.create');
    Route::post('/dashboard/game-mode-instances', [DashboardController::class, 'createGameModeInstance'])->name('dashboard.game.mode.instances.create');
    Route::post('/dashboard/quiz-game', [DashboardController::class, 'createQuestion'])->name('dashboard.questions.create');
    Route::post('/dashboard/photo-game', [DashboardController::class, 'createPhotoEntry'])->name('dashboard.photo.game.create');
    Route::post('/dashboard/math-game', [DashboardController::class, 'createMathProblem'])->name('dashboard.math.game.create');
    
    // Dashboard Update Routes
    Route::put('/dashboard/lessons/{id}', [DashboardController::class, 'updateLesson'])->name('dashboard.lessons.update');
    Route::put('/dashboard/game-modes/{id}', [DashboardController::class, 'updateGameMode'])->name('dashboard.game.modes.update');
    Route::put('/dashboard/quiz-game/{id}', [DashboardController::class, 'updateQuestion'])->name('dashboard.questions.update');
});

// CSRF Token Route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->name('csrf.token');

// API Tester Route (no auth required)
Route::get('/api-tester', function () {
    return view('api-tester');
})->name('api.tester');
<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RefereeController;
use App\Http\Controllers\Api\RuleController;
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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Sample API route
Route::get('/profits', [\App\Http\Controllers\SampleDataController::class, 'profits'])->name('profits');
Route::get('/test/match/report', [MatchController::class, 'matchReport'])->name('match.report');

// auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
});

Route::middleware(['jwt.verify'])->group(function () {
    Route::prefix('logout')->group(function () {
        Route::get('/', [AuthController::class, 'logout'])->name('auth.logout');
    });

    // assignment
    Route::prefix('assignment')->group(function () {
        Route::get('/', [AssignmentController::class, 'assignments'])->name('assignment');
        Route::get('/search/{id}', [AssignmentController::class, 'assignment'])->name('assignment.search.id');
    });

    // game
    Route::prefix('game')->group(function () {
        Route::get('/', [GameController::class, 'games'])->name('game');
    });

    // match
    Route::prefix('match')->group(function () {
        Route::get('/', [MatchController::class, 'matches'])->name('match');
        Route::get('/upcoming', [MatchController::class, 'upcomingMatch'])->name('match.upcoming');
        Route::get('/history', [MatchController::class, 'historyMatch'])->name('match.history');
        Route::get('/search/{id}', [MatchController::class, 'match'])->name('match.search.id');
    });

    // notification
    Route::prefix('notification')->group(function () {
        Route::get('/', [NotificationController::class, 'notifications'])->name('notification');
    });


    // rule book
    Route::prefix('rule')->group(function () {
        Route::get('/', [RuleController::class, 'rules'])->name('rule');
    });

    // referee
    Route::prefix('referee')->group(function () {
        Route::get('/', [RefereeController::class, 'referees'])->name('referee');
    });

    // profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'profile'])->name('profile');
        // Route::get('/avatar', [ProfileController::class, 'defaultAvatar'])->name('profile.avatar.default');
        // Route::get('/avatar/{fileId}', [ProfileController::class, 'file'])->name('profile.avatar');
        // Route::get('/license/{fileId}', [ProfileController::class, 'file'])->name('profile.license');
    });
});

<?php

use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\MatchController;
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

// auth
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
});

// assignment
Route::prefix('assigment')->group(function () {
    Route::get('/', [AssignmentController::class, 'assignments'])->name('assignment');
});

// game
Route::prefix('game')->group(function () {
    Route::get('/', [GameController::class, 'games'])->name('game');
});

// match
Route::prefix('match')->group(function () {
    Route::get('/', [MatchController::class, 'matches'])->name('match');
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
    Route::get('/{userId}', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/file/{fileId}', [ProfileController::class, 'file'])->name('profile.file');
    // Route::get('/file/path/{userId}', [ProfileController::class, 'profile'])->name('profile');
});





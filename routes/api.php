<?php

use App\Http\Controllers\Api\ApiFileController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WasitController;
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

Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware(['jwt.verify'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // referee
    Route::prefix('wasit')->group(function () {
        Route::get('/', [WasitController::class, 'index'])->name('api.wasit.index');
        Route::get('/show/{id}', [WasitController::class, 'show'])->name('api.wasit.show');
    });

    Route::prefix('file')->group(function () {
        Route::get('/photo-profile/{id}', [ApiFileController::class, 'getPhotoProfile'])->name('api.file.photo-profile');
    });
});

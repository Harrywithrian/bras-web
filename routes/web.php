<?php

use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\Documentation\ReferencesController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\IndexController;
use App\Http\Controllers\Master\RegionController;
use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\Master\LicenseLocation;
use App\Http\Controllers\Master\ViolationController;
use App\Http\Controllers\Master\IotController;

use App\Http\Controllers\Master\MGameManagementController;
use App\Http\Controllers\Master\MMechanicalCourtController;
use App\Http\Controllers\Master\MAppearanceController;

use App\Http\Controllers\Transaksi\TMatchController;

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

//$menu = theme()->getMenu();
//array_walk($menu, function ($val) {
//    if (isset($val['path'])) {
//        $route = Route::get($val['path'], [PagesController::class, 'index']);
//
//        // Exclude documentation from auth middleware
//        if (!Str::contains($val['path'], 'documentation')) {
//            $route->middleware('auth');
//        }
//    }
//});

Route::middleware('auth')->group(function () {
    Route::get('/', [IndexController::class, 'index'])->name('index');

    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    // Logs pages
    Route::prefix('log')->name('log.')->group(function () {
        Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
        Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
    });

    Route::prefix('region')->group(function () {
        Route::get('/', [RegionController::class, 'index'])->name('region.index');
        Route::get('/index', [RegionController::class, 'index'])->name('region.index');
        Route::get('/get', [RegionController::class, 'get'])->name('region.get');
        Route::post('/search', [RegionController::class, 'search'])->name('region.search');
        Route::get('/create', [RegionController::class, 'create'])->name('region.create');
        Route::post('/store', [RegionController::class, 'store'])->name('region.store');
        Route::get('/show/{id}', [RegionController::class, 'show'])->name('region.show');
        Route::get('/edit/{id}', [RegionController::class, 'edit'])->name('region.edit');
        Route::post('/update/{id}', [RegionController::class, 'update'])->name('region.update');
        Route::post('/status', [RegionController::class, 'status'])->name('region.status');
        Route::post('/delete', [RegionController::class, 'delete'])->name('region.delete');
    });

    Route::prefix('location')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('location.index');
        Route::get('/index', [LocationController::class, 'index'])->name('location.index');
        Route::get('/get', [LocationController::class, 'get'])->name('location.get');
        Route::post('/search', [LocationController::class, 'search'])->name('location.search');
        Route::get('/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/store', [LocationController::class, 'store'])->name('location.store');
        Route::get('/show/{id}', [LocationController::class, 'show'])->name('location.show');
        Route::get('/edit/{id}', [LocationController::class, 'edit'])->name('location.edit');
        Route::post('/update/{id}', [LocationController::class, 'update'])->name('location.update');
        Route::post('/status', [LocationController::class, 'status'])->name('location.status');
        Route::post('/delete', [LocationController::class, 'delete'])->name('location.delete');
    });

    Route::prefix('license')->group(function () {
        Route::get('/', [LicenseLocation::class, 'index'])->name('license.index');
        Route::get('/index', [LicenseLocation::class, 'index'])->name('license.index');
        Route::get('/get', [LicenseLocation::class, 'get'])->name('license.get');
        Route::post('/search', [LicenseLocation::class, 'search'])->name('license.search');
        Route::get('/create', [LicenseLocation::class, 'create'])->name('license.create');
        Route::post('/store', [LicenseLocation::class, 'store'])->name('license.store');
        Route::get('/show/{id}', [LicenseLocation::class, 'show'])->name('license.show');
        Route::get('/edit/{id}', [LicenseLocation::class, 'edit'])->name('license.edit');
        Route::post('/update/{id}', [LicenseLocation::class, 'update'])->name('license.update');
        Route::post('/status', [LicenseLocation::class, 'status'])->name('license.status');
        Route::post('/delete', [LicenseLocation::class, 'delete'])->name('license.delete');
    });

    Route::prefix('violation')->group(function () {
        Route::get('/', [ViolationController::class, 'index'])->name('violation.index');
        Route::get('/index', [ViolationController::class, 'index'])->name('violation.index');
        Route::get('/get', [ViolationController::class, 'get'])->name('violation.get');
        Route::post('/search', [ViolationController::class, 'search'])->name('violation.search');
        Route::get('/create', [ViolationController::class, 'create'])->name('violation.create');
        Route::post('/store', [ViolationController::class, 'store'])->name('violation.store');
        Route::get('/show/{id}', [ViolationController::class, 'show'])->name('violation.show');
        Route::get('/edit/{id}', [ViolationController::class, 'edit'])->name('violation.edit');
        Route::post('/update/{id}', [ViolationController::class, 'update'])->name('violation.update');
        Route::post('/status', [ViolationController::class, 'status'])->name('violation.status');
        Route::post('/delete', [ViolationController::class, 'delete'])->name('violation.delete');
    });

    Route::prefix('iot')->group(function () {
        Route::get('/', [IotController::class, 'index'])->name('iot.index');
        Route::get('/index', [IotController::class, 'index'])->name('iot.index');
        Route::get('/get', [IotController::class, 'get'])->name('iot.get');
        Route::post('/search', [IotController::class, 'search'])->name('iot.search');
        Route::get('/create', [IotController::class, 'create'])->name('iot.create');
        Route::post('/store', [IotController::class, 'store'])->name('iot.store');
        Route::get('/show/{id}', [IotController::class, 'show'])->name('iot.show');
        Route::get('/edit/{id}', [IotController::class, 'edit'])->name('iot.edit');
        Route::post('/update/{id}', [IotController::class, 'update'])->name('iot.update');
        Route::post('/status', [IotController::class, 'status'])->name('iot.status');
        Route::post('/delete', [IotController::class, 'delete'])->name('iot.delete');
    });

    Route::prefix('m-game-management')->group(function () {
        Route::get('/', [MGameManagementController::class, 'index'])->name('m-game-management.index');
        Route::get('/index', [MGameManagementController::class, 'index'])->name('m-game-management.index');
        Route::get('/preview', [MGameManagementController::class, 'preview'])->name('m-game-management.preview');
        Route::get('/get', [MGameManagementController::class, 'get'])->name('m-game-management.get');
        Route::post('/search', [MGameManagementController::class, 'search'])->name('m-game-management.search');
        Route::get('/show/{id}', [MGameManagementController::class, 'show'])->name('m-game-management.show');
        Route::get('/create-header', [MGameManagementController::class, 'createHeader'])->name('m-game-management.create-header');
        Route::post('/store-header', [MGameManagementController::class, 'storeHeader'])->name('m-game-management.store-header');
        Route::get('/create-content', [MGameManagementController::class, 'createContent'])->name('m-game-management.create-content');
        Route::post('/store-content', [MGameManagementController::class, 'storeContent'])->name('m-game-management.store-content');
        Route::get('/edit-header/{id}', [MGameManagementController::class, 'editHeader'])->name('m-game-management.edit-header');
        Route::post('/update-header/{id}', [MGameManagementController::class, 'updateHeader'])->name('m-game-management.update-header');
        Route::get('/edit-content/{id}', [MGameManagementController::class, 'editContent'])->name('m-game-management.edit-content');
        Route::post('/update-content/{id}', [MGameManagementController::class, 'updateContent'])->name('m-game-management.update-content');
        Route::post('/delete', [MGameManagementController::class, 'delete'])->name('m-game-management.delete');
    });

    Route::prefix('m-mechanical-court')->group(function () {
        Route::get('/', [MMechanicalCourtController::class, 'index'])->name('m-mechanical-court.index');
        Route::get('/index', [MMechanicalCourtController::class, 'index'])->name('m-mechanical-court.index');
        Route::get('/preview', [MMechanicalCourtController::class, 'preview'])->name('m-mechanical-court.preview');
        Route::get('/get', [MMechanicalCourtController::class, 'get'])->name('m-mechanical-court.get');
        Route::post('/search', [MMechanicalCourtController::class, 'search'])->name('m-mechanical-court.search');
        Route::get('/create', [MMechanicalCourtController::class, 'create'])->name('m-mechanical-court.create');
        Route::post('/store', [MMechanicalCourtController::class, 'store'])->name('m-mechanical-court.store');
        Route::get('/show/{id}', [MMechanicalCourtController::class, 'show'])->name('m-mechanical-court.show');
        Route::get('/create-header', [MMechanicalCourtController::class, 'createHeader'])->name('m-mechanical-court.create-header');
        Route::post('/store-header', [MMechanicalCourtController::class, 'storeHeader'])->name('m-mechanical-court.store-header');
        Route::get('/create-content', [MMechanicalCourtController::class, 'createContent'])->name('m-mechanical-court.create-content');
        Route::post('/store-content', [MMechanicalCourtController::class, 'storeContent'])->name('m-mechanical-court.store-content');
        Route::get('/edit-header/{id}', [MMechanicalCourtController::class, 'editHeader'])->name('m-mechanical-court.edit-header');
        Route::post('/update-header/{id}', [MMechanicalCourtController::class, 'updateHeader'])->name('m-mechanical-court.update-header');
        Route::get('/edit-content/{id}', [MMechanicalCourtController::class, 'editContent'])->name('m-mechanical-court.edit-content');
        Route::post('/update-content/{id}', [MMechanicalCourtController::class, 'updateContent'])->name('m-mechanical-court.update-content');
        Route::post('/delete', [MMechanicalCourtController::class, 'delete'])->name('m-mechanical-court.delete');
    });

    Route::prefix('m-appearance')->group(function () {
        Route::get('/', [MAppearanceController::class, 'index'])->name('m-appearance.index');
        Route::get('/index', [MAppearanceController::class, 'index'])->name('m-appearance.index');
        Route::get('/preview', [MAppearanceController::class, 'preview'])->name('m-appearance.preview');
        Route::get('/get', [MAppearanceController::class, 'get'])->name('m-appearance.get');
        Route::post('/search', [MAppearanceController::class, 'search'])->name('m-appearance.search');
        Route::get('/create-header', [MAppearanceController::class, 'createHeader'])->name('m-appearance.create-header');
        Route::post('/store-header', [MAppearanceController::class, 'storeHeader'])->name('m-appearance.store-header');
        Route::get('/create-content', [MAppearanceController::class, 'createContent'])->name('m-appearance.create-content');
        Route::post('/store-content', [MAppearanceController::class, 'storeContent'])->name('m-appearance.store-content');
        Route::get('/show/{id}', [MAppearanceController::class, 'show'])->name('m-appearance.show');
        Route::get('/edit-header/{id}', [MAppearanceController::class, 'editHeader'])->name('m-appearance.edit-header');
        Route::post('/update-header/{id}', [MAppearanceController::class, 'updateHeader'])->name('m-appearance.update-header');
        Route::get('/edit-content/{id}', [MAppearanceController::class, 'editContent'])->name('m-appearance.edit-content');
        Route::post('/update-content/{id}', [MAppearanceController::class, 'updateContent'])->name('m-appearance.update-content');
        Route::post('/delete', [MAppearanceController::class, 'delete'])->name('m-appearance.delete');
    });

    Route::prefix('t-match')->group(function () {
        Route::get('/event', [TMatchController::class, 'event'])->name('t-match.event');
        Route::get('/get-event', [TMatchController::class, 'getEvent'])->name('t-match.get-event');
        Route::get('/search-event', [TMatchController::class, 'searchEvent'])->name('t-match.search-event');
        Route::get('/show-event', [TMatchController::class, 'showEvent'])->name('t-match.show-event');
    });
});

Route::resource('users', UsersController::class);

/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__.'/auth.php';

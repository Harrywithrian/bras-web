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
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Master\RegionController;
use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\Master\LicenseLocation;
use App\Http\Controllers\Master\ViolationController;
use App\Http\Controllers\Master\IotController;

use App\Http\Controllers\Master\MGameManagementController;
use App\Http\Controllers\Master\MMechanicalCourtController;
use App\Http\Controllers\Master\MAppearanceController;

use App\Http\Controllers\Transaksi\TApprovalController;
use App\Http\Controllers\Transaksi\TEventController;
use App\Http\Controllers\Transaksi\TEventApprovalController;
use App\Http\Controllers\Transaksi\TEventLetterController;
use App\Http\Controllers\Transaksi\TMatchController;
use App\Http\Controllers\Transaksi\TPlayCallingController;
use App\Http\Controllers\Transaksi\TGameManagementController;
use App\Http\Controllers\Transaksi\TMechanicalCourtController;
use App\Http\Controllers\Transaksi\TAppearanceController;
use App\Http\Controllers\Master\WasitController;
use App\Http\Controllers\Master\ProfileController;

use App\Http\Controllers\Transaksi\ReportPertandinganController;
use App\Http\Controllers\Transaksi\ReportWasitController;

use App\Http\Controllers\Transaksi\TNotifikasiController;

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

    Route::prefix('profile')->group(function () {
        Route::get('/index/{id}', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/match/{id}', [ProfileController::class, 'match'])->name('profile.match');
        Route::get('/show-match/{id}/{wasit}', [ProfileController::class, 'showMatch'])->name('profile.show-match');
        Route::post('/search-match', [ProfileController::class, 'searchMatch'])->name('profile.search-match');
        Route::get('/print-match/{id}/{wasit}', [ProfileController::class, 'printMatch'])->name('profile.print-match');
    });

    Route::prefix('m-user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('m-user.index');
        Route::get('/index', [UserController::class, 'index'])->name('m-user.index');
        Route::get('/get', [UserController::class, 'get'])->name('m-user.get');
        Route::post('/search', [UserController::class, 'search'])->name('m-user.search');
        Route::get('/create', [UserController::class, 'create'])->name('m-user.create');
        Route::post('/store', [UserController::class, 'store'])->name('m-user.store');
        Route::get('/show/{id}', [UserController::class, 'show'])->name('m-user.show');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('m-user.edit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('m-user.update');
        Route::post('/status', [UserController::class, 'status'])->name('m-user.status');
        Route::post('/lock', [UserController::class, 'lock'])->name('m-user.lock');
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

    Route::prefix('wasit')->group(function () {
        Route::get('/', [WasitController::class, 'index'])->name('wasit.index');
        Route::get('/index', [WasitController::class, 'index'])->name('wasit.index');
        Route::get('/get', [WasitController::class, 'get'])->name('wasit.get');
        Route::post('/search', [WasitController::class, 'search'])->name('wasit.search');
        Route::get('/show/{id}', [WasitController::class, 'show'])->name('wasit.show');
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

    Route::prefix('t-approval')->group(function () {
        Route::get('/index', [TApprovalController::class, 'index'])->name('t-approval.index');
        Route::get('/get', [TApprovalController::class, 'get'])->name('t-approval.get');
        Route::post('/search', [TApprovalController::class, 'search'])->name('t-approval.search');
        Route::get('/show/{id}', [TApprovalController::class, 'show'])->name('t-approval.show');
        Route::post('/approve', [TApprovalController::class, 'approve'])->name('t-approval.approve');
        Route::post('/reject', [TApprovalController::class, 'reject'])->name('t-approval.reject');
        Route::get('/download-lisensi/{id}', [TApprovalController::class, 'downloadLisensi'])->name('t-approval.download-lisensi');
    });

    Route::prefix('t-event')->group(function () {
        Route::get('/', [TEventController::class, 'index'])->name('t-event.index');
        Route::get('/index', [TEventController::class, 'index'])->name('t-event.index');
        Route::get('/get', [TEventController::class, 'get'])->name('t-event.get');
        Route::post('/search', [TEventController::class, 'search'])->name('t-event.search');
        Route::get('/show/{id}', [TEventController::class, 'show'])->name('t-event.show');
        Route::get('/create', [TEventController::class, 'create'])->name('t-event.create');
        Route::post('/store', [TEventController::class, 'store'])->name('t-event.store');
        Route::get('/edit/{id}', [TEventController::class, 'edit'])->name('t-event.edit');
        Route::post('/update/{id}', [TEventController::class, 'update'])->name('t-event.update');
        Route::post('/delete', [TEventController::class, 'delete'])->name('t-event.delete');
    });

    Route::prefix('t-event-approval')->group(function () {
        Route::get('/', [TEventApprovalController::class, 'index'])->name('t-event-approval.index');
        Route::get('/index', [TEventApprovalController::class, 'index'])->name('t-event-approval.index');
        Route::get('/get', [TEventApprovalController::class, 'get'])->name('t-event-approval.get');
        Route::post('/search', [TEventApprovalController::class, 'search'])->name('t-event-approval.search');
        Route::get('/show/{id}', [TEventApprovalController::class, 'show'])->name('t-event-approval.show');
        Route::post('/approve', [TEventApprovalController::class, 'approve'])->name('t-event-approval.approve');
        Route::post('/reject', [TEventApprovalController::class, 'reject'])->name('t-event-approval.reject');
        Route::post('/mail', [TEventApprovalController::class, 'mail'])->name('t-event-approval.mail');
    });

    Route::prefix('t-event-letter')->group(function () {
        Route::get('/', [TEventLetterController::class, 'index'])->name('t-event-letter.index');
        Route::get('/index', [TEventLetterController::class, 'index'])->name('t-event-letter.index');
        Route::get('/get', [TEventLetterController::class, 'get'])->name('t-event-letter.get');
        Route::post('/search', [TEventLetterController::class, 'search'])->name('t-event-letter.search');
        Route::get('/show/{id}', [TEventLetterController::class, 'show'])->name('t-event-letter.show');
        Route::get('/dokumen/{id}', [TEventLetterController::class, 'dokumen'])->name('t-event-letter.dokumen');
        Route::get('/send/{id}', [TEventLetterController::class, 'send'])->name('t-event-letter.send');
        Route::post('/update', [TEventLetterController::class, 'update'])->name('t-event-letter.update');
    });

    Route::prefix('t-match')->group(function () {
        Route::get('/', [TMatchController::class, 'IndexEvent'])->name('t-match.index-event');
        Route::get('/index-event', [TMatchController::class, 'IndexEvent'])->name('t-match.index-event');
        Route::get('/get-event', [TMatchController::class, 'getEvent'])->name('t-match.get-event');
        Route::post('/search-event', [TMatchController::class, 'searchEvent'])->name('t-match.search-event');
        Route::get('/done-event/{id}', [TMatchController::class, 'doneEvent'])->name('t-match.done-event');
        Route::get('/index/{id}', [TMatchController::class, 'index'])->name('t-match.index');
        Route::get('/get/{id}', [TMatchController::class, 'get'])->name('t-match.get');
        Route::post('/search', [TMatchController::class, 'search'])->name('t-match.search');
        Route::get('/create/{id}', [TMatchController::class, 'create'])->name('t-match.create');
        Route::post('/store/{id}', [TMatchController::class, 'store'])->name('t-match.store');
        Route::get('/show/{id}', [TMatchController::class, 'show'])->name('t-match.show');
        Route::get('/done/{id}', [TMatchController::class, 'done'])->name('t-match.done');
        Route::get('/show-evaluation/{id}/{wasit}', [TMatchController::class, 'showEvaluation'])->name('t-match.show-evaluation');
        Route::get('/evaluation/{id}', [TMatchController::class, 'evaluation'])->name('t-match.evaluation');
        Route::get('/notes-evaluation/{id}', [TMatchController::class, 'notesEvaluation'])->name('t-match.notes-evaluation');
        Route::post('/submit-notes-evaluation/{id}', [TMatchController::class, 'submitNotesEvaluation'])->name('t-match.submit-notes-evaluation');
        Route::get('/{id}/play-calling/evaluation', [TPlayCallingController::class, 'create'])->name('t-match.play-calling.create');
        Route::post('/{id}/play-calling/evaluation', [TPlayCallingController::class, 'store'])->name('t-match.play-calling.store');
        Route::get('/{id}/play-calling/{referee}/summary', [TPlayCallingController::class, 'summary'])->name('t-match.play-calling.summary');
        Route::get('/{id}/play-calling/{referee}/evaluation', [TPlayCallingController::class, 'edit'])->name('t-match.play-calling.edit');
    });

    Route::prefix('game-management')->group(function () {
        Route::get('/show/{id}/{wasit}', [TGameManagementController::class, 'show'])->name('game-management.show');
        Route::get('/create/{id}', [TGameManagementController::class, 'create'])->name('game-management.create');
        Route::post('/store/{id}', [TGameManagementController::class, 'store'])->name('game-management.store');
        Route::get('/edit/{id}/{wasit}', [TGameManagementController::class, 'edit'])->name('game-management.edit');
        Route::post('/update/{id}/{wasit}', [TGameManagementController::class, 'update'])->name('game-management.update');
    });

    Route::prefix('mechanical-court')->group(function () {
        Route::get('/show/{id}/{wasit}', [TMechanicalCourtController::class, 'show'])->name('mechanical-court.show');
        Route::get('/create/{id}', [TMechanicalCourtController::class, 'create'])->name('mechanical-court.create');
        Route::post('/store/{id}', [TMechanicalCourtController::class, 'store'])->name('mechanical-court.store');
        Route::get('/edit/{id}/{wasit}', [TMechanicalCourtController::class, 'edit'])->name('mechanical-court.edit');
        Route::post('/update/{id}/{wasit}', [TMechanicalCourtController::class, 'update'])->name('mechanical-court.update');
    });

    Route::prefix('appearance')->group(function () {
        Route::get('/show/{id}/{wasit}', [TAppearanceController::class, 'show'])->name('appearance.show');
        Route::get('/create/{id}', [TAppearanceController::class, 'create'])->name('appearance.create');
        Route::post('/store/{id}', [TAppearanceController::class, 'store'])->name('appearance.store');
        Route::get('/edit/{id}/{wasit}', [TAppearanceController::class, 'edit'])->name('appearance.edit');
        Route::post('/update/{id}/{wasit}', [TAppearanceController::class, 'update'])->name('appearance.update');
    });

    Route::prefix('report-pertandingan')->group(function () {
        Route::get('/', [ReportPertandinganController::class, 'IndexEvent'])->name('report-pertandingan.index-event');
        Route::get('/index-event', [ReportPertandinganController::class, 'IndexEvent'])->name('report-pertandingan.index-event');
        Route::get('/get-event', [ReportPertandinganController::class, 'getEvent'])->name('report-pertandingan.get-event');
        Route::post('/search-event', [ReportPertandinganController::class, 'searchEvent'])->name('report-pertandingan.search-event');
        Route::get('/index/{id}', [ReportPertandinganController::class, 'index'])->name('report-pertandingan.index');
        Route::get('/get/{id}', [ReportPertandinganController::class, 'get'])->name('report-pertandingan.get');
        Route::post('/search', [ReportPertandinganController::class, 'search'])->name('report-pertandingan.search');
        Route::get('/show/{id}', [ReportPertandinganController::class, 'show'])->name('report-pertandingan.show');
        Route::get('/cetak/{id}', [ReportPertandinganController::class, 'cetak'])->name('report-pertandingan.cetak');
    });

    Route::prefix('report-wasit')->group(function () {
        Route::get('/', [ReportWasitController::class, 'index'])->name('report-wasit.index');
        Route::get('/index', [ReportWasitController::class, 'index'])->name('report-wasit.index');
        Route::get('/get', [ReportWasitController::class, 'get'])->name('report-wasit.get');
        Route::post('/search', [ReportWasitController::class, 'search'])->name('report-wasit.search');
        Route::get('/show/{id}', [ReportWasitController::class, 'show'])->name('report-wasit.show');
        Route::get('/get-match/{id}', [ReportWasitController::class, 'getMatch'])->name('report-wasit.get-match');
        Route::post('/search-match', [ReportWasitController::class, 'searchMatch'])->name('report-wasit.search-match');
        Route::get('/show-match/{id}/{wasit}', [ReportWasitController::class, 'showMatch'])->name('report-wasit.show-match');
        Route::get('/cetak/{id}/{wasit}', [ReportWasitController::class, 'cetak'])->name('report-wasit.cetak');
    });

    Route::prefix('notifikasi')->group(function () {
        Route::get('/event/{id}', [TNotifikasiController::class, 'event'])->name('notifikasi.event');
        Route::get('/match/{id}', [TNotifikasiController::class, 'match'])->name('notifikasi.match');
        Route::post('/reply-event/{id}', [TNotifikasiController::class, 'replyEvent'])->name('notifikasi.reply-event');
        Route::post('/reply-match/{id}', [TNotifikasiController::class, 'replyMatch'])->name('notifikasi.reply-match');
    });
});

Route::resource('users', UsersController::class);

/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__.'/auth.php';

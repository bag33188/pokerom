<?php

use App\Http\Controllers\Api\GameController;
use App\Http\Controllers\Api\RomController;
use App\Http\Controllers\Api\RomFileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

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

Route::name('api.')->group(function () {

    Route::get(
        '/version',
        fn() => response()->json(
            data: [
                'success' => true,
                'version' => config('app.version')
            ],
            status: HttpStatus::HTTP_OK,
            headers: array('X-Api-Version' => sprintf(
                "%s Version %.2f",
                config('app.name'), config('app.version')
            ))
        )
    )->name('version');

    // NON-PROTECTED ROUTES
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::post('/register', [UserController::class, 'register'])->name('register');
    });

    // PROTECTED ROUTES
    Route::middleware('auth:sanctum')->group(function () {

        Route::controller(UserController::class)->group(function () {
            Route::prefix('auth')->name('auth.')->group(function () {
                Route::get('/me', 'me')->name('me');
                Route::post('/logout', 'logout')->name('logout');
            });
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{userId}', 'show')->name('show');
                Route::put('/{userId}', 'update')->name('update');
                Route::patch('/{userId}', 'update')->name('edit');
                Route::delete('/{userId}', 'destroy')->name('destroy');
            });
        });

        Route::controller(RomController::class)->prefix('roms')->name('roms.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{romId}', 'show')->name('show');
            Route::post('/', 'store')->name('store');
            Route::put('/{romId}', 'update')->name('update');
            Route::patch('/{romId}', 'update')->name('edit');
            Route::delete('/{romId}', 'destroy')->name('destroy');
            Route::patch('/{romId}/link-file', 'linkRomToRomFile')->name('linkFile');
        });

        Route::controller(GameController::class)->prefix('games')->name('games.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{gameId}', 'show')->name('show');
            Route::put('/{gameId}', 'update')->name('update');
            Route::patch('/{gameId}', 'update')->name('edit');
            Route::post('/', 'store')->name('store');
            Route::delete('/{gameId}', 'destroy')->name('destroy');
        });

        Route::controller(RomFileController::class)->prefix('rom-files')->name('rom-files.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/upload', 'upload')->name('upload');
            Route::get('/{romFileId}', 'show')->name('show');
            Route::delete('/{romFileId}', 'destroy')->name('destroy');
            Route::get('/{romFileId}/download', 'download')->name('download');
        });

    });

});

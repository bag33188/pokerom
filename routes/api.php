<?php

use App\Http\Controllers\api\GameController;
use App\Http\Controllers\api\RomController;
use App\Http\Controllers\api\RomFileController;
use App\Http\Controllers\api\UserController;
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

        Route::name('games.')->group(function () {

            Route::get('/games', [GameController::class,'index'])->name('index');
            Route::get('/games/{gameId}', [GameController::class,'show'])->name('show');
            Route::put('/games/{gameId}', [GameController::class,'update'])->name('update');
            Route::patch('/games/{gameId}', [GameController::class,'update'])->name('edit');
            Route::post('/games', [GameController::class,'store'])->name('store');
            Route::delete('/games/{gameId}', [GameController::class,'destroy'])->name('destroy');

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

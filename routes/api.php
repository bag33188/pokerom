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
    Route::get('/version', fn() => response()->json(['success' => true, 'version' => config('app.version')], HttpStatus::HTTP_OK))->name('version');
    
    Route::name('auth.')->prefix('auth')->group(function () {
        Route::post('/login', [UserController::class, 'login'])->name('login');
        Route::post('/register', [UserController::class, 'register'])->name('register');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::name('auth.')->prefix('auth')->group(function () {
            Route::get('/me', [UserController::class, 'me'])->name('me');
            Route::post('/logout', [UserController::class, 'logout'])->name('logout');
        });

        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{userId}', [UserController::class, 'show'])->name('show');
            Route::put('/{userId}', [UserController::class, 'update'])->name('update');
            Route::patch('/{userId}', [UserController::class, 'update'])->name('edit');
            Route::delete('/{userId}', [UserController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('roms')->name('roms.')->group(function () {
            Route::get('/', [RomController::class, 'index'])->name('index');
            Route::get('/{romId}', [RomController::class, 'show'])->name('show');
            Route::post('/', [RomController::class, 'store'])->name('store');
            Route::put('/{romId}', [RomController::class, 'update'])->name('update');
            Route::patch('/{romId}', [RomController::class, 'update'])->name('edit');
            Route::delete('/{romId}', [RomController::class, 'destroy'])->name('destroy');
            Route::patch('/{romId}/link-file', [RomController::class, 'linkRomToRomFile'])->name('linkFile');
        });

        Route::prefix('games')->name('games.')->group(function () {
            Route::get('/', [GameController::class, 'index'])->name('index');
            Route::get('/{gameId}', [GameController::class, 'show'])->name('show');
            Route::put('/{gameId}', [GameController::class, 'update'])->name('update');
            Route::patch('/{gameId}', [GameController::class, 'update'])->name('edit');
            Route::post('/', [GameController::class, 'store'])->name('store');
            Route::delete('/{gameId}', [GameController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('rom-files')->name('rom-files.')->group(function () {
            Route::get('/', [RomFileController::class, 'index'])->name('index');
            Route::post('/upload', [RomFileController::class, 'upload'])->name('upload');
            Route::get('/{romFileId}', [RomFileController::class, 'show'])->name('show');
            Route::delete('/{romFileId}', [RomFileController::class, 'destroy'])->name('destroy');
            Route::get('/{romFileId}/download', [RomFileController::class, 'download'])->name('download');
        });

    });

});

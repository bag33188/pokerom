<?php

use App\Http\Controllers\api\GameController;
use App\Http\Controllers\api\RomController;
use App\Http\Controllers\api\UserController;
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

Route::name('api.')->group(function () {
    Route::post('/auth/login', [UserController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [UserController::class, 'me'])->name('auth.me');
        Route::post('/auth/logout', [UserController::class, 'logout'])->name('auth.logout');

        Route::get('/roms', [RomController::class, 'index'])->name('roms.index');
        Route::get('/roms/{romId}', [RomController::class, 'show'])->name('roms.show');
        Route::post('/roms', [RomController::class, 'store'])->name('roms.store');
        Route::put('/roms/{romId}', [RomController::class, 'update'])->name('roms.update');
        Route::patch('/roms/{romId}', [RomController::class, 'update'])->name('roms.edit');
        Route::delete('/roms/{romId}', [RomController::class, 'destroy'])->name('roms.destroy');
        Route::patch('/roms/{romId}/link-file', [RomController::class, 'linkRomToRomFile'])->name('roms.link');

        Route::get('/users/{userId}', [UserController::class, 'show'])->name('users.show');

        Route::get('/games', [GameController::class, 'index'])->name('games.index');
        Route::get('/games/{gameId}', [GameController::class, 'show'])->name('games.show');
        Route::put('/games/{gameId}', [GameController::class, 'update'])->name('games.update');
        Route::patch('/games/{gameId}', [GameController::class, 'update'])->name('games.edit');
        Route::post('/games', [GameController::class, 'store'])->name('games.store');
        Route::delete('/games/{gameId}', [GameController::class, 'destroy'])->name('games.destroy');
    });

});

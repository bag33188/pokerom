<?php

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
        Route::get('/roms/{romId}/show', [RomController::class, 'show'])->name('roms.show');
        Route::put('/roms/{romId}/update', [RomController::class, 'update'])->name('roms.update');
        Route::delete('/roms/{romId}/delete', [RomController::class, 'delete'])->name('roms.delete');
        Route::patch('/roms/{romId}/link-file', [RomController::class, 'linkRomToRomFile'])->name('roms.link');

        Route::get('/users/{userId}', [UserController::class, 'show'])->name('users.show');
    });

});

<?php

use App\Http\Controllers\api\RomController;
use App\Http\Controllers\api\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::name('api.')->group(function () {
    Route::post('/auth/login', [UserController::class, 'login'])->name('user.login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/auth/logout', [UserController::class, 'logout'])->name('user.logout');

        Route::get('/roms', [RomController::class, 'index'])->name('roms.index');
        Route::get('/roms/{rom}', [RomController::class, 'show'])->name('roms.show');
    });

});

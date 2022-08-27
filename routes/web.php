<?php

use App\Http\Controllers\web\RomController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/roms', [RomController::class, 'index'])->name('roms.index');
    Route::get('/roms/create', [RomController::class, 'create'])->name('roms.create');
    Route::post('/roms/store', [RomController::class, 'store'])->name('roms.store');
    Route::get('/rom-files/create', [\App\Http\Controllers\web\RomFileController::class, 'create'])->name('rom-files.create');
    Route::post('/rom-files/store', [\App\Http\Controllers\web\RomFileController::class, 'store'])->name('rom-files.store');
});

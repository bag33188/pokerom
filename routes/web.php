<?php

use App\Http\Controllers\web\GameController;
use App\Http\Controllers\web\HomeController;
use App\Http\Controllers\web\RomController;
use App\Http\Controllers\web\RomFileController;
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

Route::get('/', fn() => response()->redirectTo(route('welcome')))->middleware('guest');
Route::get('/welcome', fn() => view('welcome'))->middleware('guest')->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', HomeController::class)->name('dashboard');
    Route::get('/roms', [RomController::class, 'index'])->name('roms.index');
    Route::get('/roms/{rom}/show', [RomController::class, 'show'])->name('roms.show');
    Route::get('/roms/create', [RomController::class, 'create'])->name('roms.create')->middleware('admin');
    Route::get('/roms/{rom}/edit', [RomController::class, 'edit'])->name('roms.edit')->middleware('admin');
    Route::post('/roms/store', [RomController::class, 'store'])->name('roms.store');
    Route::put('/roms/{rom}', [RomController::class, 'update'])->name('roms.update');
    Route::delete('/roms/{rom}', [RomController::class, 'destroy'])->name('roms.destroy');

    Route::get('/rom-files', [RomFileController::class, 'index'])->name('rom-files.index');
    Route::get('/rom-files/{romFile}/show', [RomFileController::class, 'show'])->name('rom-files.show');
    Route::get('/rom-files/create', [RomFileController::class, 'create'])->name('rom-files.create')->middleware('admin');
    Route::post('/rom-files/store', [RomFileController::class, 'store'])->name('rom-files.store');
    Route::get('/rom-files/{romFile}/download', [RomFileController::class, 'download'])->name('rom-files.download');
    Route::delete('/rom-files/{romFile}', [RomFileController::class, 'destroy'])->name('rom-files.destroy');

    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/{game}/show', [GameController::class, 'show'])->name('games.show');
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create')->middleware('admin');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit')->middleware('admin');
    Route::post('/games/store', [GameController::class, 'store'])->name('games.store');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
});

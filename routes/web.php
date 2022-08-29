<?php

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
    Route::get('/roms/{rom}/show', [RomController::class, 'show'])->name('roms.show');
    Route::get('/roms/create', [RomController::class, 'create'])->name('roms.create')->middleware('admin');
    Route::get('/roms/{rom}/edit', [RomController::class, 'edit'])->name('roms.edit')->middleware('admin');
    Route::post('/roms/store', [RomController::class, 'store'])->name('roms.store');
    Route::put('/roms/{rom}', [RomController::class, 'update'])->name('roms.update');
    Route::delete('/roms/{rom}', [RomController::class, 'destroy'])->name('roms.destroy');

    Route::get('/rom-files', [RomFileController::class, 'index'])->name('rom-files.index');
    Route::get('/rom-files/create', [RomFileController::class, 'create'])->name('rom-files.create')->middleware('admin');;
    Route::post('/rom-files/store', [RomFileController::class, 'store'])->name('rom-files.store');
    Route::get('/rom-files/{romFile}/download', [RomFileController::class, 'download'])->name('rom-files.download');
    Route::delete('/rom-files/{romFile}', [RomFileController::class, 'destroy'])->name('rom-files.destroy');
});

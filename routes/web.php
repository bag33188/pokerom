<?php

use App\Http\Controllers\Web\GameController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\RomController;
use App\Http\Controllers\Web\RomFileController;
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

Route::middleware('guest')->group(function () {
    Route::get('/welcome', fn() => view('welcome'))->name('welcome');
    Route::get('/', fn() => response()->redirectTo(route('welcome')));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/home', HomeController::class)->name('home');

    Route::controller(RomController::class)->prefix('roms')->name('roms.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{rom}/show', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/{rom}/edit', 'edit')->name('edit');
        Route::post('/store', 'store')->name('store');
        Route::put('/{rom}', 'update')->name('update');
        Route::delete('/{rom}', 'destroy')->name('destroy');
        Route::patch('/{rom}/link-file', 'linkFile')->name('link-file');
    });

    Route::controller(GameController::class)->prefix('games')->name('games.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{game}/show', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::get('/{game}/edit', 'edit')->name('edit');
        Route::post('/store', 'store')->name('store');
        Route::put('/{game}', 'update')->name('update');
        Route::delete('/{game}', 'destroy')->name('destroy');
    });

    Route::controller(RomFileController::class)->prefix('rom-files')->name('rom-files.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{romFile}/show', 'show')->name('show');
        Route::get('/create', 'create')->name('create')->middleware('admin');
        Route::post('/store', 'store')->name('store');
        Route::get('/{romFile}/download', 'download')->name('download');
        Route::delete('/{romFile}', 'destroy')->name('destroy');
    });
});

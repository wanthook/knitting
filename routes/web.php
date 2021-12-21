<?php

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('administrator')->group(function () {
    Route::prefix('master-option')->group(function () {
        Route::get('list', [App\Http\Controllers\MasterOptionController::class, 'index'])
             ->name('masteroptionlist');
        Route::post('table', [App\Http\Controllers\MasterOptionController::class, 'dt'])
             ->name('masteroptiontable');
        Route::post('select-tipe', [App\Http\Controllers\MasterOptionController::class, 'selectTipe'])
             ->name('masteroptionselecttipe');
        Route::post('save', [App\Http\Controllers\MasterOptionController::class, 'store'])
            ->name('masteroptionsave');
        Route::post('delete', [App\Http\Controllers\MasterOptionController::class, 'destroy'])
            ->name('masteroptiondelete');
    });

    Route::prefix('module')->group(function () {
        Route::get('list', [App\Http\Controllers\ModuleController::class, 'index'])
             ->name('modulelist');
        Route::post('table', [App\Http\Controllers\ModuleController::class, 'dt'])
             ->name('moduletable');
        Route::post('select-parent', [App\Http\Controllers\ModuleController::class, 'selectparent'])
             ->name('moduleselectparent');
        Route::post('select-tree', [App\Http\Controllers\ModuleController::class, 'selecttree'])
             ->name('moduleselecttree');
        Route::post('save', [App\Http\Controllers\ModuleController::class, 'store'])
            ->name('modulesave');
        Route::post('delete', [App\Http\Controllers\ModuleController::class, 'destroy'])
            ->name('moduledelete');
    });

    Route::prefix('users')->group(function () {
        Route::get('list', [App\Http\Controllers\Auth\RegisterController::class, 'index'])
             ->name('userlist');
        Route::post('table', [App\Http\Controllers\Auth\RegisterController::class, 'dt'])
             ->name('usertable');
        Route::post('select', [App\Http\Controllers\Auth\RegisterController::class, 'select'])
             ->name('userselect');
        Route::post('save', [App\Http\Controllers\Auth\RegisterController::class, 'store'])
            ->name('usersave');
        Route::post('delete', [App\Http\Controllers\Auth\RegisterController::class, 'destroy'])
            ->name('userdelete');
        
        Route::post('tipe', [App\Http\Controllers\MasterOptionController::class, 'selectTypeUser'])
            ->name('usertipe');
    });
});
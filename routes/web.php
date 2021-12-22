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
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::middleware(['auth'])->group(function () {
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
                    
               Route::post('select-mrp', [App\Http\Controllers\MasterOptionController::class, 'selectMrpGroup'])
                    ->name('masteroptionselectmrp');
               Route::post('select-mtype', [App\Http\Controllers\MasterOptionController::class, 'selectMatType'])
               ->name('masteroptionselectmtype');
               Route::post('select-mgroup', [App\Http\Controllers\MasterOptionController::class, 'selectMatGroup'])
               ->name('masteroptionselectmgroup');
               Route::post('select-bunit', [App\Http\Controllers\MasterOptionController::class, 'selectBUnit'])
               ->name('masteroptionselectbunit');
               Route::post('select-valcl', [App\Http\Controllers\MasterOptionController::class, 'selectValCl'])
               ->name('masteroptionselectvalcl');
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

     Route::prefix('master')->group(function () {
          Route::prefix('material')->group(function () {
               Route::get('list', [App\Http\Controllers\MaterialController::class, 'index'])
               ->name('materiallist');
               Route::post('table', [App\Http\Controllers\MaterialController::class, 'dt'])
               ->name('materialtable');
               Route::post('select-tipe', [App\Http\Controllers\MaterialController::class, 'selectTipe'])
               ->name('materialselect');
               Route::post('save', [App\Http\Controllers\MaterialController::class, 'store'])
               ->name('materialsave');
               Route::post('upload', [App\Http\Controllers\MaterialController::class, 'storeUpload'])
               ->name('materialupload');
               Route::post('delete', [App\Http\Controllers\MaterialController::class, 'destroy'])
               ->name('materialdelete');
          });
     
     });

     Route::get('files/{kode}', ['as' => 'app.files', function ($kode)
     {
          $path = "";
          switch($kode)
          {
               case "file_template_material":
                    $path = storage_path('app').'/public/template_material.xlsx';
                    break;
          }
          if($path)
          {
               $header = ['Content-Type' => File::mimeType($path)];
               return Response::download($path,$kode.'.xlsx', $header);
          }
          else 
          {
               return abort(404);
          }
     }]);
});

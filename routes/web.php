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
               Route::post('select-country', [App\Http\Controllers\MasterOptionController::class, 'selectCountry'])
                    ->name('masteroptionselectcountry');
               Route::post('select-group', [App\Http\Controllers\MasterOptionController::class, 'selectGroup'])
                    ->name('masteroptionselectgroup');
               Route::post('select-workcenter', [App\Http\Controllers\MasterOptionController::class, 'selectWc'])
                    ->name('masteroptionselectwc');
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
               Route::post('select', [App\Http\Controllers\MaterialController::class, 'select'])
               ->name('materialselect');
               Route::post('save', [App\Http\Controllers\MaterialController::class, 'store'])
               ->name('materialsave');
               Route::post('upload', [App\Http\Controllers\MaterialController::class, 'storeUpload'])
               ->name('materialupload');
               Route::post('delete', [App\Http\Controllers\MaterialController::class, 'destroy'])
               ->name('materialdelete');

               Route::get('download/{id}', [App\Http\Controllers\MaterialController::class, 'download'])
               ->name('materialdownload');

               Route::post('table-upload', [App\Http\Controllers\MaterialController::class, 'dtUpload'])
               ->name('materialtableupload');
          });

           Route::prefix('customer')->group(function () {
               Route::get('list', [App\Http\Controllers\CustomerController::class, 'index'])
               ->name('customerlist');
               Route::post('table', [App\Http\Controllers\CustomerController::class, 'dt'])
               ->name('customertable');
               Route::post('select', [App\Http\Controllers\CustomerController::class, 'select'])
               ->name('customerselect');
               Route::post('save', [App\Http\Controllers\CustomerController::class, 'store'])
               ->name('customersave');
               Route::post('upload', [App\Http\Controllers\CustomerController::class, 'storeUpload'])
               ->name('customerupload');
               Route::post('delete', [App\Http\Controllers\CustomerController::class, 'destroy'])
               ->name('customerdelete');
          });
          
          Route::prefix('mesin')->group(function () {
               Route::get('list', [App\Http\Controllers\MesinController::class, 'index'])
               ->name('mesinlist');
               Route::post('table', [App\Http\Controllers\MesinController::class, 'dt'])
               ->name('mesintable');
               Route::post('select', [App\Http\Controllers\MesinController::class, 'select'])
               ->name('mesinselect');
               Route::post('save', [App\Http\Controllers\MesinController::class, 'store'])
               ->name('mesinsave');
               Route::post('upload', [App\Http\Controllers\MesinController::class, 'storeUpload'])
               ->name('mesinupload');
               Route::post('delete', [App\Http\Controllers\MesinController::class, 'destroy'])
               ->name('mesindelete');
          });
     
     });

     Route::prefix('transaksi')->group(function () {
          Route::prefix('spk-rajut')->group(function () {
               Route::get('list', [App\Http\Controllers\SpkRajutController::class, 'index'])
                    ->name('spkrajutlist');
               Route::get('edit-form/{id}', [App\Http\Controllers\SpkRajutController::class, 'edit'])
                    ->name('spkrajutedit');
               Route::get('add', [App\Http\Controllers\SpkRajutController::class, 'create'])
                    ->name('spkrajutadd');
               Route::post('table', [App\Http\Controllers\SpkRajutController::class, 'dt'])
                    ->name('spkrajuttable');
               Route::post('table-detail', [App\Http\Controllers\SpkRajutController::class, 'dtDetail'])
                    ->name('spkrajuttabledetail');
               Route::post('select', [App\Http\Controllers\SpkRajutController::class, 'select'])
                    ->name('spkrajutselect');
               Route::post('save', [App\Http\Controllers\SpkRajutController::class, 'store'])
                    ->name('spkrajutsave');
               Route::post('save-detail', [App\Http\Controllers\SpkRajutController::class, 'storeDetail'])
                    ->name('spkrajutsavedetail');
               Route::post('upload', [App\Http\Controllers\SpkRajutController::class, 'storeUpload'])
                    ->name('spkrajutupload');
               Route::post('delete', [App\Http\Controllers\SpkRajutController::class, 'destroy'])
                    ->name('spkrajutdelete');
               Route::post('delete-detail', [App\Http\Controllers\SpkRajutController::class, 'destroyDetail'])
                    ->name('spkrajutdeletedetail');
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
               case "file_template_customer":
                    $path = storage_path('app').'/public/template_customer.xlsx';
                    break;
               case "file_template_mesin":
                    $path = storage_path('app').'/public/template_mesin.xlsx';
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
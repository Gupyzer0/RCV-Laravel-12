<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliciesController;
// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\InventoryController;
// use App\Http\Controllers\PricesController;
use App\Http\Controllers\OfficesController;
// use App\Http\Controllers\VehicleController;
// use App\Http\Controllers\VehicleTypesController;
// use App\Http\Controllers\VehicleClassesController;
use App\Http\Controllers\PaymentsController;
// use App\Http\Controllers\Admin\PagosController;
// use App\Http\Controllers\Admin\FacturacionController;
// use App\Http\Controllers\AccidentsController;
// use App\Http\Controllers\FinanceController;
// use App\Http\Controllers\Moderador\ModeratorController;
// use App\Http\Controllers\ChangeUsersPassword;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyController;
use App\Http\Controllers\PolizasController;

// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Route::get('/dashboard', [PoliciesController::class, 'index'])->name('dashboard');
/*
|--------------------------------------------------------------------------
| admin routes
|--------------------------------------------------------------------------
*/

// Route::get('/lider', 'Auth\AdminLoginController@showLoginForm')->name('admin.login');
// Route::post('/lider', 'Auth\AdminLoginController@login')->name('admin.login.submit');

// Route::get('/supervisor', 'Auth\ModeratorLoginController@showLoginForm')->name('mod.login');
// Route::post('/supervisor', 'Auth\ModeratorLoginController@login')->name('mod.login.submit');

Route::get('/registrar', [PoliciesController::class, 'create_client']);
// Route::post('/registrar-poliza', [PoliciesController::class, 'store'])->name('user.register.policy.submit');

Auth::routes();
// Route::get('/', 'Auth\LoginController@showLoginForm');
// Route::post('/', 'Auth\LoginController@login')->name('login');
// Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/v/{id}', [VerifyController::class, 'show_policy']);
Route::get('/download/{id}', [VerifyController::class, 'showPolicyPdf'])->name('policy.download');
Route::get('/condiciones', [VerifyController::class, 'downloadConditions'])->name('policy.download.conditions');

// Route::get('/c', function () {
//     $path = public_path('/uploads/condi.pdf'); // Asegúrate de que el archivo está en /public
//     return response()->download($path);
// });

// Polizas
Route::middleware(['auth'])->group(function () {
    
    Route::get('/polizas', [PolizasController::class, 'index'])->name('polizas.index');

    Route::middleware(['can:view,poliza'])->group(function () {
        Route::get('/polizas/{poliza}', [PolizasController::class, 'show'])->name('polizas.show');
        Route::get('/polizas/{poliza}/reportes/pdf', [PolizasController::class, 'pdf'])->name('polizas.pdf');
        Route::get('/polizas/{poliza}/reportes/pdf-digital', [PolizasController::class, 'pdf_digital'])->name('polizas.pdf-digital');
    });
   
    Route::middleware(['can:update,poliza'])->group(function () {
        Route::get('/polizas/{poliza}/edit', [PolizasController::class, 'edit'])->name('polizas.edit');
        Route::put('/polizas/{poliza}', [PolizasController::class, 'update'])->name('polizas.update');
    });
    
    Route::middleware(['can:delete,poliza'])->group(function () {
        Route::delete('/polizas/{poliza}', [PolizasController::class, 'delete'])->name('polizas.delete');
        Route::patch('/polizas/{poliza}/anular', [PolizasController::class, 'anular'])->name('polizas.anular');
        Route::patch('/polizas/{poliza}/desanular', [PolizasController::class, 'desanular'])->name('polizas.desanular');
    });
});


//Consultas AJAX
Route::post('/validate-p2p/{policy}', [PaymentsController::class, 'validateP2P'])->name('api.validate-p2p');
Route::get('/register-office/search-municipio', [OfficesController::class, 'search_municipio'])->name('ajax.office.search.municipio');
Route::get('/register-office/search-parroquia', [OfficesController::class, 'search_parroquia'])->name('ajax.office.search.parroquia');
Route::get('/register-policy/search', [PoliciesController::class, 'search'])->name('ajax.policy.search.vehicle');
Route::get('/register-policy/search-type', [PoliciesController::class, 'search_type'])->name('ajax.policy.search.vehicle.type');
Route::get('/register-policy/price-select', [PoliciesController::class, 'price_select'])->name('ajax.policy.price.select');
Route::get('/register-policy/price-adminselect', [PoliciesController::class, 'price_adminselect'])->name('ajax.policy.price.adminselect');
Route::get('/register-policy/price-view', [PoliciesController::class, 'price_view'])->name('ajax.policy.price.view');

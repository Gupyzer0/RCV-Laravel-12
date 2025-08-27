<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\Moderador\ModeratorController;
use App\Http\Controllers\LocationController;

use App\Http\Controllers\Moderador\PolizasController;
use App\Http\Controllers\Moderador\UsersController;
use App\Http\Controllers\Moderador\PagosController;

/**
 * Rutas de moderadores (supervisores)
 */
Route::prefix('mod')->middleware('role:moderador')->group(function () {
    // Ruta Polizas

    Route::get('/polizas', [PolizasController::class, 'index'])->name('moderador.polizas.index');
    // DELETE Route::get('/inde-search', [ModeratorController::class, 'index_search'])->name('mod.search.policies');
    Route::get('/polizas/{policy}', [PolizasController::class, 'show'])->name('moderador.polizas.show'); //policy.show.mod
    // C Route::get('/pdf-digital/{id}', [ModeratorController::class, 'exportd_modpdf'])->name('policy.exportd.pdf');
    Route::get('/polizas/{policy}/pdf', [PolizasController::class, 'pdf'])->name('moderador.polizas.pdf');
    //Route::get('/edit-policyd/{id}', [ModeratorController::class, 'edit'])->name('mod.edit.policy');
    Route::get('/polizas/{policy}/edit', [PolizasController::class, 'edit'])->name('moderador.polizas.edit');
    // Route::put('/edit-policyd/{id}', [ModeratorController::class, 'update'])->name('mod.update.policy');
    Route::put('/polizas/{policy}', [PolizasController::class, 'update'])->name('moderador.polizas.update');
    
    /* ? */ Route::post('/restored-policy/{id}', [ModeratorController::class, 'nanular_mod'])->name('restored.policy');
    /* ? */ Route::post('/anular-policy/{id}', [PoliciesController::class, 'anular_user'])->name('user.anular.policy');

    //user
    // Route::get('/index-usersd', [UsersController::class, 'index_users_mod'])->name('index.users.mod');
    Route::get('/users', [UsersController::class, 'index'])->name('moderador.users.index');
    // Route::get('/register-user', [UsersController::class, 'showRegistrationForm'])->name('register.user.mod');
    Route::get('/users/create', [UsersController::class, 'create'])->name('moderador.users.create');
    // Route::post('/register-user', [UsersController::class, 'register'])->name('register.mod');
    Route::post('/users', [UsersController::class, 'store'])->name('moderador.users.store');
    // Route::get('/cant-contram/{id}', [UsersController::class, 'mod_cant'])->name('mod.cant.contratos');
    Route::post('/users/{user}/agregar-contratos', [UsersController::class, 'agregar_contratos'])->name('moderador.users.agregar-contratos');
    // Route::get('/edit-contram/{id}', [UsersController::class, 'edit_contra'])->name('mod.edit.contratos');
    Route::post('/users/{user}/editar-numero-contratos', [UsersController::class, 'editar_numero_contratos'])->name('moderador.users.editar-numero-contratos');
    // Route::get('/pdf-user/{id}', [UsersController::class, 'pdf_user'])->name('export.pdf.user.mod');
    Route::get('/users/{user}/pdf', [UsersController::class, 'pdf'])->name('moderador.users.pdf');

    //consulta de pagos
    //Route::get('/index-payments/notpaidd', [PagosController::class, 'index_not_paid_mod'])->name('index.notpaid.mod');
    Route::get('/pagos-pendientes', [PagosController::class, 'index_pendientes'])->name('moderador.pagos.index-pendientes');
    //Route::get('/index-paymentd/not-paid/{id}', [ModeratorController::class, 'show_not_paid_mod'])->name('mod.show.notpaid');
    Route::get('/pagos-pendientes/{user}', [PagosController::class, 'index_pendientes_por_usuario'] )->name('moderador.pagos.pendientes-por-usuario');

    Route::post('/selected-pay/{id}', [ModeratorController::class, 'selected_pay'])->name('selectedm.pay.submit');


    Route::get('/exportd-payments/{id}', [ModeratorController::class, 'mod_exportpdf'])->name('mod.payments.export');

    
    Route::get('/index-paymentd/report/{id}', [ModeratorController::class, 'report_mod'])->name('mod.report.policies');
    Route::post('/payments-reports', [ModeratorController::class, 'report_all_mod'])->name('mod.report.all.policies');
    Route::get('/search-payments/notpaid', [ModeratorController::class, 'search_usersnopaid'])->name('mod.search.notpaid');

    Route::get('/index-pricesd', [PricesController::class, 'index_mod'])->name('index.prices.mod');

    Route::get('/general-paymentsd', [ModeratorController::class, 'general_policies'])->name('mod.general.payments');
    Route::get('/payments-vendedord', [ModeratorController::class, 'searchVendedord'])->name('mod.vendedor.seach1');


    Route::get('/locationd', [LocationController::class, 'showMap_mod'])->name('map.mod');

    // Rutas Oficinas
    Route::get('/index-offices', [ModeratorController::class, 'index_office'])->name('index.offices.mod');
    Route::get('/register-office', [ModeratorController::class, 'create_office'])->name('register.office.mod');
    Route::post('/register-office', [ModeratorController::class, 'store_office'])->name('register.office.submit.mod');
    Route::get('/edit-office/{id}', [ModeratorController::class, 'edit_office'])->name('admin.edit.office.mod');
    Route::put('/edit-office/{id}', [ModeratorController::class, 'update_office'])->name('admin.update.office.mod');
    Route::get('/', [ModeratorController::class, 'index'])->name('inicio');

    //Inventario
    Route::get('/index-inventory', [InventoryController::class, 'index_mod'])->name('index.inventory.mod');
    Route::get('/index-inventory/{id}', [InventoryController::class, 'show_inventory_mod'])->name('show.inventory.mod');
    Route::get('/edit-inventory/{id}', [InventoryController::class, 'edit_mod'])->name('edit.inventory.mod');
    Route::get('/edit-inventory/{id}', [InventoryController::class, 'update_mod'])->name('update.inventory.mod');
    Route::get('/register-inventory', [InventoryController::class, 'create_mod'])->name('register.inventory.mod');
    Route::post('/register-inventory', [InventoryController::class, 'store_mod'])->name('register.inventory.submit.mod');
    Route::delete('/delete-inventory/{id}', [InventoryController::class, 'mod_destroy'])->name('delete.inventory.mod');
});
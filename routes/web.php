<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\OfficesController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypesController;
use App\Http\Controllers\VehicleClassesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\Admin\PagosController;
use App\Http\Controllers\Admin\FacturacionController;
use App\Http\Controllers\AccidentsController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\Moderador\ModeratorController;
use App\Http\Controllers\ChangeUsersPassword;
use App\Http\Controllers\UserController;

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

Route::get('/v/{id}', 'VerifyController@show_policy');
Route::get('/download/{id}', 'VerifyController@showPolicyPdf')->name('policy.download');


Route::get('/condiciones', 'VerifyController@downloadConditions')->name('policy.download.conditions');

// Route::get('/c', function () {
//     $path = public_path('/uploads/condi.pdf'); // Asegúrate de que el archivo está en /public
//     return response()->download($path);
// });

/**
 * Rutas de administradores
 */
Route::prefix('admin')->middleware('role:administrador')->group(function () {


    // Registro de actividad
    Route::get('/activity-log', [AdminController::class, 'admin_activity_log'])->name('admin.activity.log');
    Route::get('/activity-log/all', [AdminController::class, 'admin_activity_log_all'])->name('admin.activity.log.all');
    // Route::get('/activity-log/user/{id}', [AdminController::class, 'admin_activity_log_user'])->name('admin.activity.log.user');
    Route::get('/activity-log/user/{id}', [AdminController::class, 'pdf_user_author'])->name('admin.activity.log.user');

    Route::get('/deleted-policys', [PoliciesController::class, 'deleteImages'])->name('deleted.policys');


    // Cambiar contraseña administrador
    Route::get('/change-password/{id}', [AdminController::class, 'admin_edit_password'])->name('admin.change.password');
    Route::put('/change-password/{id}', [AdminController::class, 'admin_update_password'])->name('admin.change.password.submit');

    // Rutas Polizas
    Route::get('/index-policies', [PoliciesController::class, 'index_admin'])->name('index.policies');
    Route::get('/deleted-policies', [PoliciesController::class, 'deleted_admin'])->name('deleted.policies');
    Route::get('/index-policy/{id}', [PoliciesController::class, 'show_admin'])->name('policy.price.view');
    Route::get('/index-search', [PoliciesController::class, 'index_search_admin'])->name('search.policies');
    Route::get('/deleted-search', [PoliciesController::class, 'deleted_search_admin'])->name('search.deleted.policies');
    Route::get('/admin-exportpdf/{id}', [PoliciesController::class, 'exportpdf_so'])->name('admin.policy.export.pdf');
    Route::get('/admin-exportpdf-digital/{id}', [PoliciesController::class, 'exportpdf_digital'])->name('admin.policy.export.pdf.digital');
    Route::get('/admin-vexportpdf', [PoliciesController::class, 'admin_vexportpdf'])->name('admin.vencida-export.pdf');
    Route::get('/edit-policy/{id}', [PoliciesController::class, 'admin_edit'])->name('admin.edit.policy');
    Route::put('/edit-policy/{id}', [PoliciesController::class, 'admin_update'])->name('admin.update.policy');
    Route::put('/renew-policy/{id}', [PoliciesController::class, 'admin_renew'])->name('admin.renew.policy');
    Route::get('/register-policy', [PoliciesController::class, 'create_admin'])->name('register.policy');
    Route::post('/register-policy', [PoliciesController::class, 'store_admin'])->name('register.policy.submit');
    Route::get('/register-policy/search', [PoliciesController::class, 'search'])->name('policy.search.vehicle');
    Route::delete('/delete-policy/{id}', [PoliciesController::class, 'admin_destroy'])->name('delete.user');
    Route::put('/renew-policy-price/{id}', [PoliciesController::class, 'admin_price_renew'])->name('admin.renew.price');
    Route::post('/restore-policy/{id}', [PoliciesController::class, 'nanular_admin'])->name('restore.policy');
    Route::get('/static-index', [PoliciesController::class, 'index_static'])->name('index.static');
    Route::post('/restore-policies/{id}', [PoliciesController::class, 'restore_policies'])->name('restore.policies');
    Route::post('/anular-policy/{id}', [PoliciesController::class, 'anular_user'])->name('POLIZAS.user.anular.policy');

    Route::get('/filter-policies', [PoliciesController::class, 'filtervencida'])->name('filter.vencida');
    Route::get('/index-vencida', [PoliciesController::class, 'vencida_admin'])->name('index.vencida');

    Route::get('/admin-exportpdf-digital2/{id}', [PoliciesController::class, 'exportpdf_digital2'])->name('admin.policy.export.pdf.digital2');


    Route::get('/export/policies-mes/{month}', [PoliciesController::class, 'export_todas'])->name('exportPdf.todas');

    // Inventario
    Route::get('/index-inventory', [InventoryController::class, 'index_inventory'])->name('index.inventory');
    Route::get('/index-inventory/{id}', [InventoryController::class, 'show_inventory'])->name('show.inventory');
    Route::get('/register-inventory', [InventoryController::class, 'create_admin'])->name('register.inventory');
    Route::post('/register-inventory', [InventoryController::class, 'store_admin'])->name('register.inventory.submit');

    Route::get('/edit-inventory/{id}', [InventoryController::class, 'edit_admin'])->name('admin.edit.inventory');
    Route::put('/edit-inventory/{id}', [InventoryController::class, 'update_admin'])->name('admin.update.inventory');

    Route::delete('/delete-inventory/{id}', [InventoryController::class, 'admin_destroy'])->name('delete.inventory');

    // Rutas Usuarios
    // Registrar usuario
    //Route::get('/register-user', 'Auth\RegisterController@showRegistrationForm')->name('register.user'); TODO
    //Route::post('/register-user', 'Auth\RegisterController@register')->name('register'); TODO

    Route::get('/index-users', [AdminController::class, 'index_users'])->name('index.users');
    Route::get('/index-usersm', [AdminController::class, 'index_users_m'])->name('index.users.m');
    Route::get('/index-user/{id}', [AdminController::class, 'show_user'])->name('index.show.user');
    Route::get('/index-usersd', [AdminController::class, 'index_users_deleted'])->name('index.users.deleted');
    Route::get('/edit-user/{id}', [AdminController::class, 'edit'])->name('admin.edit.user');
    Route::put('/edit-user/{id}', [AdminController::class, 'update'])->name('admin.update.user');
    Route::get('/edit-user/password/{id}', [AdminController::class, 'edit_password'])->name('admin.edit.user.password');
    Route::put('/edit-user/password/{id}', [AdminController::class, 'update_password'])->name('admin.edit.user.password.submit');
    Route::delete('/delete-user/{id}', [AdminController::class, 'destroy'])->name('admin.delete.user');
    Route::put('/restore-user/{id}', [AdminController::class, 'restore'])->name('admin.restore.user');
    Route::get('/sms-user/{id}', [AdminController::class, 'sms'])->name('admin.sms.user');
    Route::get('/cant-contra/{id}', [AdminController::class, 'admin_cant'])->name('admin.cant.contratos');
    Route::get('/edit-contra/{id}', [AdminController::class, 'edit_contra'])->name('admin.edit.contratos');
    Route::get('/export-users', [AdminController::class, 'index_users_pdf'])->name('export.users');
    Route::get('/lock-user/{id}', [AdminController::class, 'lock'])->name('admin.lock.user');
    Route::get('/unlock-user/{id}', [AdminController::class, 'unlock'])->name('admin.unlock.user');
    Route::get('/pdf-user/{id}', [AdminController::class, 'pdf_user'])->name('export.pdf.user');
    Route::get('/contrator-user/{id}', [AdminController::class, 'pdf_user_contrator'])->name('export.contrator.user');
    Route::get('/lock-user-region', [AdminController::class, 'lock_user_region'])->name('lock.region.user');


    Route::post('/upload-profile/{id}', [AdminController::class, 'upload_profile'])->name('upload.profile');
    Route::get('/img/{id}/download-profile', [AdminController::class, 'download_profile'])->name('download_profile');


    //Usuarios Supervidores
    Route::get('/index-mod', [AdminController::class, 'index_mod'])->name('index.user.mod');
    Route::get('/index-mod/{id}', [AdminController::class, 'show_user'])->name('index.show.mod');
    Route::get('/edit-mod/{id}', [AdminController::class, 'edit_mod'])->name('mod.edit.user');
    Route::put('/edit-mod/{id}', [AdminController::class, 'update_mod'])->name('mod.update.user');
    Route::put('/edit-mod/password/{id}', [AdminController::class, 'updatemod_password'])->name('mod.edit.user.password.submit');
    Route::delete('/delete-mod/{id}', [AdminController::class, 'destroy_mod'])->name('mod.delete.user');


    Route::get('/register-mod', 'Auth\RegisterController@showRegistrationFormmod')->name('registers.mod');
    Route::post('/register-mod', 'Auth\RegisterController@registermod')->name('mod.register');

    //Location
    Route::get('/location', 'LocationController@showMap')->name('map');
    Route::post('/extract-coordinates/{id}', 'LocationController@extractCoordinates')->name('extract-coordinates');
    Route::post('/deleted-maps/{id}', 'LocationController@destroy')->name('deleted-maps');

    // Rutas Usuarios administradores
    Route::get('/index-admins', [AdminController::class, 'index_users_admins'])->name('index.users.admins');
    Route::get('/index-admin/{id}', [AdminController::class, 'show_admin'])->name('index.show.admins');

    Route::get('/edit-admin/{id}', [AdminController::class, 'edit_admin'])->name('admin.edit.admin');
    Route::put('/edit-admin/{id}', [AdminController::class, 'update_admin'])->name('admin.update.admin');
    Route::delete('/delete-admin/{id}', [AdminController::class, 'destroy_admin'])->name('admin.delete.admin');
    // Cambiar contraseña administrador
    Route::get('/change-password/{id}', [AdminController::class, 'admin_edit_password'])->name('admin.change.password');
    Route::put('/change-password/{id}', [AdminController::class, 'admin_update_password'])->name('admin.change.password.submit');
    // Registrar administrador
    Route::get('/register', 'Auth\AdminRegisterController@showRegistrationForm')->name('admin.register');
    Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');

    // Rutas precios
    Route::get('/index-prices', [PricesController::class, 'index_admin'])->name('index.prices');
    Route::get('/index-price/{id}', [PricesController::class, 'show_admin'])->name('index.show.price');
    Route::get('/edit-price/{id}', [PricesController::class, 'admin_edit'])->name('admin.edit.price');
    Route::put('/edit-price/{id}', [PricesController::class, 'admin_update'])->name('admin.update.price');
    Route::get('/register-price', [PricesController::class, 'create'])->name('register.price');
    Route::post('/register-price', [PricesController::class, 'store'])->name('register.price.submit');
    Route::delete('/delete-price/{id}', [PricesController::class, 'destroy'])->name('delete.price');
    Route::get('/export-price/{id}', [PricesController::class, 'export'])->name('export.price');

    //precios nuevos

    // Rutas Oficinas
    Route::get('/index-offices', [OfficesController::class, 'index'])->name('index.offices');
    Route::get('/register-office', [OfficesController::class, 'create'])->name('register.office');
    Route::post('/register-office', [OfficesController::class, 'store'])->name('register.office.submit');

    Route::get('/edit-office/{id}', [OfficesController::class, 'admin_edit'])->name('admin.edit.office');
    Route::put('/edit-office/{id}', [OfficesController::class, 'admin_update'])->name('admin.update.office');
    Route::get('/cant-office/{id}', [OfficesController::class, 'admin_cant'])->name('admin.cant.office');
    Route::delete('/delete-office/{id}', [OfficesController::class, 'destroy'])->name('delete.office');


    // Rutas Vehiculos
    Route::get('/index-vehicles', [VehicleController::class, 'index_admin'])->name('index.vehicles');
    Route::get('/register-vehicle', [VehicleController::class, 'create_admin'])->name('register.vehicle');
    Route::post('/register-vehicle', [VehicleController::class, 'store_admin'])->name('register.vehicle.submit');
    Route::get('/edit-vehicle/{id}', [VehicleController::class, 'admin_edit'])->name('admin.edit.vehicle');
    Route::put('/edit-vehicle/{id}', [VehicleController::class, 'admin_update'])->name('admin.update.vehicle');
    Route::delete('/delete-vehicle/{id}', [VehicleController::class, 'destroy'])->name('delete.vehicle');

    /*Registrar tipo de vehiculo*/
    Route::get('/index-types', [VehicleTypesController::class, 'index_admin'])->name('index.vehicle.types');
    Route::get('/register-type', [VehicleTypesController::class, 'create_admin'])->name('register.type');
    Route::post('/register-type', [VehicleTypesController::class, 'store_admin'])->name('register.type.submit');
    Route::get('/edit-type/{id}', [VehicleTypesController::class, 'edit_admin'])->name('edit.vehicle.type');
    Route::put('/edit-type/{id}', [VehicleTypesController::class, 'update_admin'])->name('update.vehicle.type');
    Route::delete('/delete-type/{id}', [VehicleTypesController::class, 'destroy'])->name('delete.type');

    /* Registrar clase */
    Route::get('/index-classes', [VehicleClassesController::class, 'index_admin'])->name('index.vehicle.classes');
    Route::get('/register-class', [VehicleClassesController::class, 'create_admin'])->name('register.class');
    Route::post('/register-class', [VehicleClassesController::class, 'store_admin'])->name('register.class.submit');
    Route::get('/edit-class/{id}', [VehicleClassesController::class, 'edit_admin'])->name('edit.class');
    Route::put('/edit-class/{id}', [VehicleClassesController::class, 'update_admin'])->name('edit.class.submit');
    Route::delete('/delete-class/{id}', [VehicleClassesController::class, 'destroy'])->name('delete.class');

    //Rutas Pagos
    Route::get('/search-payments', [PaymentsController::class, 'search_users'])->name('search.users');
    Route::get('/search-payments/notpaid', [PaymentsController::class, 'search_usersnopaid'])->name('search.user.notpaid');
    Route::get('/search-modpayments/notpaid', [PaymentsController::class, 'search_modnopaid'])->name('search.supervisor.notpaid');

    Route::post('/register-payment/{id}', [PaymentsController::class, 'store_admin'])->name('register.payment.submit');

    Route::post('/selected-payr/{id}', [PaymentsController::class, 'selected_payr'])->name('selectedr.pay.submit');
    Route::put('/update-payment/{id}', [PaymentsController::class, 'update'])->name('payment.bill');

    Route::delete('/delete-policy-pay/{id}', [PaymentsController::class, 'admin_destroy_not_paid'])->name('deletep.policy');
    Route::get('/reports/{modId}', [PaymentsController::class, 'reportsuper'])->name('mod.all.report.policies');
    Route::get('/payments-vendedor', [PaymentsController::class, 'searchVendedor'])->name('vendedor.seach');

    // Namespace Admin 
    Route::namespace('Admin')->group(function () {
        // Pagos
        Route::get('/index-payments/notpaid', [PagosController::class, 'index'])->name('index.notpaid'); // Polizas sin pagar por usuario
        Route::get('/index-payments', [PagosController::class, 'index_pagados'])->name('index.payments'); // Polizas pagadas
        Route::get('/index-payments/notpaids', [PagosController::class, 'index_sin_pagar_por_supervisor'])->name('index.notpaids'); // Polizas no pagadas por supervisor
        Route::get('/export-policies-pdf/{moderator_id}', [PagosController::class, 'exportPoliciesToPdf'])->name('MOD.export.policies.pdf');
        Route::get('/export-payments/{id}', [PagosController::class, 'admin_exportpdf'])->name('admin.payments.export');
        Route::get('/index-payment/not-paid/{user}', [PagosController::class, 'show'])->name('index.show.notpaid');
        Route::get('/report/{user}', [PagosController::class, 'report_one'])->name('one.report.policies');
        Route::post('/reportpaymenta', [PagosController::class, 'report_payment_admin'])->name('report.paymenta');
        Route::get('/index-payment/paid/{payment}', [PagosController::class, 'show_paid_admin'])->name('index.show.paid'); //(Show Pago) Indice de polizas asociadas al pago
        Route::get('/index-payment/{id}', [PagosController::class, 'show_admin'])->name('index.show.payment');
        Route::post('/selected-pay/{user}/pago-manual', [PagosController::class, 'ajax_pagar_polizas_manual'])->name('ajax-pago-manual'); // Pagar manualmente
        Route::post('/selected-pay/{user}', [PagosController::class, 'ajax_pagar_polizas'])->name('selected.pay.submit'); // Pagar
        Route::post('/index-payment/not-paid/{user}/calcular-comision', [PagosController::class, 'ajax_calcular_comision'])->name('index.show.notpaid.calcular-comision');

        // Facturacion
        Route::get('facturacion', [FacturacionController::class, 'index'])->name('facturacion.index');
        Route::post('facturacion/emitir-facturas', [FacturacionController::class, 'emitir_facturas'])->name('facturacion.emitir');
        Route::get('facturacion/{poliza}/descargar-factura', [FacturacionController::class, 'descargar_factura'])->name('facturacion.descargar-factura');
    });

    //reporte de pagos
    Route::get('/index-paymentss', [PaymentsController::class, 'index_paymmetts'])->name('index.paymentss');
    Route::get('/index-paymentss/{id}', [PaymentsController::class, 'show_paymmetts'])->name('show.paymentss');
    Route::get('/export-report/{id}', [PaymentsController::class, 'report_exportpdf'])->name('report.export');



    //Reporte
    Route::get('/general-payments', [PaymentsController::class, 'general_admin'])->name('general.payments');
    Route::post('/export-sales-report', [PaymentsController::class, 'exportSalesReportPdf'])->name('export.sales.report');
    Route::get('/payments-vendedor1', [PaymentsController::class, 'searchVendedor1'])->name('vendedor.seach1');

    //Siniestros
    Route::get('/index-siniestros', [AccidentsController::class, 'index'])->name('index.siniestros');
    Route::get('/index-siniestros/{id}', [AccidentsController::class, 'show'])->name('show.siniestros');
    Route::get('/register-siniestro', [AccidentsController::class, 'create'])->name('register.siniestro');
    Route::post('/register-siniestro', [AccidentsController::class, 'store'])->name('register.siniestro.submit');
    Route::get('/edit-siniestro/{id}', [AccidentsController::class, 'edit'])->name('admin.edit.siniestro');
    Route::put('/edit-siniestro/{id}', [AccidentsController::class, 'update'])->name('admin.update.siniestro');
    Route::delete('/delete-siniestro/{id}', [AccidentsController::class, 'destroy'])->name('delete.siniestro');
    Route::get('/export-siniestro/{id}', [AccidentsController::class, 'export'])->name('export.siniestro');
    Route::get('/export-siniestros', [AccidentsController::class, 'export_all'])->name('export.siniestros');
    Route::get('/get-policy-data/{policyNumber}', [AccidentsController::class, 'getPolicyData'])->name('get-policy-data');




    //Finanzas
    Route::get('/index-finance', [FinanceController::class, 'sumTotalPremiumByType'])->name('index.finance');
    Route::get('/new-finance', [FinanceController::class, 'sumTotalPremiumByType_new'])->name('index.finance2');
    Route::get('/export-policies-pdf/{type}', [FinanceController::class, 'exportPoliciesPdfByAdmin'])->name('export.policies.pdf');

    Route::get('/export-finance', [FinanceController::class, 'exportSummaryPdf'])->name('export.finance.pdf');
    //estadisticas
    Route::get('/index-estadistica', [PaymentsController::class, 'index_static_policies'])->name('index.static.policies');

    // Rutas Divisas
    Route::post('/register-foreign', [PaymentsController::class, 'foreign_register'])->name('register.foreign.submit');
    Route::put('/update-foreign/{id}', [PaymentsController::class, 'update_foreign'])->name('edit.foreign.submit');

    // Route::get('/show-import-form', function(){
    //     return view('admin-modules.Policies.admin-policies-import');
    // });
    Route::post('/upload-db', [PoliciesController::class, 'uploadFile'])->name('upload.db');

    // Ruta dashboard admin
    Route::get('/', [AdminController::class, 'index'])->name('admin');
});

/**
 * Rutas de moderadores (supervisores)
 */
Route::prefix('mod')->middleware('role:moderador')->group(function () {
    // Ruta Polizas

    Route::get('/index-policiesd', [ModeratorController::class, 'index_mod'])->name('index.policies.mod');
    Route::get('/index-policy/{id}', [ModeratorController::class, 'show_mod'])->name('policy.show.mod');
    Route::get('/inde-search', [ModeratorController::class, 'index_search'])->name('mod.search.policies');
    Route::get('/edit-policyd/{id}', [ModeratorController::class, 'edit'])->name('mod.edit.policy');
    Route::put('/edit-policyd/{id}', [ModeratorController::class, 'update'])->name('mod.update.policy');


    Route::get('/pdf-digital/{id}', [ModeratorController::class, 'exportd_modpdf'])->name('policy.exportd.pdf');
    Route::post('/restored-policy/{id}', [ModeratorController::class, 'nanular_mod'])->name('restored.policy');
    Route::post('/anular-policy/{id}', [PoliciesController::class, 'anular_user'])->name('user.anular.policy');

    //user
    Route::get('/index-usersd', [ModeratorController::class, 'index_users_mod'])->name('index.users.mod');
    Route::get('/register-user', [ModeratorController::class, 'showRegistrationForm'])->name('register.user.mod');
    Route::post('/register-user', [ModeratorController::class, 'register'])->name('register.mod');
    Route::get('/cant-contram/{id}', [ModeratorController::class, 'mod_cant'])->name('mod.cant.contratos');
    Route::get('/edit-contram/{id}', [ModeratorController::class, 'edit_contra'])->name('mod.edit.contratos');
    Route::get('/pdf-user/{id}', [ModeratorController::class, 'pdf_user'])->name('export.pdf.user.mod');

    //consulta de pagos
    Route::get('/index-payments/notpaidd', [ModeratorController::class, 'index_not_paid_mod'])->name('index.notpaid.mod');
    Route::get('/exportd-payments/{id}', [ModeratorController::class, 'mod_exportpdf'])->name('mod.payments.export');
    Route::get('/index-paymentd/not-paid/{id}', [ModeratorController::class, 'show_not_paid_mod'])->name('mod.show.notpaid');
    Route::post('/selected-pay/{id}', [ModeratorController::class, 'selected_pay'])->name('selectedm.pay.submit');
    Route::get('/index-paymentd/report/{id}', [ModeratorController::class, 'report_mod'])->name('mod.report.policies');
    Route::post('/payments-reports', [ModeratorController::class, 'report_all_mod'])->name('mod.report.all.policies');
    Route::get('/search-payments/notpaid', [ModeratorController::class, 'search_usersnopaid'])->name('mod.search.notpaid');

    Route::get('/index-pricesd', [PricesController::class, 'index_mod'])->name('index.prices.mod');

    Route::get('/general-paymentsd', [ModeratorController::class, 'general_policies'])->name('mod.general.payments');
    Route::get('/payments-vendedord', [ModeratorController::class, 'searchVendedord'])->name('mod.vendedor.seach1');


    Route::get('/locationd', 'LocationController@showMap_mod')->name('map.mod');

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

/**
 * Rutas de usuarios
 */
Route::prefix('user')->middleware('role:usuario')->group(function () {

    Route::get('/export-csv', [PoliciesController::class, 'exportCsv'])->name('export.csv');

    // Rutas Vehiculos
    Route::get('/index-vehicles', [VehicleController::class, 'index'])->name('user.index.vehicles');
    Route::get('/register-vehicle', [VehicleController::class, 'create'])->name('user.register.vehicle');
    Route::post('/register-vehicle', [VehicleController::class, 'store'])->name('user.register.vehicle.submit');

    // Registrar tipo de vehiculo
    Route::get('/index-types', [VehicleTypesController::class, 'index'])->name('user.index.vehicle.types');
    Route::get('/register-type', [VehicleTypesController::class, 'create'])->name('user.register.type');
    Route::post('/register-type', [VehicleTypesController::class, 'store'])->name('user.register.type.submit');
    Route::get('/edit-type/{id}', [VehicleTypesController::class, 'edit'])->name('user.edit.vehicle.type');
    Route::put('/edit-type/{id}', [VehicleTypesController::class, 'update'])->name('user.update.type');

    // Rutas Polizas
    Route::get('/index-policies', [PoliciesController::class, 'index'])->name('user.index.policies');
    Route::get('/solicitud-policies', [PoliciesController::class, 'indexs'])->name('user.index.spolicies');
    Route::get('/index-vencidas', [PoliciesController::class, 'index_vencidas'])->name('user.index-vencidas.policies');
    Route::get('/register-policy', [PoliciesController::class, 'create'])->name('user.register.policy');
    Route::post('/register-policy', [PoliciesController::class, 'store'])->name('user.register.policy.submit');
    Route::get('/index-search', [PoliciesController::class, 'index_search'])->name('user.search.policies');
    Route::get('/index-policy/{id}', [PoliciesController::class, 'show'])->name('user.show.policy');
    Route::get('/edit-policy/{id}', [PoliciesController::class, 'edit'])->name('user.edit.policy');
    Route::put('/edit-policy/{id}', [PoliciesController::class, 'update'])->name('user.update.policy');
    Route::get('/edit-solicitud/{id}', [PoliciesController::class, 'edit_solicitud'])->name('user.edit.solicitud');
    Route::put('/edit-solicitud/{id}', [PoliciesController::class, 'update_solicitud'])->name('user.update.solicitud');
    Route::get('/renew-policy/{id}', [PoliciesController::class, 'renew'])->name('user.renew.policy');
    Route::put('/renew-policy/{id}', [PoliciesController::class, 'renew_update'])->name('user.rupdate.policy');
    Route::get('/pdf-digital/{id}', [PoliciesController::class, 'exportpdf_digital'])->name('pdf.digital');
    Route::get('/pdf/{id}', [PoliciesController::class, 'exportpdf_so'])->name('SO.pdf.digital');
    Route::post('/filter-policies', [PoliciesController::class, 'filterPolicies'])->name('user.filter.policies');
    Route::get('/user-exportpdf/{id}', [PoliciesController::class, 'user_exportpdf'])->name('user.policy.export.pdf');
    Route::put('/renew-policy-price/{id}', [PoliciesController::class, 'price_renew'])->name('user.renew.price');
    Route::get('/download-tp/{id}', [PoliciesController::class, 'downloadImagetp'])->name('download.tp.image');
    Route::get('/download-ci/{id}', [PoliciesController::class, 'downloadImageci'])->name('download.ci.image');
    Route::post('/upload-documents/{id}', [PoliciesController::class, 'upload_document'])->name('user.upload.document');
    Route::post('/upload-documentss/{id}', [PoliciesController::class, 'upload_documents'])->name('user.upload.documents');
    Route::get('/proce-p/{id}', [PoliciesController::class, 'procePolicy'])->name('user.reporte.payment');
    Route::post('/proce-p2/{id}', [PoliciesController::class, 'procePolicy2'])->name('user.reporte.payment2');

    // solicitud
    Route::get('/downloads-tp/{id}', [PoliciesController::class, 'downloadImagetps'])->name('download.tp.images');
    Route::get('/downloads-ci/{id}', [PoliciesController::class, 'downloadImagecis'])->name('download.ci.images');

    // polizas viejas
    Route::get('/user-exportpdf2/{id}', [PoliciesController::class, 'user_exportpdf2'])->name('user.policy.export.pdf2');
    Route::get('/renew-policy2/{id}', [PoliciesController::class, 'renew2'])->name('user.renew.policy2');
    Route::put('/renew-policy2/{id}', [PoliciesController::class, 'renew_update2'])->name('user.rupdate.policy2');

    // Rutas Precios
    Route::get('/index-prices', [PricesController::class, 'index'])->name('user.index.prices');
    Route::get('/index-price/{id}', [PricesController::class, 'show'])->name('user.index.show.price');

    // Rutas Pagos
    Route::get('/index-payments', [PaymentsController::class, 'index'])->name('user.index.payments');
    Route::get('/index-payment/not-paid/', [PaymentsController::class, 'show_not_paid'])->name('user.index.show.notpaid');
    Route::get('/export-payments', [PaymentsController::class, 'user_exportpdf'])->name('user.payments.export');
    Route::get('/export-report/{id}', [PaymentsController::class, 'report_exportpdf'])->name('user.report.export');
    Route::post('/selected-pay/{id}', [PaymentsController::class, 'selected_pay_user'])->name('selected_pay_user');
    Route::get('/policies-report', [PaymentsController::class, 'policies_report'])->name('user.policies.report');

    // Rutas Manejo de Perfil
    Route::put('/change-password/{id}', [ChangeUsersPassword::class, 'update_password'])->name('user.change.password.submit');
    Route::post('/upload-profile/{id}', [UserController::class, 'upload_profile'])->name('user.upload.profile');
    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/img/{id}/download-profile', [UserController::class, 'download_profile'])->name('user.download_profile');

    // Rutas Registro de Actividad
    Route::get('/activity-log/{id}', [DashboardController::class, 'activity_log'])->name('user.activity.log');
    Route::get('/search-municipio', [OfficesController::class, 'search_municipio'])->name('office.search.municipio.user');
    Route::get('/search-parroquia', [OfficesController::class, 'search_parroquia'])->name('office.search.parroquia.user');

    // Importa base de datos usuario
    Route::post('/user-upload-db', [PoliciesController::class, 'userUploadFile'])->name('user.upload.db');
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

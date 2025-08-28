<?php

use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\LocationController;

/**
 * Rutas de administradores
 */
Route::prefix('admin')->middleware('role:administrador')->group(function () {
    // -------------------------------------------------------------------------------------------------------------
    // TODO: verificar estos controladores
    // -------------------------------------------------------------------------------------------------------------
    // Route::get('/register-mod', 'Auth\RegisterController@showRegistrationFormmod')->name('registers.mod');
    // Route::post('/register-mod', 'Auth\RegisterController@registermod')->name('mod.register');
    // Registrar administrador
    // Route::get('/register', 'Auth\AdminRegisterController@showRegistrationForm')->name('admin.register');
    // Route::post('/register', 'Auth\AdminRegisterController@register')->name('admin.register.submit');
    // -------------------------------------------------------------------------------------------------------------

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
    Route::get('/register-user', 'Auth\RegisterController@showRegistrationForm')->name('register.user'); // TODO
    Route::post('/register-user', 'Auth\RegisterController@register')->name('admin.register'); // TODO

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

    //Location
    Route::get('/location', [LocationController::class, 'showMap'])->name('map');
    Route::post('/extract-coordinates/{id}', [LocationController::class, 'extractCoordinates'])->name('extract-coordinates');
    Route::post('/deleted-maps/{id}', [LocationController::class, 'destroy'])->name('deleted-maps');

    // Rutas Usuarios administradores
    Route::get('/index-admins', [AdminController::class, 'index_users_admins'])->name('index.users.admins');
    Route::get('/index-admin/{id}', [AdminController::class, 'show_admin'])->name('index.show.admins');

    Route::get('/edit-admin/{id}', [AdminController::class, 'edit_admin'])->name('admin.edit.admin');
    Route::put('/edit-admin/{id}', [AdminController::class, 'update_admin'])->name('admin.update.admin');
    Route::delete('/delete-admin/{id}', [AdminController::class, 'destroy_admin'])->name('admin.delete.admin');
    // Cambiar contraseña administrador
    Route::get('/change-password/{id}', [AdminController::class, 'admin_edit_password'])->name('admin.change.password');
    Route::put('/change-password/{id}', [AdminController::class, 'admin_update_password'])->name('admin.change.password.submit');
    
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
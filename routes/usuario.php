<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoliciesController;
use App\Http\Controllers\PricesController;
use App\Http\Controllers\OfficesController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypesController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\ChangeUsersPassword;
use App\Http\Controllers\UserController;

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
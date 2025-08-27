<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Http\Requests\CalcularComision;
use App\Http\Requests\StorePago;
use App\Http\Requests\StorePagoManual;
use App\Http\Services\Bnc;
use Exception;
use PDF;

use App\Models\User;
use App\Models\Moderator;
use App\Models\Policy;
use App\Models\ForeignUnit;
use App\Models\Payment;

/**
 * Controlador para todo lo que tenga que ver con el administrador y las polizas por pagar.
 * $user->type es lo que define el id del administrador, por ende en muchso queries se
 * busca filtrar los usuarios donde su "type" que vendria siendo "admin_id" sea igual al
 * id del usuario administrador que esta logueado actualmente.
 */
class PagosController extends Controller
{

    /**
     * Muestra las polizas por pagar ordenadas por usuario y supervisor
     */
    public function index()
    {
        $user = Auth::user();
        $type = $user->type; // Columna que define el id del administrador en cuestion.

        // Usuarios relacionados al administrador logueado
        $users = User::with('policies')->withCount('policies')
            ->where('type', $user->type)
            ->orderBy('policies_count')
            ->paginate(10);

        // Obtener listas para datalists
        $users3 = User::whereIn('type', $type == 4 ? [4, 5, 6] : [$type])->get(['id', 'username', 'name', 'lastname']);
        $moderators = Moderator::whereIn('type', $type == 4 ? [4, 5, 6] : [$type])->get(['id', 'names']);

        $policiesSummaries = Policy::selectRaw("
                        user_id,
                        SUM(CASE WHEN status = 0 AND report = 1 THEN 1 ELSE 0 END) as reportPaidCount,
                        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as notPaidCount,
                        SUM(CASE WHEN status = 0 AND statusu = 1 THEN 1 ELSE 0 END) as nulasCount,
                        SUM(CASE WHEN status = 0 AND statusu IS NULL THEN (total_premium) ELSE 0 END) as totalNotPaidPrice
                    ")
            ->whereIn('user_id', $users->pluck('id'))
            //->whereNull('deleted_at') No es necesario con el trait de softDeletes
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        return view('admin-modules.Payments.admin-payments-index-notpaid', compact('users', 'users3', 'moderators', 'policiesSummaries'));
    }

    /**
     * Lista de polizas sin pagar ordenadas por supervisor
     */
    public function index_sin_pagar_por_supervisor()
    {
        $user = Auth::user();
        $type = $user->type;

        $moderators = Moderator::with(['supervisados'])->whereIn('type', $type == 4 ? [4, 5, 6] : [$type])
            ->get(['id', 'names', 'profi_percentaje']);

        // Iteramos sobre cada moderador (supervisor) para agregarle los datos del sumario
        // de ventas y los totales en comisiones.   
        foreach ($moderators as $mod) {
            // Una manera mas sencilla de obtener los IDs de los usuarios supervisados
            $ids_usuarios_supervisados = $mod->supervisados->pluck('id');
            // Obtener resúmenes de pólizas
            $sumario_de_polizas = Policy::selectRaw("
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as notPaidCount,
                    SUM(CASE WHEN status = 0 AND report = 1 THEN 1 ELSE 0 END) as reportPaidCount,
                    SUM(CASE WHEN statusu = 1 THEN 1 ELSE 0 END) as nulasCount,
                    SUM(CASE WHEN status = 0  THEN total_premium ELSE 0 END) as totalVendido
                ")
                ->whereIn('user_id', $ids_usuarios_supervisados)
                ->whereNull('deleted_at')
                ->first(); // Usar first() porque solo necesitamos los resúmenes

            // le asignamos esta nueva propiedad al moderador de esta iteracion
            // para acceder a este facilmente desde la vista.
            $mod->sumario = $sumario_de_polizas;

            // Calcular la comisión y el total a recibir
            $mod->comision = $mod->sumario->totalVendido * $mod->profi_percentaje / 100;
            $mod->totalARecibir = $mod->sumario->totalVendido - $mod->comision;
        }
        return view('admin-modules.Payments.admin-payments-index-notpaids', compact('moderators'));
    }

    /**
     * Listado de polizas pagadas
     */
    public function index_pagados()
    {
        $type = Auth::user()->type;
        $users = User::where('type', $type)->paginate(10);
        // Obtener usuarios para el select
        $users3 = User::where('type', $type)->get();

        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];
        $payments = Payment::all();
        return view('admin-modules.Payments.admin-payments-index', compact('users3', 'users', 'payments', 'foreign_reference'));
    }

    /**
     * Muestra pagos pendientes para un vendedor en particular
     */
    public function show(User $user)
    {
        $not_paid = $policies = Policy::where('user_id', $user->id)
            ->where('status', FALSE) // no pagada
            ->get();

        return view('admin-modules.Payments.admin-payment-show-notpaid', compact('not_paid', 'user'));
    }

    /**
     * Hace el cierre de las polizas para un usuario en particular.
     * Coloca la columna "report" a true en la poliza determinada.
     * +-----------------------------------------------------------------------
     * | El cierre solo funciona para premarcar las polizas y que el checkbox 
     * | de estas ya este marcado automaticamente al momento de pagar 
     * | las polizas ...
     * +-----------------------------------------------------------------------
     */
    public function report_one(User $user)
    {
        Policy::where('user_id', $user->id)->where('report', FALSE)->update(['report' => TRUE]);

        return redirect()->back()->with('success', 'Se ha realizado el cierre correctamente');
    }

    /**
     * Hace el cierre de todas las polizas al momento atadas a un supervisor en particular 
     * (type == user;_id del administrador (supervisor) en cuestion)
     */
    public function report_payment_admin()
    {
        Policy::where('type', Auth::user()->type)->where('report', 0)->update(['report' => 1]);

        return redirect()->back()->with('success', 'Se ha realizado el porte general correctamente, ahora puede exportar');
    }

    /**
     * Reporte de polizas por vendedor
     */
    public function exportPoliciesToPdf($modId)
    {
        // Obtener el nombre del supervisor
        $supervisor = Moderator::find($modId);
        $supervisorName = $supervisor ? $supervisor->names : 'Sin supervisor';

        // Obtener las pólizas filtradas por mod_id y status = 0
        $policies = Policy::whereHas('user', function ($query) use ($modId) {
            $query->where('mod_id', $modId);
        })
            ->where('status', 0)
            ->with('user') // Cargar la relación con el usuario (vendedor)
            ->orderBy('user_id') // Ordenar por vendedor
            ->get();

        // Agrupar las pólizas por vendedor
        $groupedPolicies = $policies->groupBy('user_id');

        // Calcular el total de total_premium por vendedor
        $totalsByVendedor = [];
        foreach ($groupedPolicies as $userId => $policiesGroup) {
            $totalsByVendedor[$userId] = $policiesGroup->sum('total_premium');
        }

        // Datos para la vista
        $data = [
            'supervisorName' => $supervisorName,
            'groupedPolicies' => $groupedPolicies,
            'totalsByVendedor' => $totalsByVendedor,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('admin-modules.Payments.export-policies-pdf', $data);

        // Descargar el PDF
        return $pdf->stream("polizas_supervisor_{$supervisorName}.pdf");
    }

    /**
     * Reporte de polizas sin pagar
     */
    public function admin_exportpdf($user_id)
    {
        $user = User::find($user_id);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;
        $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', FALSE)
            ->where('report', TRUE)
            ->get();
        $data = [
            'policies' => $policies,
            'user' => $user,
            'euro' => $euro,
            'dolar' => $dolar
        ];

        $pdf = PDF::loadView('admin-modules.Payments.admin-payment-export', $data)->setPaper('letter', 'portrait');
        return $pdf->stream('Correlativo' . '.pdf');
    }

    /**
     * Muestra las polizas asociadas a un pago en particular
     */
    public function show_paid_admin(Payment $payment) // ID del pago
    {
        return view('admin-modules.Payments.admin-payment-show-paid', [
            'policies' => $payment->policies,
            'foreign_reference' => ForeignUnit::all()->pluck('foreign_reference')[0],
        ]);
    }
    /**
     * Pagos realizados a un usuario
     */
    public function show_admin($id)
    {
        $payments = Payment::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];

        return view('admin-modules.Payments.admin-payment-show', compact('id', 'payments', 'foreign_reference'));
    }

    /**
     * Pagar (automaticamente).
     * 
     * Realiza el pago haciendo uso de la API del banco para ello.
     */
    public function ajax_pagar_polizas(StorePago $request, User $user)
    {
        $type = Auth::user()->type; // Usuario administrador actual
        $polizas_seleccionadas = $request->polizas;

        // Obteniendo colección de todas las polizas a pagar
        $polizas_a_pagar = Policy::whereIn('id', $polizas_seleccionadas)->get();

        // Obteniendo totales usando la colección anterior
        $total_all = $polizas_a_pagar->sum('total_premium');

        // Calculando pago de vendedores con su porcentaje de gananacia
        // TODO: podria obtenerse el usuario desde la poliza??? así este metodo sería mucho mas general no?
        $total_payment = round(($total_all * $user->profit_percentage) / 100, 2);

        // Calculando el total en Bolivares que será usado para realizar el pago
        $ref_euro = ForeignUnit::where('foreign_name', 'Euro')->first()->foreign_reference;
        $total_bolivares = $total_payment * $ref_euro;

        // Realiza el pago
        try {
            $bnc = new Bnc();
            $pago = $bnc->realizarPagoMovil(
                $total_bolivares, // Total en Bs
                intval($user->bank->codigo), // Código del banco
                $user->bank_phone, // Número de teléfono
                str_replace('-', '', $user->ci), // Quitando guiones de la cedula para quedar en formato VXXXXXXXX . . .
                'Lider Seguros, pago a proveedores', // Descripción del pago
                $user->nombreCompleto, // Nombre del beneficiario
                $request->referencia_interna, // String (Str::random(20)) creado en el formulario para evitar la repetición de la misma operación
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 503);
        }

        // Verificar si se ha enviado un duplicado (Ejemplo común: la persona se fué atrás en el navegador ó alguna otra cosa rara)
        if ($pago['value_decrypted']['SwAlreadySent'] == 'true') {
            return response()->json([
                'message' => 'Este pago ya se encuentra hecho, por favor recargue esta página (presione F5)', // TODO: verificacion interna de esto
            ], 503);
        }

        // El pago y la actualización de las polizas con el id del pago debe estar en una transacción
        $payment_id = DB::transaction(function () use ($type, $user, $total_all, $total_payment, $polizas_seleccionadas, $pago) {
            $payment = Payment::create([
                'tipo_de_pago_id' => 2, // Automatico
                'type' => $type,
                'office' => $user->office->office_address,
                'user_id' => $user->id,
                'total' => $total_all,
                'profit_percentage' => $user->profit_percentage,
                'total_payment' => $total_payment,
                'bill' => json_encode($pago),
            ]);

            // Actualizar las polizas seleccionadas y colocarles el id del pago realizado
            Policy::where('user_id', $user->id)->where('status', false)->whereIn('id', $polizas_seleccionadas)
                ->update([
                    'status' => true,
                    'payment_id' => $payment->id,
                ]);

            return $payment->id;
        });

        $mensaje_exito = "Pago realizado correctamente con la referencia: {$pago['value_decrypted']['Reference']} y el código de autorización: {$pago['value_decrypted']['AuthorizationCode']}";
        $request->session()->flash('success', $mensaje_exito);

        return response()->json([
            'message' => $mensaje_exito, // como debug
            'redirect' => route('index.show.paid', $payment_id)
        ]);
    }

    /**
     * Pago manual.
     * 
     * Este tipo de pago requiere adjuntar un soporte que indique como fueron
     * pagadas las polizas.
     */
    public function ajax_pagar_polizas_manual(StorePagoManual $request, User $user)
    {
        // Usuario administrador actual
        $type = Auth::user()->type;

        // Obteniendo totales usando la colección de polizas_a_pagar
        $polizas_a_pagar = Policy::whereIn('id', $request->polizas)->get();
        $total_all = $polizas_a_pagar->sum('total_premium');

        // Calculando pago de vendedores con su porcentaje de gananacia
        // TODO: podria obtenerse el usuario desde la poliza??? así este metodo sería mucho mas general no?
        $total_payment = round(($total_all * $user->profit_percentage) / 100, 2);

        // Archivo de soporte
        $archivo_soporte = $request->file('soporte_pago');
        $nombre_del_archivo = Str::uuid() . '.' . $archivo_soporte->getClientOriginalExtension();

        try {
            $ruta_del_archivo = $archivo_soporte->storeAs('public/soportes', $nombre_del_archivo);

            // El pago y la actualización de las polizas con el id del pago debe estar en una transacción
            DB::transaction(function () use ($type, $user, $total_all, $total_payment, $polizas_a_pagar, $ruta_del_archivo, $request) {
                $payment = Payment::create([
                    'tipo_de_pago_id' => 1, // Manual
                    'type' => $type,
                    'office' => $user->office->office_address,
                    'user_id' => $user->id,
                    'total' => $total_all,
                    'profit_percentage' => $user->profit_percentage,
                    'total_payment' => $total_payment,
                    'bill' => $ruta_del_archivo,
                ]);

                // Actualizar las polizas seleccionadas y colocarles el id del pago realizado
                Policy::where('user_id', $user->id)
                    ->where('status', false)
                    ->whereIn('id', $request->polizas)
                    ->update([
                        'status' => true,
                        'payment_id' => $payment->id,
                    ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creando el pago: ' . $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'Pago Registrado',
        ]);
    }

    //Cantidad de polizas sin pagar para un usuario en particular -> TODO: mejor pasar al modelo de policy ?????
    public static function get_polizas_sin_pagar($user_id)
    {
        // Contar directamente en la base de datos sin cargar los registros en memoria
        $counted_policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->count();

        // Devuelve el conteo, que será 0 si no hay registros que cumplan con la consulta
        return $counted_policies;
    }

    /**
     * AJAX para calcular la comision
     */
    public function ajax_calcular_comision(CalcularComision $request, User $user)
    {
        $polizas_seleccionadas = $request->polizas;

        if ($polizas_seleccionadas && count($polizas_seleccionadas) > 0) {
            // Obteniendo colección de todas las polizas a pagar
            $polizas_a_pagar = Policy::whereIn('id', $polizas_seleccionadas)->get();

            // Obteniendo totales usando la colección anterior
            $total_all = $polizas_a_pagar->sum('total_premium');

            // Calculando pago de vendedores con su porcentaje de gananacia
            $total_euros = round(($total_all * $user->profit_percentage) / 100, 2);

            // Obteniendo valores de referencia para calcular precio en Bolivares y Euros.
            $ref_euro = ForeignUnit::where('foreign_name', 'Euro')->first()->foreign_reference;
            $ref_dolar = ForeignUnit::where('foreign_name', 'Dolar')->first()->foreign_reference;
            $total_bolivares = $total_euros * $ref_euro;
            $total_dolares = $total_bolivares / $ref_dolar;

            return response()->json([
                'total_euros' => number_format($total_euros, 2, ',', '.'),
                'total_dolares' => number_format($total_dolares, 2, ',', '.'),
                'total_bolivares' => number_format($total_bolivares, 2, ',', '.'),
            ]);
        }

        return response()->json([
            'total_euros' => 0,
            'total_dolares' => 0,
            'total_bolivares' => 0,
        ]);
    }
    /**
     * Calculo de margen de ganancia -> TODO quitar esto de aca y usar el getAttribute creado en modelo de Policy
     */
    // public static function profit_percentage($value1, $value3)
    // {
    //     $suma = ($value3 * $value1) / 100;
    //     $result = $value1 - $suma;
    //     return $result;
    // }
}

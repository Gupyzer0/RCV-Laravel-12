<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ForeignUnit;
use App\Models\Policy;
use App\Models\Policy2;
use App\Models\Pagos;
use App\Models\Payment;
use App\Models\Relation_po_pa;
use App\Models\Rreport;
use App\Models\Bank;

use App\Http\Services\Bnc;
use Carbon\Carbon;
use PDF;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];
        $payments = Payment::where('user_id', $user_id)->get();
        return view('user-modules.Payments.payments-index', compact('payments', 'foreign_reference'));
    }

    public function index_paymmetts()
    {
        $admin_type = Auth::user()->type;
        $users = User::where('type', $admin_type)->get();

        // Obtener la fecha de hoy (en formato Y-m-d)
        $today = Carbon::now()->toDateString();

        // Filtrar los pagos: solo los de hoy y pertenecientes a usuarios con el mismo tipo que el admin
        $pagos = Pagos::whereHas('user', function ($query) use ($admin_type) {
            $query->where('type', $admin_type);
        })->get();

        $pagos->each(function ($reporte) {
            // Obtener todas las relaciones para este pago
            $relations = Rreport::where('payment_id', $reporte->id)
                ->whereHas('policy', function ($query) {
                    $query->where('status', 0);
                })
                ->with('policy')
                ->get();

            // Contar el total de relaciones con policy->status == 0
            $reporte->total_policies = $relations->count();

            // Filtrar las pólizas con report = 1 y status = 0
            $reporte->status = $relations->filter(function ($relation) {
                return $relation->policy->report && $relation->policy->status == 0;
            })->count();
        });

        // Obtener la referencia extranjera
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        return view('admin-modules.Paymentt.index-paymentts', compact('pagos', 'dolar', 'euro', 'today', 'users'));
    }

    public function searchVendedor(Request $request)
    {
        $type = Auth::user()->type;

        // Obtener los parámetros de búsqueda
        $startDate = $request->get('fechai');
        $endDate = $request->get('fechaf');
        $userId = $request->get('user');
        $exportType = $request->get('export'); // Para exportar PDF o Excel
        $users = User::where('type', $type)->get();

        // Construir la consulta base
        $query = Pagos::whereHas('user', function ($query) use ($type, $userId) {
            $query->where('type', $type);

            if ($userId && $userId != 0) {
                $query->where('id', $userId);
            }
        });

        // Filtrar por rango de fechas si se proporcionan
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Agrupar por policy_id y sumar los montos
        $pagos = $query->select('policy_id')
            ->selectRaw("
                SUM(CASE WHEN paymentt = 'efectivo' THEN amount ELSE 0 END) as total_efectivo,
                SUM(CASE WHEN paymentt = 'punto_de_venta' THEN amount ELSE 0 END) as total_punto_de_venta,
                SUM(CASE WHEN paymentt = 'pago_movil' THEN amount ELSE 0 END) as total_pago_movil,
                SUM(CASE WHEN paymentt = 'transferencia' THEN amount ELSE 0 END) as total_transferencia
            ")
            ->groupBy('policy_id')
            ->with('policies') // Cargar relación con Policy
            ->get();

        // Obtener el valor de foreign_reference
        $foreign_reference = ForeignUnit::value('foreign_reference');

        // Verificar si se solicitó una exportación
        if ($exportType === 'pdf') {
            // Generar PDF (puedes usar una librería como dompdf)
            $pdf = PDF::loadView('exports.pagos-pdf', compact('pagos', 'startDate', 'endDate', 'userId'));
            return $pdf->download('pagos.pdf');
        } elseif ($exportType === 'excel') {
            // Generar Excel (puedes usar Laravel Excel)
            return Excel::download(new PagosExport($pagos), 'pagos.xlsx');
        }

        // Si no es exportación, retornar la vista
        return view('admin-modules.Paymentt.index-paymentts', compact('pagos', 'foreign_reference', 'startDate', 'endDate', 'userId', 'users'));
    }

    public function show_paymmetts($id)
    {
        $policies_ids = Rreport::where('payment_id', $id)->pluck('policy_id');
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        for ($i = 0; $i < count($policies_ids); $i++) {
            $object = Policy::where('id', $policies_ids[$i])->get();
            $policies[] = $object[0];
        }

        return view('admin-modules.Paymentt.show-paymentts', compact('policies', 'dolar', 'euro'));
    }

    public function search_users(Request $request)
    {

        $texto = $request->input('users');
        $type = Auth::user()->type;
        $users = User::where('id', $texto)->get();
        // Obtener usuarios para el select
        $users3 = User::where(function ($query) use ($type) {
            // Si el tipo de usuario es 4, incluir los tipos 4, 5 y 6
            if ($type == 4) {
                $query->whereIn('type', [4, 5, 6]);
            } else {
                // De lo contrario, incluir solo el tipo de usuario actual
                $query->where('type', $type);
            }
        })->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];

        return view('admin-modules.Payments.admin-payments-index', compact('users3', 'users', 'foreign_reference'));
    }

    //Cantidad de polizas sin pagar
    public static function policies_not_paid($user_id)
    {
        // Contar directamente en la base de datos sin cargar los registros en memoria
        $counted_policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->count();

        // Devuelve el conteo, que será 0 si no hay registros que cumplan con la consulta
        return $counted_policies;
    }

    public static function total_premium_sum($user_id)
    {
        // Sumar directamente el campo total_premium en la base de datos
        $total_premium = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->sum('total_premium');

        // Devuelve la suma de total_premium, que será 0 si no hay registros
        return $total_premium;
    }

    

    public function selected_payr(Request $request, $id)
    {
        $policies_selected = count($request->update_checkbox);

        $from_raw = Policy::where('user_id', $id)
            ->where('status', FALSE)
            ->orderBy('created_at', 'asc')
            ->limit(1)
            ->get('created_at');

        $until_raw = Policy::where('user_id', $id)
            ->where('status', FALSE)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get('created_at');

        // To store all the values of policies prices got from the user selection
        $total_all_raw = [];
        for ($i = 0; $i < $policies_selected; $i++) {
            array_push($total_all_raw, Policy::where('user_id', $id)
                ->where('status', FALSE)
                ->where('id', $request->update_checkbox[$i])
                ->pluck('total_premium')[0]);
        }

        $total_all = null;
        foreach ($total_all_raw as $total) {
            $total_all = $total_all + $total;
        }
        $total_payment = PaymentsController::profit_percentage($total_all, $request->profit_percentage);
        $from = Carbon::parse($from_raw[0]->created_at);
        $until = Carbon::now();

        $payment = new Payment;
        $payment->name = $request->name;
        $payment->office = $request->office;
        $payment->user_id = $id;
        $payment->total = $total_all;
        $payment->profit_percentage = $request->profit_percentage;
        $payment->total_payment = $total_payment;
        $payment->from = $from;
        $payment->until = $until;
        $payment->save();

        $payment_id = Payment::orderBy('created_at', 'desc')->limit(1)->pluck('id');

        $relations = [];

        for ($i = 0; $i < $policies_selected; $i++) {
            $relation = [];
            $relation = new Relation_po_pa;
            $relation['policy_id'] = $request->update_checkbox[$i];
            $relation['payment_id'] = $payment_id[0];
            $relations[$i] = $relation;
            $relation->save();
        }

        // This updates the selected policies that are being "paid"
        for ($i = 0; $i < $policies_selected; $i++) {
            Policy::where('user_id', $id)->where('status', FALSE)->where('id', $request->update_checkbox[$i])->update(['status' => TRUE]);
        }


        return redirect('/admin/index-paymentss')->with('success', 'Pago realizado correctamente');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_admin(Request $request, $id)
    {
        $from_raw = Policy::where('user_id', $id)
            ->where('status', FALSE)
            ->orderBy('created_at', 'asc')
            ->limit(1)
            ->get('created_at');

        $until_raw = Policy::where('user_id', $id)
            ->where('status', FALSE)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->get('created_at');

        $from = Carbon::parse($from_raw[0]->created_at);
        $until = Carbon::now();

        $payment = new Payment;
        $payment->name = $request->name;
        $payment->office = $request->office;
        $payment->user_id = $request->user_id;
        $payment->total = $request->total;
        $payment->profit_percentage = $request->profit_percentage;
        $payment->total_payment = $request->total_payment;
        $payment->from = $from;
        $payment->until = $until;
        $payment->save();
        $policy_update = Policy::where('user_id', $id)->where('status', FALSE)->update(['status' => TRUE]);
        // $policy_update->save();

        return redirect('/admin/index-payments')->with('success', 'Pago realizado correctamente');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'bill' => ['required', 'mimes:jpeg,bmp,png,gif,svg,pdf']
            ]
        );

        $payment = Payment::findOrFail($id);
        $payment->bill = $request->file('bill')->store('public');

        $payment->update();

        return redirect()->back()->with('success', 'Comprobante adjuntado exitosamente');
    }

    // Foreign module functions
    public function foreign_register(Request $request)
    {
        $this->validate($request, [
            'foreign_reference' => ['required', 'max:25', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
        ]);

        $all = $request->all();

        $pricesarr = [];
        foreach ($all as $item) {
            $replaced = str_replace(',', '', $item);

            array_push($pricesarr, $replaced);
        }

        $foreign = new ForeignUnit;
        $foreign->foreign_name = $request->foreign_name;
        $foreign->foreign_reference = $pricesarr[1];
        $foreign->save();

        return redirect('/admin')->with('success', 'Divisa registrada correctamente');
    }

    public function update_foreign(Request $request, $id)
    {

        $this->validate($request, [
            'foreign_reference' => ['required', 'max:25', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
        ]);

        $all = $request->all();

        $pricesarr = [];
        foreach ($all as $item) {
            $replaced = str_replace(',', '', $item);

            array_push($pricesarr, $replaced);
        }

        $foreign = ForeignUnit::findOrFail($id);
        $foreign->foreign_reference = $pricesarr[2];
        $foreign->update();

        return redirect()->back()->with('success', 'Divisa actualizada correctamente');
    }

    public function user_exportpdf()
    {
        $today = Carbon::now();
        $today = $today->format('Y-m-d');
        $user = Auth::user();
        $user_id = $user->id;
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;
        $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', FALSE)
            ->get();

        $data = [
            'policies' => $policies,
            'euro' => $euro,
            'dolar' => $dolar
        ];

        $pdf = PDF::loadView('user-modules.Payments.payments-export', $data)->setPaper('letter', 'portrait');
        $filename =  'Correlaivo ' . $user->name . ' ' . $user->lastname . ' ' . $user->office->office_address . ' ' . $today;
        return $pdf->stream($filename . '.pdf');
    }

    






    public function index_static_policies()
    {
        $summary = Policy::select(
            DB::raw('user_id'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as policy_count'),
            DB::raw('SUM(total_premium) as total_premium_sum')
        )
            ->whereYear('created_at', '>=', 2024)
            ->groupBy('user_id', 'year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->orderBy('user_id', 'asc')
            ->get();

        $totalSum = $summary->sum('total_premium_sum');
        return view('admin-modules.Payments.admin-payments-estadistica', compact('summary', 'totalSum'));
    }
    public static function policies_not_paid_price($user_id)
    {

        $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', FALSE)
            ->get();

        //Check if $policies is not null
        if ($policies->first() != null) {
            $prices = [];

            //Iterate over $policies to get the prices and push each price to an array
            foreach ($policies as $row) {
                array_push($prices, $row->total_premium);
            }

            //Sum the prices to get a total and return it
            $total = array_sum($prices);
            return $total;
        }

        // return 0 if $policies is null
        return 0;
    }

    public function admin_destroy_not_paid($id)
    {
        $policies = Policy::findOrFail($id);
        $policies->delete();
        return redirect()->back()->with('success', 'Eliminada con Exito');
    }

    /**
     * Muestra las polizas por pagar al proveedor
     */
    public function show_not_paid()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        $banks = Bank::all();
        $not_paid = $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', FALSE)
            ->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];

        return view('user-modules.Payments.payment-show-notpaid', compact('not_paid', 'foreign_reference', 'dolar', 'banks', 'euro'));
    }



    public static function profit_percentage($value1, $value3)
    {
        $suma = ($value3 * $value1) / 100;


        $result = $value1 - $suma;
        return $result;
    }
    

    public function policies_report()
    {
        $user = Auth::user();
        $comision = $user->profit_percentage;
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        // Obtener los pagos del usuario conectado
        $reportes = Pagos::where('user_id', $user->id)->get();

        // Para cada reporte, contar las relaciones y obtener pólizas con status 1
        $reportes->each(function ($reporte) {
            // Obtener todas las relaciones para este pago
            $relations = Rreport::where('payment_id', $reporte->id)->with('policy')->get();

            // Contar el total de relaciones
            $reporte->total_policies = $relations->count();

            // Filtrar las pólizas con status = 1 y obtener sus policy_id
            $reporte->status = $relations->filter(function ($relation) {
                return $relation->policy && $relation->policy->status == 0;
            })->count();
        });

        return view('user-modules.Payments.payments-report', compact('reportes', 'comision', 'dolar', 'euro'));
    }


    public function report_exportpdf($id)
    {
        // Obtener los IDs de las pólizas relacionadas con el pago
        $policies_ids = Rreport::where('payment_id', $id)->pluck('policy_id');
        $payment = Pagos::findOrFail($id);

        // Verificar si hay registros en ForeignUnit
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        // Obtener las pólizas correspondientes
        $policies = Policy::whereIn('id', $policies_ids)->get();

        // Preparar los datos para la vista
        $data = [
            'policies' => $policies,
            'payment' => $payment,
            'dolar' => $dolar,
            'euro' => $euro
        ];

        // Generar el PDF
        $pdf = PDF::loadView('payments-export-report', $data)->setPaper('letter', 'portrait');

        // Nombre del archivo PDF
        $filename = 'Reporte_Pago_' . $id . '.pdf';

        // Devolver el PDF para descargar
        return $pdf->stream($filename);
    }


    public function general_admin()
    {

        $type = Auth::user()->type;
        $users = User::where('type', $type)->orderBy('name', 'asc')->get();
        $date = Carbon::now();
        $today = $date->format('Y-m-d');
        $policies = Policy::whereDate('created_at', $today)->whereNull('status')->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];


        return view('admin-modules.Payments.admin-payment-general', compact('users', 'foreign_reference', 'policies'));
    }

    public function searchVendedor1(Request $request)
    {
        $adm = Auth::user();
        $type = $adm->type;
        $fechai = $request->input('fechai');
        $fechaf = $request->input('fechaf');
        $user = $request->input('user');
        $users = User::where('type', $type)->get();

        $user1 = null; // Valor predeterminado
        if ($user && $user != 0) {
            $user1 = User::where('id', $user)->first(); // Obtener el usuario específico si se selecciona
        }

        // Crear consulta base para pólizas
        if ($adm->id == 999501) {
            $query = Policy::query();
        } else {
            $query = Policy::query()->whereHas('user', function ($q) use ($type) {
                $q->where('type', $type); // Filtrar por el tipo de usuario conectado
            });
        }

        // Filtrar por rango de fechas si están presentes
        if ($fechai && $fechaf) {
            $fechaiFormatted = \Carbon\Carbon::parse($fechai)->startOfDay();
            $fechafFormatted = \Carbon\Carbon::parse($fechaf)->endOfDay();
            $query->whereBetween('created_at', [$fechaiFormatted, $fechafFormatted]);
        }

        // Si se selecciona un usuario específico, filtrar por user_id
        if ($user && $user != 0) {
            $query->where('user_id', $user);
        }

        $query->orderBy('type', 'asc')->orderBy('user_id', 'desc');
        $policies = $query->get();

        // Exportar a PDF si se solicita
        if ($request->has('export') && $request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('admin-modules.Payments.admin-payment-pdf', compact('policies', 'fechai', 'fechaf', 'users', 'user1'));
            $today = now()->format('Y-m-d');
            return $pdf->stream('reporte_pagos_' . $today . '.pdf');
        }

        // Determinar vista basada en el ID del administrador
        return view('admin-modules.Payments.admin-payment-general', compact('policies', 'fechai', 'fechaf', 'users', 'user'));
    }

    public function reportsuper($modId)
    {

        $updatedCount = Policy::whereHas('user', function ($query) use ($modId) {
            $query->where('mod_id', $modId);
        })
            ->where('status', 0)
            ->where('report', 0)
            ->update(['report' => 1]);
        return redirect()->back()->with('success', 'Se ha realizado el cierre correctamente');
    }
    /**
     * Valida los pagos contra la plataforma del BNC
     */
    public function validateP2P(Request $request, Policy2 $policy)
    {
        $request->validate([
            'Reference' => 'required|string',
            'DateMovement' => 'required|date',
        ]);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
        $cantidad_a_pagar = number_format($policy->total_premium * $foreign_reference, 2);

        // TODO: verificar si este mismo pago movil ya ha sido usado antes
        if(Rreport::where('reference_number', $request->input('Reference'))->first())
        {
            return response()->json(['status' => 'duplicado']);
        }

        $bnc = new Bnc();
        $pago_valido = $bnc->validarPagoMovilReferencia(
            $request->input('Reference'),
            $request->input('DateMovement'),
            $cantidad_a_pagar // Colocar aqui simplemente '10.01' para las pruebas
        );

        if ($pago_valido === false) {
            return response()->json(['status' => 'no-existe']);
        } else {
            // guardar pago movil en BD y pasar poliza a policies2
            $nueva_poliza = DB::transaction(function () use($policy, $pago_valido) {
                // TODO: id_bank == 16 -> BNC -> tabla de bancos requiere los numeros de estos y sacarlo de $pago_valido
                return Policy2::procesarPoliza($policy, 16, $pago_valido->Amount, $pago_valido->ReferenceA);
            });
            return response()->json(['status' => 'success', 'policy' => $nueva_poliza->id]);
        }
    }
}

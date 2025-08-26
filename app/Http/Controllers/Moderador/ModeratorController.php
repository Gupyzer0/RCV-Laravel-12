<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\User;
use App\Models\Moderator;
use App\Models\Vehicle;
use App\Models\Policy;
use App\Models\Office;
use App\Models\ActivityLog;
use App\Models\ForeignUnit;
use Carbon\Carbon;
use PDF;
use App\Models\Payment;
use App\Models\Relation_po_pa;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Parroquia;
use App\Models\VehicleType;

use App\Models\Bank;

class ModeratorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth:moderator');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $supervisor = Auth::user();
        $supervisorId = $supervisor->id; // El ID del supervisor logueado es el mod_id de sus supervisados

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $todayDate = Carbon::now()->toDateString();
        $sevenDaysFromNow = Carbon::now()->addDays(7)->toDateString();

        // Subconsulta para obtener los IDs de los usuarios supervisados por este supervisor
        $supervisedUserIds = User::select('id')->where('mod_id', $supervisorId);


        // *** Obtener todos los datos necesarios en el controlador para el supervisor ***

        // 1. Total Pólizas vendidas (solo de los supervisados por este supervisor)
        $policiesSoldAll = Policy::whereIn('user_id', $supervisedUserIds)->count();

        // 2. Pólizas vendidas este mes (solo de los supervisados por este supervisor)
        $policiesSoldMonth = Policy::whereIn('user_id', $supervisedUserIds)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // 3. Cantidad de Supervisados
        // Contar los usuarios cuyo mod_id es el ID del supervisor
        $supervisedUsersCount = (clone $supervisedUserIds)->count(); // Usamos una copia de la subconsulta o volvemos a construirla


        // 4. Vendedor del Mes (solo entre los supervisados por este supervisor)
        $bestSellerMonthData = Policy::select('user_id', DB::raw('count(*) as policies_count'))
            ->whereIn('user_id', $supervisedUserIds) // Filtrar por los IDs supervisados
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('user_id')
            ->orderByDesc('policies_count')
            ->first();

        $bestSellerMonthName = 'No hay vendedor del mes entre tus supervisados'; // Mensaje más específico
        if ($bestSellerMonthData && $bestSellerUser = User::select('name', 'lastname')->find($bestSellerMonthData->user_id)) {
            $bestSellerMonthName = $bestSellerUser->name . ' ' . $bestSellerUser->lastname;
        }

        // 5. Datos para el gráfico de pólizas vendidas al mes (solo de los supervisados por este supervisor)
        $monthlyPoliciesData = Policy::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
            ->whereIn('user_id', $supervisedUserIds) // Filtrar por los IDs supervisados
            ->whereYear('created_at', $currentYear)
            // ->where('deleted_at', null) // Quitar si usas Soft Deletes
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Formatear los datos para que Chart.js los entienda (un array con 12 valores)
        $policiesMonth = array_fill(0, 12, 0); // Inicializar array con 12 ceros
        foreach ($monthlyPoliciesData as $data) {
            $policiesMonth[$data->month - 1] = $data->count; // Poner el conteo en el índice correcto (mes - 1)
        }
        $policiesMonthJson = json_encode($policiesMonth); // Convertir a JSON para pasarlo a JS


        // NO necesitamos obtener datos de ForeignUnit porque no se usan en esta vista.


        // *** Pasar los datos a la vista ***
        return view('supervisor', compact( // Asegúrate de que 'supervisor' es el nombre correcto de tu vista
            'policiesSoldAll',
            'policiesSoldMonth',
            'supervisedUsersCount', // Cantidad de supervisados
            'bestSellerMonthName', // Nombre del mejor vendedor
            'policiesMonthJson' // Datos del gráfico
        ));
    } 

    


    //Polizas

    //Supervidor
 
    public function nanular_mod(Request $request, $id)
    {


        $policy  = Policy::findOrFail($id);
        $policy->statusu = NULL;

        $policy->update();


        return redirect('/mod/index-policiesd')->with('success', 'Se ha revocado la anulación correctamente');
    }

    //Pagos
   

    public function mod_exportpdf($user_id)
    {
        $user = User::find($user_id);

        $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', FALSE)
            ->where('report', TRUE)
            ->get();



        $data = [
            'policies' => $policies,
            'user' => $user
        ];


        $pdf = PDF::loadView('mod-modules.Payments.mod-payment-export', $data)->setPaper('letter', 'portrait');


        return $pdf->stream('Correlativo' . '.pdf');
    }

    public function selected_pay(Request $request, $id)
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
        $until = Carbon::parse($until_raw[0]->created_at);

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

        if (PaymentsController::policies_not_paid($id) == 0) {
            return redirect('/mod/index-payments/notpaidd/')->with('success', 'Pago realizado correctamente');
        } else {
            return redirect()->back()->with('success', 'Pago realizado correctamente');
        }
    }

    public function search_usersnopaid(Request $request)
    {



        $texto = $request->input('users');
        $id = Auth::user()->id;
        $users = User::where('username', $texto)->paginate(10);
        // Obtener usuarios para el select
        $users3 = User::where('mod_id', $id)->get();


        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];

        return view('mod-modules.Payments.mod-payments-index-notpaid', compact('users3', 'users', 'foreign_reference'));
    }

    public function report_mod($id)
    {
        Policy::where('user_id', $id)->where('report', FALSE)->update(['report' => TRUE]);

        return redirect()->back()->with('success', 'Se ha realizado el cierre correctamente');
    }

    public function report_all_mod()
    {
        $user = Auth::user();
        $type = $user->type;
        $mod_id = $user->id; // Asumo que el mod_id es el ID del usuario autenticado

        Policy::where('type', $type)
            ->where('report', 0)
            ->whereIn('user_id', function ($query) use ($mod_id) {
                $query->select('id')
                    ->from('users')
                    ->where('mod_id', $mod_id);
            })
            ->update(['report' => 1]);

        return redirect()->back()->with('success', 'Se ha realizado el porte general correctamente, ahora puede exportar');
    }
   
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    

    //Offices

    public function index_office()
    {
        $user = Auth::user()->id;
        $offices = Office::orderBy('cant', 'asc')
            ->where('mod_id', $user)
            ->get();
        $counter = 0;

        return view('mod-modules.Offices.mod-offices-index', compact('offices', 'counter'));
    }


    public function create_office()
    {
        $estados = Estado::all();

        return view('mod-modules.Offices.mod-offices-create', compact('estados'));
    }

    public function store_office(Request $request)
    {
        $this->validate($request, [
            'estado'    => ['required'],
            'municipio' => ['required'],
            'parroquia' => ['required'],
            'address'   => ['required']
        ]);

        $user = Auth::user();
        $office = new Office;
        $office->mod_id = $user->id;
        $office->type  = $user->type;
        $office->id_estado = $request->input('estado');
        $office->id_municipio = $request->input('municipio');
        $office->id_parroquia = $request->input('parroquia');
        $office->office_address = $request->input('address');


        $office->save();

        return redirect('/mod/index-offices')->with('success', 'Oficina registrada correctamente');
    }



    public function edit_office($id)
    {
        $office = Office::findOrFail($id);
        $estados = Estado::all();
        return  view('mod-modules.Offices.mod-office-edit', compact('office', 'id', 'estados'));
    }


    public function update_office(Request $request, $id)
    {
        $office = Office::findOrFail($id);
        $office->id_estado = $request->input('estado');
        $office->id_municipio = $request->input('municipio');
        $office->id_parroquia = $request->input('parroquia');
        $office->office_address = $request->input('address');
        $office->save();

        return redirect('/mod/index-offices')->with('success', 'Editado Correctamente');
    }

    // AJAX REQUESTS
    public function search_municipio(Request $request)
    {
        if ($request->ajax()) {
            $data = Municipio::where('id_estado',  $request->estadoId)->get();

            $output = '';
            $output = '<option value=""> - Seleccionar Municipio - </option>';

            foreach ($data as $row) {
                $output .= '<option value="' . $row->id_municipio . '">' . $row->municipio . '</option>';
            }

            return $output;
        }
    }

    public function search_parroquia(Request $request)
    {
        if ($request->ajax()) {
            $data = Parroquia::where('id_municipio',  $request->municipioId)->get();

            $output = '';
            $output = '<option value=""> - Seleccionar Parroquia - </option>';

            foreach ($data as $row) {
                $output .= '<option value="' . $row->id_parroquia . '">' . $row->parroquia . '</option>';
            }

            return $output;
        }
    }

    public function general_policies()
    {
        $mod_id = Auth::user()->id;
        $users = User::where('mod_id', $mod_id)->get();
        $date = Carbon::now();
        $today = $date->format('Y-m-d');
        $policies = Policy::whereDate('created_at', $today)->whereNull('status')->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];


        return view('mod-modules.Payments.mod-payment-general2', compact('users', 'foreign_reference', 'policies'));
    }

    public function searchVendedord(Request $request)
    {
        $mod_id = Auth::user()->id;
        $fechai = $request->input('fechai');
        $fechaf = $request->input('fechaf');
        $user = $request->input('user');
        $users = User::where('mod_id', $mod_id)->get();

        $user1 = null; // Valor predeterminado
        if ($user && $user != 0) {
            $user1 = User::where('id', $user)->first(); // Obtener el usuario específico si se selecciona
        }

        // Crear consulta base para pólizas

        $query = Policy::query()->whereHas('user', function ($q) use ($mod_id) {
            $q->where('mod_id', $mod_id); // Filtrar por el tipo de usuario conectado
        });


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

        $query->orderBy('user_id', 'desc');
        $policies = $query->get();

        // Exportar a PDF si se solicita
        if ($request->has('export') && $request->input('export') === 'pdf') {
            $pdf = Pdf::loadView('mod-modules.Payments.mod-payment-pdf', compact('policies', 'fechai', 'fechaf', 'users', 'user1'));
            $today = now()->format('Y-m-d');
            return $pdf->stream('reporte_pagos_' . $today . '.pdf');
        }

        // Determinar vista basada en el ID del administrador



        return view('mod-modules.Payments.mod-payment-general2', compact('policies', 'fechai', 'fechaf', 'users', 'user'));
    }
}

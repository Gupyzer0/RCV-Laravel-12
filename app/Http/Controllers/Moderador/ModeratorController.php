<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
use Illuminate\Support\Facades\Storage;
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

    /**
     * Lista de usuarios moderados
     */
    public function index_users_mod()
    {
        $users = Auth::user()->usuarios_moderados;
        return view('mod-modules.Users.mod-users-index', compact('users'));
    }


    //Polizas

    //Supervidor

    public function index_mod()
    {
        $today = Carbon::now();
        $user = Auth::user();
        $mod_id = $user->id;
        $type = $user->type;
        $policies = Policy::orderBy('created_at', 'desc')
            ->whereIn('user_id', function ($query) use ($mod_id) {
                $query->select('id')
                    ->from('users')
                    ->where('mod_id', $mod_id);
            })
            ->paginate(7);
        $counter = 0;

        return view('mod-modules.Policies.mod-policies-index', compact('policies', 'counter', 'today', 'type'));
    }

    public function index_search(Request $request)
    {
        $today = Carbon::now();
        $texto = trim($request->get('texto'));
        $user = Auth::user();
        $status = $user->status;
        $counter = 0;

        // Consulta base
        $query = Policy::select(
            'id',
            'user_id',
            'price_id',
            'client_ci',
            'vehicle_registration',
            'client_name',
            'client_lastname',
            'vehicle_brand',
            'vehicle_model',
            'created_at',
            'status',
            'damage_things',
            'client_phone'
        );

        // Aplicar filtro de búsqueda solo si hay texto
        if (!empty($texto)) {
            $query->where(function ($q) use ($texto) {
                $q->where('client_ci', 'LIKE', "%{$texto}%")
                    ->orWhere('vehicle_registration', 'LIKE', "%{$texto}%")
                    ->orWhere('id', 'LIKE', "%{$texto}%");
            });
        } else {
            // Si no hay texto, no devolver ningún resultado
            $query->where('id', 0); // Esto asegura que no se devuelvan registros
        }

        $policies = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('mod-modules.Policies.mod-policies-index', compact('policies', 'today', 'texto', 'status', 'counter'));
    }

    public function show_mod($id)
    {
        $policy = Policy::findOrFail($id);
        $today = Carbon::now();
        $expiring_date = Carbon::parse($policy->expiring_date);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];

        return view('mod-modules.Policies.mod-policies-show', compact('policy', 'today', 'expiring_date', 'foreign_reference'));
    }

    public function exportd_modpdf($id)
    {
        $policy = Policy::find($id);
        $doc = substr("$policy->client_ci_contractor", 0, 1);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        $qrCode = base64_encode(
            QrCode::format('png')
                ->size(150)
                ->color(0, 0, 0)
                ->backgroundColor(255, 255, 255, 0)
                ->generate('https://liderdeseguros.com/v/' . $policy->id)
        );



        $data = [
            'policy' => $policy,
            'euro' => $euro,
            'dolar' => $dolar,
            'doc' => $doc,
            'qrCode' => $qrCode
        ];

        // $customPaper = array(0,0,700,1050);

        $pdf = PDF::loadView('policy-pdf-digital', $data)->setPaper('letter', 'portrait');

        $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');


        return $pdf->stream($fileName . '.pdf');
    }

    public function nanular_mod(Request $request, $id)
    {


        $policy  = Policy::findOrFail($id);
        $policy->statusu = NULL;

        $policy->update();


        return redirect('/mod/index-policiesd')->with('success', 'Se ha revocado la anulación correctamente');
    }

    //Pagos

    /**
     * Lista de usuarios que tienen polizas pendientes por pagar
     */
    public function index_not_paid_mod()
    {
        $user = Auth::user();
        $mod_id = $user->id;

        // Obtener usuarios paginados con el número de pólizas contado
        // $users = User::select('users.*')
        //     ->leftJoin('policies', 'users.id', '=', 'policies.user_id')
        //     ->selectRaw('users.id, count(policies.id) as policies_count')
        //     ->where('users.mod_id', $mod_id)
        //     ->groupBy('users.id')
        //     ->orderBy('policies_count', 'desc')
        //     ->paginate(1000);
        
        $users = $user->usuarios_moderados;        

        // Obtener listas para datalists
        $users3 = User::whereIn('mod_id', $mod_id == 4 ? [4, 5, 6] : [$mod_id])->get(['id', 'username', 'name', 'lastname']);


        // Obtener resúmenes de políticas
        $policiesSummaries = Policy::selectRaw("
                 user_id,
                 SUM(CASE WHEN status = 0 AND report = 1 THEN 1 ELSE 0 END) as reportPaidCount,
                 SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as notPaidCount,
                 SUM(CASE WHEN status = 0 AND statusu = 1 THEN 1 ELSE 0 END) as nulasCount,
                 SUM(CASE WHEN status = 0 AND statusu IS NULL THEN total_premium ELSE 0 END) as totalNotPaidPrice
             ")
            ->whereIn('user_id', $users->pluck('id'))
            ->whereNull('deleted_at')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        return view('mod-modules.Payments.mod-payments-index-notpaid',  compact('users', 'users3', 'policiesSummaries'));
    }

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

    public function show_not_paid_mod($user_id)
    {

        $not_paid = $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user_id)
            ->where('status', FALSE)
            ->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];


        return view('mod-modules.Payments.mod-payment-show-notpaid', compact('not_paid', 'foreign_reference'));
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

    public function mod_cant(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $numeroct  = $request->input('numeroc1');
        $user->ncontra = $user->ncontra + $numeroct;
        $user->update();
        return redirect()->back()->with('success', 'Contratos agregados');
    }

    public function edit_contra(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $numeroct  = $request->input('numeroc1');
        $user->ncontra = $numeroct;
        $user->update();
        return redirect()->back()->with('success', 'Editado Correctamente');
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


    public function pdf_user($id)
    {
        $user = User::find($id);
        $data = [
            'user' => $user
        ];

        // $customPaper = array(0,0,700,1050);
        $pdf = PDF::loadView('admin-modules.Users.admin-user-export', $data)->setPaper('letter', 'portrait');

        $fileName = 'Acta de Compromiso ' . $user->name . ' ' . $user->lastname;

        return $pdf->stream($fileName . '.pdf');
    }

    public function showRegistrationForm()
    {
        $type = Auth::user()->type;
        $users = Moderator::where('type', $type)->get();
        $offices = Office::where('type', $type)->get();
        $banks = Bank::all();

        return view('mod-modules.Users.register-user', compact('offices', 'users', 'banks'));
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'id_type'           => ['required'],
            'ci'                => ['required', 'max:10', 'min:7', 'unique:users', 'regex:/[^A-Za-z-\s]+$/'],
            'ci_document'       => ['sometimes', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:2048'],
            'name'              => ['nullable', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z_ ]+$/'],
            'lastname'          => ['nullable', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z_ ]+$/'],
            'rif_pn'            => ['nullable', 'required_if:id_type,V-,E-', 'string', 'max:255'],
            'rif_pn_document'       => ['sometimes', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:2048'],
            'direccion_pn'      => ['nullable', 'string', 'max:255'],
            'google_maps_url_pn' => ['nullable', 'url', 'max:255'],
            'foto_establecimiento_pn_document'  => ['sometimes', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'foto_carnet_pn_document'           => ['sometimes', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'instagram_pn'      => ['nullable', 'string', 'max:255'],
            'facebook_pn'       => ['nullable', 'string', 'max:255'],
            'razon_social_pj'       => ['nullable', 'string', 'max:255'],
            'registro_mercantil_pj' => ['nullable', 'string', 'max:255'],
            'cedula_rl_pj'          => ['required_if:id_type,J-', 'nullable', 'string', 'max:255', 'regex:/[^A-Za-z-\s]+$/'],
            'cedula_rl_pj_document'             => ['sometimes', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:2048'],
            'direccion_pj'          => ['required_if:id_type,J-', 'nullable', 'string', 'max:255'],
            'google_maps_url_pj'    => ['nullable', 'url', 'max:255'], // URL es opcional
            'telefono_pj'           => ['nullable', 'string', 'max:20'], // Ajusta el max si es necesario
            'correo_pj'             => ['nullable', 'string', 'email', 'max:255'],
            'instagram_pj'          => ['nullable', 'string', 'max:255'],
            'facebook_pj'           => ['nullable', 'string', 'max:255'],
            'islr_pj_document'                  => ['sometimes', 'file', 'mimes:png,jpg,jpeg,pdf', 'max:2048'],
            'foto_establecimiento_pj_document'  => ['sometimes', 'file', 'mimes:png,jpg,jpeg', 'max:2048'], // Solo imágenes para fotos
            'foto_carnet_rl_pj_document'        => ['sometimes', 'file', 'mimes:png,jpg,jpeg', 'max:2048'],
            'phone_number'      => ['min:5', 'max:8', 'regex:/[^A-Za-z-\s]+$/'],
            'email'             => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username'          => ['required', 'string', 'min:2', 'max:255', 'regex:/^\S*$/u', 'unique:users'],
            'password'          => ['required', 'string', 'min:8', 'confirmed'],
            'office_id'         => ['required'],
            'profit_percentage' => ['required', 'numeric'],

        ], [
            'name.required' => 'Este campo no puede estar vacio',
            'name.min'      => 'El nombre debe tener al menos 2 letras',
            'name.max'      => 'El nombre no puede ser mas largo que 255 caracteres',
            'name.string'   => 'El nombre no debe tener caracteres especiales',
            'name.regex'    => 'El nombre no puede tener numeros',
            'lastname.required' => 'Este campo no puede estar vacio',
            'lastname.string'   => 'El apellido no puede contener caracteres especiales',
            'lastname.regex'    => 'El apellido no puede contener numeros',
            'lastname.max'      => 'El apellido no puede mas largo que 255 caracteres',
            'lastname.min'      => 'El apellido debe tener al menos 2 letras',
            'ci.required'       => 'Este campo no puede estar vacio',
            'ci.regex'          => 'Este campo no puede contener letras o espacios',
            'ci.min'            => 'La cedula debe tener al menos 2 numeros',
            'ci.max'            => 'La cedula no debe tener mas de 10 numeros',
            'ci.unique'         => 'Esta cedula ya esta en uso',
            'email.required' => 'Este campo no puede estar vacio',
            'email.email'   => 'Correo electronico invalido',
            'email.max'     => 'El correo electronico no puede ser mas largo que 255 caracteres',
            'email.unique'  => 'Este orreo electronico ya esta en uso',
            'office_id.required' => 'Este campo no puede estar vacio',
            'username.required' => 'Este campo no puede estar vacio',
            'username.min'      => 'El nombre de usuario no debe tener al menos 2 letras',
            'username.max'      => 'El nombre de usuario no puede ser mas largo que 255 caracteres',
            'username.regex'    => 'El nombre de usuario no puede contener espacios',
            'username.unique'   => 'Este nombre de usuario ya esta en uso',
            'profit_percentage.required' => 'Este campo no puede estar vacio',
            'profit_percentage.numeric'  => 'Este campo solo puede contener numeros',
            // 'phone_number.required'      => 'Este campo no puede estar vacio',
            'phone_number.min'           => 'Este campo debe tener al menos 5 números',
            'phone_number.max'           => 'Este campo no puede tener mas de 7 números',
            'phone_number.regex'         => 'Este campo no puede tener letras o espacios',
            'password.required' => 'Este campo no puede estar vacio',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La contraseña no coincide',
            // Mensajes para la validación del documento
            'ci_ima.required' => 'Debe subir un documento de identificación',
            'ci_ima.file'     => 'El campo debe ser un archivo',
            'ci_ima.mimes'    => 'El documento debe ser de tipo PNG, JPG, JPEG o PDF',
            'ci_ima.max'      => 'El documento no puede pesar más de 2MB',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user_ci = $data['id_type'] . $data['ci'];
        $names = strtolower($data['name']);
        $lastname = strtolower($data['lastname']);
        $user = Auth::user();

        if ($data['id_type'] == 'V-') {
            return User::create([
                'mod_id'            => $user->id,
                'type'              => $user->type,
                'name'              => ucwords($names),
                'lastname'          => ucwords($lastname),
                'username'          => $data['username'],
                'ci'                => $user_ci,
                'email'             => $data['email'],
                'office_id'         => $data['office_id'],
                'profit_percentage' => $data['profit_percentage'],
                'phone_number'      => $data['sp_prefix'] . $data['phone_number'],
                'ncontra'           => $data['ncontra'],
                'password'          => Hash::make($data['password']),
                'rif_pn'            => $data['rif_pn'] ?? null,
                'direccion_pn'      => $data['direccion_pn'] ?? null,
                'google_maps_url_pn' => $data['google_maps_url_pn'] ?? null,
                'instagram_pn'      => $data['instagram_pn'] ?? null,
                'facebook_pn'       => $data['facebook_pn'] ?? null,
            ]);
        } elseif ($data['id_type'] == 'J-') {
            return User::create([
                'mod_id'            => $user->id,
                'type'              => $user->type,


                // Campos adicionales del validador (no file)                          
                'name'   => $data['razon_social_pj'] ?? null,
                'lastname' => $data['registro_mercantil_pj'] ?? null,
                'cedula_rl_pj'      => $data['cedula_rl_pj'] ?? null,
                'direccion_pj'      => $data['direccion_pj'] ?? null,
                'google_maps_url_pj' => $data['google_maps_url_pj'] ?? null,
                'telefono_pj'       => $data['telefono_pj'] ?? null,
                'correo_pj'         => $data['correo_pj'] ?? null,
                'instagram_pj'      => $data['instagram_pj'] ?? null,
                'facebook_pj'       => $data['facebook_pj'] ?? null,
                'username'          => $data['username'],
                'ci'                => $user_ci,
                'email'             => $data['email'],
                'office_id'         => $data['office_id'],
                'profit_percentage' => $data['profit_percentage'],
                'phone_number'      => $data['telefono_pj'] . $data['sp_prefixj'],
                'ncontra'           => $data['ncontra'],
                'password'          => Hash::make($data['password']),
            ]);
        }
    }

    public function register(Request $request)
    {
        // Validar los datos, incluyendo los archivos
        $this->validator($request->all())->validate();

        // Crear el usuario
        event(new Registered($user = $this->create($request->all())));
        // Aquí puedes enviar un correo de bienvenida si lo deseas



        // --- Lógica para la carga y guardado de rutas de los múltiples documentos ---
        $filesToUpload = [
            'ci_document'                       => ['db_column' => 'ci_document',    'file_name' => 'cedula'],
            'rif_document'                      => ['db_column' => 'rif_document',   'file_name' => 'rif'],
            'foto_establecimiento_pn_document'  => ['db_column' => 'fotolocal',      'file_name' => 'foto_local'],
            'foto_carnet_pn_document'           => ['db_column' => 'fotocarnet',     'file_name' => 'foto_carnet'],
            'cedula_rl_pj_document'             => ['db_column' => 'ci_document_ju', 'file_name' => 'cedula_juridica'],
            'islr_pj_document'                  => ['db_column' => 'islr',           'file_name' => 'isrl'],
            'foto_establecimiento_pj_document'  => ['db_column' => 'fotolocal',      'file_name' => 'foto_local'],
            'foto_carnet_rl_pj_document'        => ['db_column' => 'fotocarnet',     'file_name' => 'foto_carnet'],
        ];

        $userId = $user->id; // Obtener el ID del usuario recién creado
        $uploadPath = 'uploads/users/' . $userId; // Carpeta de destino

        // Crear la carpeta si no existe
        Storage::disk('public')->makeDirectory($uploadPath);
        $destinationPath = public_path($uploadPath); // Ruta completa de destino física

        foreach ($filesToUpload as $formFieldName => $fileInfo) {
            if ($request->hasFile($formFieldName)) {
                $document = $request->file($formFieldName);
                $dbColumn = $fileInfo['db_column'];
                $targetFileName = $fileInfo['file_name'];
                $fileName = $targetFileName . '.' . $document->getClientOriginalExtension();
                $filePath = $uploadPath . '/' . $fileName;

                // Mover el archivo directamente sin procesamiento de imagen
                $document->move($destinationPath, $fileName);

                // Guardar la ruta del archivo en la base de datos
                $user->$dbColumn = $filePath;
            }
        }

        // Guardar los cambios en el usuario (incluyendo las rutas de los archivos)
        $user->save();
        // --- Fin de la lógica de carga ---

        return redirect('/mod/index-usersd')->with('success', 'Usuario registrado correctamente');
    }


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

    public function edit($id)
    {
        $policy          = Policy::findOrFail($id);
        $user1 = Auth::user();
        $user            = User::all();
        $vehicles        = Vehicle::distinct()->orderBy('brand', 'asc')->get('brand');
        $estados         = Estado::all();
        $vehicle_type = VehicleType::distinct()->orderBy('type', 'asc')->get('type');

        // $cant = $user->cantidadp;
        $cedula = $policy->client_ci;
        $cedula_contractor = $policy->client_ci_contractor;
        $id_type = substr($cedula, 0, 2);
        $id_type_contractor = substr($cedula_contractor, 0, 2);

        $identification = preg_split('/[A-Z].*?-/', $cedula);
        array_push($identification, $id_type);
        $identification_contractor = preg_split('/[A-Z].*?-/', $cedula_contractor);
        array_push($identification_contractor, $id_type_contractor);

        $kilos = $policy->vehicle_weight;
        $weight_num = preg_split('/[A-Z].*/', $kilos);

        $phone = $policy->client_phone;
        $client_phone = preg_split('/-/', $phone);

        return  view('mod-modules.Policies.mod-policies-edit', compact('policy', 'id', 'vehicle_type', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'user1'));
    }


    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                // Datos del Asegurado (Tomador)
                'client_name_contractor' => ['required', 'max:50', 'min:1'],
                'client_lastname_contractor' => ['required', 'max:50', 'min:1'],
                'client_ci_contractor' => ['required', 'max:10', 'min:5', 'regex:/[^A-Za-z-\s]+$/'],
                'id_type_contractor' => ['required'],

                // Datos del Tomador (Asegurado)
                'client_name' => ['required', 'max:50', 'min:1'],
                'client_lastname' => ['required', 'max:50', 'min:1'],
                'client_ci' => ['required', 'max:10', 'min:5', 'regex:/[^A-Za-z-\s]+$/'],
                'id_type' => ['required'],
                'client_email' => ['required', 'email', 'max:50'],
                'fecha_n' => ['required', 'date'],
                'estadocivil' => ['required'],
                'genero' => ['required'],
                'sp_prefix' => ['required'],
                'client_phone' => ['required', 'min:1', 'max:8', 'regex:/[^A-Za-z-\s]+$/'],
                // Dirección del Cliente
                'estado' => ['required'],
                'municipio' => ['required'],
                'parroquia' => ['required'],
                'client_address' => ['required', 'max:255'],
                // Datos del Vehículo
                'vehicleBrand' => ['required', 'max:50'],
                'vehicleModel' => ['required', 'max:50'],
                'vehicle_type' => ['required', 'max:50'],
                'vehicle_year' => ['required', 'numeric'],
                'vehicle_color' => ['required', 'max:25', 'min:1', 'regex:/^[a-zA-Z_ ]+$/'],
                'used_for' => ['required'],
                'vehicle_bodywork_serial' => ['required', 'max:25', 'min:1'],
                'vehicle_motor_serial' => ['required', 'max:25', 'min:1'],
                'vehicle_certificate_number' => ['required', 'max:25', 'min:1'],
                'vehicle_registration' => ['required', 'max:15', 'min:1'],
                'vehicle_weight' => ['required', 'regex:/[^A-Za-z-\s]+$/'],
                'trailer' => ['nullable'],

                // Imágenes (opcional, dependiendo de si son requeridas o no)
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
                'image1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,pdf', 'max:4096']
            ],
            [
                // Mensajes personalizados de error
                // Datos del Asegurado (Tomador)
                'client_name_contractor.required' => 'El nombre del contratante es obligatorio.',
                'client_name_contractor.max' => 'El nombre del contratante no debe exceder los 50 caracteres.',
                'client_name_contractor.min' => 'El nombre del contratante debe tener al menos 1 carácter.',
                'client_lastname_contractor.required' => 'El apellido del contratante es obligatorio.',
                'client_lastname_contractor.max' => 'El apellido del contratante no debe exceder los 50 caracteres.',
                'client_lastname_contractor.min' => 'El apellido del contratante debe tener al menos 1 carácter.',
                'client_ci_contractor.required' => 'El documento de identificación del contratante es obligatorio.',
                'client_ci_contractor.max' => 'El documento de identificación del contratante no debe exceder los 10 caracteres.',
                'client_ci_contractor.min' => 'El documento de identificación del contratante debe tener al menos 1 carácter.',
                'client_ci_contractor.regex' => 'El documento de identificación del contratante solo debe contener números.',
                'id_type_contractor.required' => 'El tipo de documento del contratante es obligatorio.',

                // Datos del Tomador (Asegurado)
                'client_name.required' => 'El nombre del tomador es obligatorio.',
                'client_name.max' => 'El nombre del tomador no debe exceder los 50 caracteres.',
                'client_name.min' => 'El nombre del tomador debe tener al menos 1 carácter.',
                'client_lastname.required' => 'El apellido del tomador es obligatorio.',
                'client_lastname.max' => 'El apellido del tomador no debe exceder los 50 caracteres.',
                'client_lastname.min' => 'El apellido del tomador debe tener al menos 1 carácter.',
                'client_ci.required' => 'El documento de identificación del tomador es obligatorio.',
                'client_ci.max' => 'El documento de identificación del tomador no debe exceder los 10 caracteres.',
                'client_ci.min' => 'El documento de identificación del tomador debe tener al menos 1 carácter.',
                'client_ci.regex' => 'El documento de identificación del tomador solo debe contener números.',
                'id_type.required' => 'El tipo de documento del tomador es obligatorio.',
                'client_email.required' => 'El correo electrónico del tomador es obligatorio.',
                'client_email.email' => 'El correo electrónico del tomador debe ser válido.',
                'client_email.max' => 'El correo electrónico del tomador no debe exceder los 50 caracteres.',
                'fecha_n.required' => 'La fecha de nacimiento del tomador es obligatoria.',
                'fecha_n.date' => 'La fecha de nacimiento del tomador debe ser una fecha válida.',
                'estadocivil.required' => 'El estado civil del tomador es obligatorio.',
                'genero.required' => 'El género del tomador es obligatorio.',
                'sp_prefix.required' => 'El prefijo del número de teléfono es obligatorio.',
                'client_phone.required' => 'El número de teléfono del tomador es obligatorio.',
                'client_phone.min' => 'El número de teléfono del tomador debe tener al menos 1 carácter.',
                'client_phone.max' => 'El número de teléfono del tomador no debe exceder los 8 caracteres.',
                'client_phone.regex' => 'El número de teléfono del tomador solo debe contener números.',

                // Dirección del Cliente
                'estado.required' => 'El estado es obligatorio.',
                'municipio.required' => 'El municipio es obligatorio.',
                'parroquia.required' => 'La parroquia es obligatoria.',
                'client_address.required' => 'La dirección del cliente es obligatoria.',
                'client_address.max' => 'La dirección del cliente no debe exceder los 255 caracteres.',

                // Datos del Vehículo
                'vehicleBrand.required' => 'La marca del vehículo es obligatoria.',
                'vehicleBrand.max' => 'La marca del vehículo no debe exceder los 50 caracteres.',
                'vehicleModel.required' => 'El modelo del vehículo es obligatorio.',
                'vehicleModel.max' => 'El modelo del vehículo no debe exceder los 50 caracteres.',
                'vehicle_type.required' => 'El tipo de vehículo es obligatorio.',
                'vehicle_type.max' => 'El tipo de vehículo no debe exceder los 50 caracteres.',
                'vehicle_year.required' => 'El año del vehículo es obligatorio.',
                'vehicle_year.numeric' => 'El año del vehículo debe ser un número.',
                'vehicle_color.required' => 'El color del vehículo es obligatorio.',
                'vehicle_color.max' => 'El color del vehículo no debe exceder los 25 caracteres.',
                'vehicle_color.min' => 'El color del vehículo debe tener al menos 1 carácter.',
                'vehicle_color.regex' => 'El color del vehículo solo debe contener letras.',
                'used_for.required' => 'El uso del vehículo es obligatorio.',
                'vehicle_bodywork_serial.required' => 'El serial de la carrocería es obligatorio.',
                'vehicle_bodywork_serial.max' => 'El serial de la carrocería no debe exceder los 25 caracteres.',
                'vehicle_bodywork_serial.min' => 'El serial de la carrocería debe tener al menos 1 carácter.',
                'vehicle_motor_serial.required' => 'El serial del motor es obligatorio.',
                'vehicle_motor_serial.max' => 'El serial del motor no debe exceder los 25 caracteres.',
                'vehicle_motor_serial.min' => 'El serial del motor debe tener al menos 1 carácter.',
                'vehicle_certificate_number.required' => 'El número de certificado es obligatorio.',
                'vehicle_certificate_number.max' => 'El número de certificado no debe exceder los 25 caracteres.',
                'vehicle_certificate_number.min' => 'El número de certificado debe tener al menos 1 carácter.',
                'vehicle_registration.required' => 'La placa o matrícula es obligatoria.',
                'vehicle_registration.max' => 'La placa o matrícula no debe exceder los 15 caracteres.',
                'vehicle_registration.min' => 'La placa o matrícula debe tener al menos 1 carácter.',
                'vehicle_weight.required' => 'El peso del vehículo es obligatorio.',
                'vehicle_weight.regex' => 'El peso del vehículo solo debe contener números.',

                // Poliza
                'vehicle_class.required' => 'La clase del vehículo es obligatoria.',
                'price.required' => 'El plan de la póliza es obligatorio.',

                // Imágenes
                'image.image' => 'El archivo debe ser una imagen válida.',
                'image.mimes' => 'El archivo debe ser de tipo: jpg, jpeg, png o pdf.',
                'image.max' => 'La imagen debe pesar menos de 4 MB.',
                'image1.image' => 'El archivo debe ser una imagen válida.',
                'image1.mimes' => 'El archivo debe ser de tipo: jpg, jpeg, png o pdf.',
                'image1.max' => 'La imagen debe pesar menos de 4 MB.',
            ]
        );

        //Get Vehicle id from select option using pluck to get the value in an arry of 1 item
        $vehicle_id = Vehicle::where('brand', $request->vehicleBrand)
            ->where('model', $request->vehicleModel)
            ->pluck('id');

        $policy  = Policy::findOrFail($id);

        // Datos del Asegurado
        $policy->client_name_contractor = ucwords(strtolower($request->input('client_name_contractor')));
        $policy->client_lastname_contractor = ucwords(strtolower($request->input('client_lastname_contractor')));
        $policy->client_ci_contractor       = $request->input('id_type_contractor') . $request->input('client_ci_contractor');

        // Datos del Tomador
        $policy->client_ci            = $request->input('id_type') . $request->input('client_ci');
        $policy->client_name          = ucwords(strtolower($request->input('client_name')));
        $policy->client_lastname      = ucwords(strtolower($request->input('client_lastname')));
        $policy->client_email         = $request->input('client_email');
        $policy->fecha_n              = $request->input('fecha_n');
        $policy->estadocivil          = $request->input('estadocivil');
        $policy->genero               = $request->input('genero');
        $policy->client_phone         = $request->input('sp_prefix') . $request->input('client_phone');
        $policy->id_estado            = $request->input('estado');
        $policy->id_municipio         = $request->input('municipio');
        $policy->id_parroquia         = $request->input('parroquia');
        $policy->client_address       = $request->input('client_address');


        // Datos del vehiculo

        $policy->vehicle_brand              = strtoupper($request->input('vehicleBrand'));
        $policy->vehicle_model              = strtoupper($request->input('vehicleModel'));
        $policy->vehicle_type               = strtoupper($request->input('vehicle_type'));
        $policy->vehicle_year               = $request->input('vehicle_year');
        $policy->vehicle_color              = strtoupper($request->input('vehicle_color'));
        $policy->used_for                   = strtoupper($request->input('used_for'));
        $policy->vehicle_bodywork_serial    = strtoupper($request->input('vehicle_bodywork_serial'));
        $policy->vehicle_motor_serial       = strtoupper($request->input('vehicle_motor_serial'));
        $policy->vehicle_certificate_number = strtoupper($request->input('vehicle_certificate_number'));
        $policy->vehicle_weight             = $request->input('vehicle_weight') . 'Kg';
        $policy->vehicle_registration       = strtoupper($request->input('vehicle_registration'));

        // Crear o verificar la carpeta de subida
        $uploadPath = public_path('uploads/' . $policy->id);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Procesar la imagen 'image' (Título de Propiedad)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'TP_' . preg_replace('/\D/', '', $policy->client_ci) . '.' . $extension;

            // Eliminar la imagen anterior si existe
            if ($policy->image_tp && file_exists($uploadPath . '/' . $policy->image_tp)) {
                unlink($uploadPath . '/' . $policy->image_tp);
            }

            // Mover la nueva imagen a la carpeta de subida
            $image->move($uploadPath, $imageName);
            $policy->image_tp = $imageName;
        }

        // Procesar la imagen 'image1' (Cédula o RIF)
        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $extension1 = $image1->getClientOriginalExtension();
            $imageName1 = 'CI_' . preg_replace('/\D/', '', $policy->client_ci) . '.' . $extension1;

            // Eliminar la imagen anterior si existe
            if ($policy->image_ci && file_exists($uploadPath . '/' . $policy->image_ci)) {
                unlink($uploadPath . '/' . $policy->image_ci);
            }

            // Mover la nueva imagen a la carpeta de subida
            $image1->move($uploadPath, $imageName1);
            $policy->image_ci = $imageName1;
        }


        $policy->update();

        return redirect('/mod/index-policiesd')->with('success', 'Poliza Actualizada correctamente');
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

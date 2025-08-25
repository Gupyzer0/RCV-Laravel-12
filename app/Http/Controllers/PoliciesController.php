<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Price;
use App\Models\Bank;
use App\Models\Policy;
use App\Models\Policy2;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use App\Models\VehicleType;
use App\Models\Estado;
use App\Models\Rreport;
use App\Models\ForeignUnit;
use PDF;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PoliciesController extends Controller
{
    /**
     * Index usuario normal
     */

    public function index(Request $request)
    {
        $user = Auth::user();
        $user_id = $user->id;
        $status = $user->status;
        $today = Carbon::now();
        $today = $today->format('Y-m-d');
        $vehicle_classes = VehicleClass::orderBy('class', 'asc')->get();
        $policies = Policy::where('user_id', '=', $user_id)
        ->where('deleted_at', NULL) // TODO: esto no es necesario si se usa adecuadamente ...
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        $counter = 0;

        if($request->input('poliza_registrada'))
        {
            $nueva_poliza = Policy::find($request->input('poliza_registrada'));
            return view('user-modules.Policies.policies-index', compact('user_id','vehicle_classes', 'policies', 'counter', 'today','status'))
                ->with('success','Cotización N° ' . $nueva_poliza->id . ' procesada exitosamente, N° de Póliza: ' . $nueva_poliza->idp);
        }
        return view('user-modules.Policies.policies-index', compact('user_id','vehicle_classes', 'policies', 'counter', 'today','status'));
        

        return view('user-modules.Policies.policies-index', compact('user_id','vehicle_classes', 'policies', 'counter', 'today','status'));
    }

    public function index_search(Request $request)
    {
        $today = Carbon::now();
        $texto = trim($request->get('texto'));
        $user = Auth::user();
        $status = $user->status;
        $banks = Bank::all();
        $counter = 0;

        // Consultar solo los registros no eliminados en la tabla `policies`

        $policies2 = Policy::select('id', 'user_id', 'client_ci', 'vehicle_registration', 'client_name', 'client_lastname', 'vehicle_brand', 'vehicle_model', 'created_at', 'status','damage_things','client_phone')
                           ->where(function ($query) use ($texto) {
                               $query->where('client_ci', 'LIKE', "%{$texto}%")
                                     ->orWhere('vehicle_registration', 'LIKE', "%{$texto}%");                         });
      

        // Combinar ambas consultas
        $policies = $policies2->orderBy('created_at', 'desc')->paginate(20);

        return view('user-modules.Policies.policies-index', compact('policies', 'today', 'texto', 'status','banks', 'counter'));
    }


   public function filterPolicies(Request $request)
    {
        $today = Carbon::now();
        $user = Auth::user();
        $user_id = $user->id;
        $status = $user->status;
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $counter = 0;

        $policies = Policy::whereBetween('created_at', [
            Carbon::parse($start_date)->startOfDay(),
            Carbon::parse($end_date)->endOfDay()
        ])
        ->where('user_id', '=', $user_id)
        ->paginate(50);

        return view('user-modules.Policies.policies-index', compact('policies', 'today','status', 'counter'));
    }

    public function index_vencidas()
    {
        $user = Auth::user();
        $user_id = $user->id;
        $status = $user->status;
        $today = Carbon::now();
        $today = $today->format('Y-m-d');
        $policies = Policy::where('expiring_date', '<' , $today)
        ->where('user_id', '=', $user_id)
        ->orderBy('expiring_date', 'desc')
        ->paginate(800);


        $counter = 0;

        return view('user-modules.Policies.policies-index', compact('user_id', 'policies', 'counter', 'today','status'));
    }

    public function index_search_admin ( Request $request){

      $today = Carbon::now();
        $user = Auth::user();
        $type = $user->type;
        $texto = trim($request->get('texto'));

        // Buscar pólizas tanto eliminadas como no eliminadas que coincidan con el texto de búsqueda
        $policies = Policy::where(function ($query) use ($texto) {
            $query->where('client_ci', 'LIKE', "%{$texto}%")
                  ->orWhere('vehicle_registration', 'LIKE', "%{$texto}%")
                  ->orWhere('id', 'LIKE', "%{$texto}%");
        })->paginate(5);

        return view('admin-modules.Policies.admin-policies-index', compact('policies', 'today','texto','type','user'));
     }

    public function index_static()
     {
         $startDate = '2025-01-01'; // Fecha de inicio: 1 de agosto de 2023
         $endDate = date('Y-m-d'); // Fecha de fin: hoytype
         $type = Auth::user()->type;


        $counter = Policy::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('CASE
                   WHEN DAY(created_at) BETWEEN 1 AND 15 THEN "1"
                        ELSE "2"
                     END as period'), // Agrupar en periodos 1-15 y 16-fin del mes
            DB::raw('SUM(CASE WHEN LEFT(client_ci, 1) = "V" THEN 1 ELSE 0 END) AS vene'),
            DB::raw('SUM(CASE WHEN LEFT(client_ci, 1) = "E" THEN 1 ELSE 0 END) AS extra'),
            DB::raw('SUM(CASE WHEN LEFT(client_ci, 1) = "J" THEN 1 ELSE 0 END) AS juri'),
            DB::raw('SUM(CASE WHEN LEFT(client_ci, 1) = "G" THEN 1 ELSE 0 END) AS gobi'),
            DB::raw('COUNT(*) as total_registros'),
            DB::raw('SUM(total_premium) as total_premiun_sum'),
            DB::raw('SUM(total_premium * `foreign`) as total_premiun_foreign_sum'),
            DB::raw('SUM(CASE WHEN status = 1 THEN total_premium ELSE 0 END) as total_premiun_sumr'),
            DB::raw('SUM(CASE WHEN status = 1 THEN total_premium * `foreign` ELSE 0 END) as total_premiun_foreign_sumr')
        )
        ->whereNull('deleted_at') // Excluye los registros eliminados
        ->whereNotNull('foreign') // Omitir registros donde foreign sea NULL
        ->whereBetween('created_at', [$startDate, $endDate]) // Filtrar por fecha entre agosto 2023 y hoy
        ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'), DB::raw('period')) // Agrupar por año, mes y periodo
        ->orderBy(DB::raw('YEAR(created_at)'), 'ASC')
        ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
        ->orderBy(DB::raw('period'), 'ASC')
        ->where('type', $type)
        ->get();

         $meses = [
             1 => 'Enero',
             2 => 'Febrero',
             3 => 'Marzo',
             4 => 'Abril',
             5 => 'Mayo',
             6 => 'Junio',
             7 => 'Julio',
             8 => 'Agosto',
             9 => 'Septiembre',
             10 => 'Octubre',
             11 => 'Noviembre',
             12 => 'Diciembre'
         ];


         $cant = Estado::withCount([
            'policies as vene' => function ($query) use ($startDate, $endDate, $type) { // ← Añadir $type
                $query->where('client_ci', 'LIKE', 'V%')
                      ->whereNotNull('foreign')
                      ->where('type', $type) // ← Ahora sí funciona
                      ->whereBetween('created_at', [$startDate, $endDate]);
            },          
    
             'policies as gobi' => function ($query) use ($startDate, $endDate, $type) {
                 $query->where('client_ci', 'LIKE', 'G%')
                       ->whereNotNull('foreign') // Omitir registros donde foreign sea NULL
                       ->where('type', $type)
                       ->whereBetween('created_at', [$startDate, $endDate])



                       ; // Filtrar solo registros con type = 2
             },
             'policies as extra' => function ($query) use ($startDate, $endDate, $type) {
                 $query->where('client_ci', 'LIKE', 'E%')
                       ->whereNotNull('foreign') // Omitir registros donde foreign sea NULL
                       ->where('type', $type)
                       ->whereBetween('created_at', [$startDate, $endDate])



                       ; // Filtrar solo registros con type = 2
             },
             'policies as juri' => function ($query) use ($startDate, $endDate, $type) {
                 $query->where('client_ci', 'LIKE', 'J%')
                       ->whereNotNull('foreign') // Omitir registros donde foreign sea NULL
                       ->where('type', $type)
                       ->whereBetween('created_at', [$startDate, $endDate])



                       ; // Filtrar solo registros con type = 2
             }
         ])->get();

         return view('admin-modules.Policies.admin-policies-static', compact('counter', 'meses', 'cant'));
    }


    public function index_admin(Request $request)
    {
        $type = Auth::user()->type;
        $today = Carbon::now();
        $policies = Policy::orderBy('created_at', 'desc')
            ->where ('type', $type)
            ->paginate(7);
            $counter = 0;

        if($request->input('poliza_registrada'))
        {
            $nueva_poliza = Policy::find($request->input('poliza_registrada'));
            return view('admin-modules.Policies.admin-policies-index', compact('policies', 'counter', 'today','type'))
                ->with('success','Cotización N° ' . $nueva_poliza->idp . ' procesada exitosamente, N° de Póliza: ' . $nueva_poliza->id);
        }
        return view('admin-modules.Policies.admin-policies-index', compact('policies', 'counter', 'today','type'));
    }

    public function export_todas($month)
    {
        $startOfMonth = Carbon::createFromDate(now()->year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate(now()->year, $month, 1)->endOfMonth();

        $policies = Policy::with(['estado', 'municipio', 'parroquia', 'class'])
            ->whereNull('deleted_at')
            ->where('status', 1)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])  // Filtrar por el mes actual
            ->get();

        $pdf = Pdf::loadView('export-todas', compact('policies'))
                  ->setPaper('letter', 'portrait');

        return $pdf->stream("policies_{$startOfMonth->format('F_Y')}.pdf");
    }


    public function deleted_admin()
    {
       $type = Auth::user()->type;
       $today = Carbon::now();
       $policies = Policy::onlyTrashed()
        ->where ('type', $type)
        ->paginate(7);
        $counter = 0;

        return view('admin-modules.Policies.admin-policies-deleted', compact('policies', 'counter', 'today','type'));
    }

    public function deleted_search_admin ( Request $request){
        $today = Carbon::now();
        $texto=trim($request->get('texto'));
        $policies = Policy::onlyTrashed()
        ->where('id', 'LIKE', "%{$request->get('texto')}%")
        ->orwhere('client_ci', 'LIKE', "%{$request->get('texto')}%")
        ->paginate(10);

        return view('admin-modules.Policies.admin-policies-deleted', compact('policies', 'today','texto'));
     }

    public function restore_policies($id)
    {
       Policy::withTrashed()->find($id)->restore();

        return redirect()->back()->with('success', 'Buscar la poliza Nuevamente');
    }


     public function vencida_admin()
    {
        $today = Carbon::now();
        $endDate = $today->subDay(5);
        $type = Auth::user()->type;
        $users = User::where('type', $type)->get();
        $today = $today->format('Y-m-d');
        $policies = Policy::where('expiring_date', '<=' , $endDate)
        ->where ('type', $type)
        ->orderBy('user_id', 'asc')
        ->get();
        $counter = 0;
        return view('admin-modules.Policies.admin-policies-vencidas', compact('policies', 'counter', 'today', 'users'));
    }
   

         public function filtervencida(Request $request)
    {
        $adm = Auth::user();
        $type = $adm->type;
        $fechai = $request->input('fechai');
        $fechaf = $request->input('fechaf');
        $user = $request->input('user');
        $users = User::all();
    
         $query = Policy::withTrashed(); // Incluye las pólizas eliminadas
    
        // Filtrar por rango de fechas si están presentes
        if ($fechai && $fechaf) {
            $fechaiFormatted = Carbon::parse($fechai)->startOfDay();
            $fechafFormatted = Carbon::parse($fechaf)->endOfDay();
            $query->whereBetween('expiring_date', [$fechaiFormatted, $fechafFormatted]);
        }
    
        // Si se selecciona un usuario específico, filtrar por user_id
        if ($user && $user != 0) {
            $query->where('user_id', $user);
        }
    
        // Filtrar por tipo de usuario
        $query->where('type', '=', $type)->orderBy('expiring_date', 'asc');
    
        $policies = $query->get();
    
        return view('admin-modules.Policies.admin-policies-vencidas', compact('policies', 'users'));
    }


    public static function vencida_count($user_id)
    {
      $today = Carbon::now();
      $today = $today->format('Y-m-d');
      $policies = Policy::where('deleted_at', null)
                          ->where('user_id', $user_id)
                          ->where('expiring_date', '<=' , $today)
                          ->get();

      // Check if policies is not null to avoid errors
      if($policies->first() != null){
        $policies_to_num = [];

        // Iterate over $policies to push each element to an array with the purpose of count the elements in it
        foreach ($policies as $row) {
          array_push($policies_to_num, $row);
        }

        // Count and return the counted elements
        $counted_policies = count($policies_to_num); 
        return $counted_policies;
      }

      // Return 0 if $policies is null
      return 0;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Seleccionar el tipo dependiendo de la marca y el modelo
        $user = Auth::user();
        $cant = $user->ncontra;

        $vehicle_classes = VehicleClass::orderBy('class', 'asc')->get();
        $vehicles = Vehicle::distinct()->orderBy('brand','asc')->get('brand');
        $vehicle_type = VehicleType::distinct()->orderBy('type','asc')->get('type');
        $estados = Estado::all();
        return view('user-modules.Policies.policies-create', compact('vehicles', 'vehicle_classes', 'vehicle_type', 'estados','user','cant'));


    }

    public function store(Request $request)
{
    $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
    $this->validate(
        $request,
        [
            'client_name' => ['required', 'max:255', 'min:1'],
            'client_lastname' => ['required', 'max:255', 'min:1'],
            'id_type' => ['required'],
            'id_type_contractor' => ['required'],
            'client_ci' => ['required', 'max:10', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
            'client_name_contractor' => ['required', 'max:255', 'min:1'],
            'client_lastname_contractor' => ['required', 'max:255', 'min:1'],
            'client_ci_contractor' => ['required', 'max:10', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
            'fecha_n' => ['required','date_format:Y-m-d'],
            'estadocivil' => ['required'],
            'genero' => ['required'],
            'estado' => ['required'],
            'municipio' => ['required'],
            'parroquia' => ['required'],
            'client_address' => ['required'],
            'vehicleBrand' => ['required'],
            'vehicleModel' => ['required'],
            'vehicle_type' => ['required'],
            'vehicle_year' => ['required', 'numeric'],
            'vehicle_class' => ['required'],
            'vehicle_color' => ['required', 'max:25', 'min:1'],
            'used_for' => ['required'],
            'vehicle_bodywork_serial' => ['required', 'max:25', 'min:1'],
            'vehicle_motor_serial'  => ['required', 'max:25', 'min:1'],
            'vehicle_certificate_number' => ['required', 'max:25', 'min:1'],
            'vehicle_registration' => ['required','max:15', 'min:1'],
            'vehicle_weight' => ['required', 'regex:/[^A-Za-z-\s]+$/'],
            'price' => ['required'],
            'sp_prefix' => ['required'],
            'client_phone' => ['min:1', 'max:8', 'regex:/[^A-Za-z-\s]+$/'],
            'image' => ['mimes:jpeg,png,jpg,pdf', 'max:8192'],
            'image1' => ['mimes:jpeg,png,jpg,pdf', 'max:8192']
        ],
        [
            'client_name.required' => 'El nombre del cliente es obligatorio.',
            'client_name.max' => 'El nombre del cliente no puede exceder los 255 caracteres.',
            'client_lastname.required' => 'El apellido del cliente es obligatorio.',
            'client_lastname.max' => 'El apellido del cliente no puede exceder los 255 caracteres.',
            'id_type.required' => 'Debe seleccionar un tipo de identificación.',
            'id_type_contractor.required' => 'Debe seleccionar un tipo de identificación para el contratante.',
            'client_ci.required' => 'El número de cédula es obligatorio.',
            'client_ci.max' => 'La cédula no puede tener más de 10 caracteres.',
            'client_ci.regex' => 'La cédula debe contener solo números.',
            'client_name_contractor.required' => 'El nombre del contratante es obligatorio.',
            'client_lastname_contractor.required' => 'El apellido del contratante es obligatorio.',
            'client_ci_contractor.required' => 'La cédula del contratante es obligatoria.',
            'fecha_n.required' => 'Debe colocar la fecha de nacimiento.',
            'fecha_n.date_format' => 'La fecha de nacimiento debe estar en formato válido (DIA-MES-AÑO).',
            'fecha_n.before' => 'Debe ser mayor de edad para continuar.',
            'estadocivil.required' => 'Debe seleccionar un estado civil.',
            'genero.required' => 'Debe seleccionar el género.',
            'estado.required' => 'Debe seleccionar un estado.',
            'municipio.required' => 'Debe seleccionar un municipio.',
            'parroquia.required' => 'Debe seleccionar una parroquia.',
            'client_address.required' => 'Debe colocar la dirección.',
            'vehicleBrand.required' => 'Debe ingresar la marca del vehículo.',
            'vehicleModel.required' => 'Debe ingresar el modelo del vehículo.',
            'vehicle_type.required' => 'Debe seleccionar el tipo de vehículo.',
            'vehicle_year.required' => 'Debe ingresar el año del vehículo.',
            'vehicle_year.numeric' => 'El año del vehículo debe ser un número.',
            'vehicle_class.required' => 'Debe seleccionar la clase del vehículo.',
            'vehicle_color.required' => 'Debe ingresar el color del vehículo.',
            'vehicle_color.max' => 'El color del vehículo no puede exceder los 25 caracteres.',
            'used_for.required' => 'Debe especificar el uso del vehículo.',
            'vehicle_bodywork_serial.required' => 'Debe ingresar el serial de carrocería.',
            'vehicle_motor_serial.required' => 'Debe ingresar el serial del motor.',
            'vehicle_certificate_number.required' => 'Debe ingresar el número de certificado del vehículo.',
            'vehicle_registration.required' => 'Debe ingresar la placa del vehículo.',
            'vehicle_weight.required' => 'Debe ingresar el peso del vehículo.',
            'vehicle_weight.regex' => 'El peso del vehículo debe contener solo números.',
            'price.required' => 'Debe ingresar el precio.',
            'sp_prefix.required' => 'Debe ingresar el prefijo SP.',
            'client_phone.regex' => 'El teléfono debe contener solo números.',
            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe estar en formato jpeg, png, jpg o gif.',
            'image.max' => 'La imagen no debe exceder 4MB.',
            'image1.image' => 'El archivo debe ser una imagen.',
            'image1.mimes' => 'La imagen debe estar en formato jpeg, png, jpg o gif.',
            'image1.max' => 'La imagen no debe exceder 4MB.'
        ]
    );

    $user = Auth::user();
    $user_id = $user->id;
    $type = $user->type;
    $cant = $user->ncontra - 1;
    $user->ncontra = $cant;
    $user->update();

    $client_ci = $request->input('id_type') . $request->input('client_ci');
    $vehicle_weight = $request->input('vehicle_weight') . 'Kg';

    $policy = new Policy2;
    $policy->user_id = $user_id;
    $policy->type = $type;
    $policy->expiring_date = Carbon::now()->addYear();
    $policy->vehicle_id = 0;
    $policy->vehicle_class_id = $request->input('vehicle_class');
    $policy->vehicle_type = strtoupper($request->input('vehicle_type'));
    $policy->vehicle_brand = strtoupper($request->input('vehicleBrand'));
    $policy->vehicle_model = strtoupper($request->input('vehicleModel'));
    $policy->vehicle_year = $request->input('vehicle_year');
    $policy->vehicle_color = ucwords($request->input('vehicle_color'));
    $policy->vehicle_weight = $vehicle_weight;
    $policy->vehicle_bodywork_serial = strtoupper($request->input('vehicle_bodywork_serial'));
    $policy->vehicle_motor_serial = strtoupper($request->input('vehicle_motor_serial'));
    $policy->vehicle_certificate_number = strtoupper($request->input('vehicle_certificate_number'));
    $policy->vehicle_registration = strtoupper($request->input('vehicle_registration'));
    $policy->used_for = $request->input('used_for');
    $policy->trailer = $request->input('trailer');
    $policy->client_name = ucwords(strtolower($request->input('client_name')));
    $policy->client_lastname = ucwords(strtolower($request->input('client_lastname')));
    $policy->client_email = strtoupper($request->input('client_email'));
    $policy->client_phone = $request->input('sp_prefix') . $request->input('client_phone');
    $policy->client_ci = strtoupper($client_ci);
    $policy->fecha_n = $request->input('fecha_n');
    $policy->estadocivil = $request->input('estadocivil');
    $policy->genero = $request->input('genero');
    $policy->id_estado = $request->input('estado');
    $policy->id_municipio = $request->input('municipio');
    $policy->id_parroquia = $request->input('parroquia');
    $policy->client_address = $request->input('client_address');
    $policy->client_name_contractor = ucwords(strtolower($request->input('client_name_contractor')));
    $policy->client_lastname_contractor = ucwords(strtolower($request->input('client_lastname_contractor')));
    $policy->client_ci_contractor = strtoupper($request->input('id_type_contractor') . $request->input('client_ci_contractor'));
    

    // Datos del precio al momento de crear la poliza
    $price_info = Price::find($request->input('price'));
    $policy->price_id = $request->input('price');

    if ($request->input('trailer') == '1') {
        $ext = $price_info->total_premium * 20 / 100;
        $policy->total_premium = $price_info->total_premium + $ext;
    } elseif ($request->input('price') == '36'|| $request->input('price') == '97' || $request->input('price') == '61') {
        $peso = ceil($request->input('vehicle_weight') / 1000) - 12;
        $policy->total_premium = ($peso * 5) + $price_info->total_premium;
    } else {
        $policy->total_premium = $price_info->total_premium;
    }

    $policy->total_all = $price_info->total_all;
    $policy->foreign = $foreign_reference;
    $policy->dolar = ForeignUnit::skip(1)->first()->foreign_reference;
    $policy->save();
    
    // Procesamiento y almacenamiento de la imagen
    $uploadPath = public_path('uploads/' . $policy->id);
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $extension = $image->getClientOriginalExtension();
        $imageName = 'TP_' . preg_replace('/\D/', '', $policy->client_ci) . '.' . $extension;
        $image->move($uploadPath, $imageName);
        if ($policy->image_tp && file_exists($uploadPath . '/' . $policy->image_tp)) {
            unlink($uploadPath . '/' . $policy->image_tp);
        }
        $policy->image_tp = $imageName;
    }

    if ($request->hasFile('image1')) {
        $image1 = $request->file('image1');
        $extension1 = $image1->getClientOriginalExtension();
        $imageName1 = 'CI_' . preg_replace('/\D/', '', $policy->client_ci) . '.' . $extension1;
        $image1->move($uploadPath, $imageName1);
        if ($policy->image_ci && file_exists($uploadPath . '/' . $policy->image_ci)) {
            unlink($uploadPath . '/' . $policy->image_ci);
        }
        $policy->image_ci = $imageName1;
    }
    $policy->save();

    return redirect('/user/solicitud-policies')->with('success', 'Solicitud Cargada correctamente');
}

    public function upload_document(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'image' => ['mimes:jpeg,png,jpg,pdf', 'max:4096'],
                'image1' => ['mimes:jpeg,png,jpg,pdf', 'max:4096']
            ], [
                'image.image' => 'El archivo debe ser una imagen o un PDF.',
                'image.mimes' => 'El archivo debe estar en formato jpeg, png, jpg o pdf.',
                'image.max' => 'El archivo no debe exceder 2MB.',
                'image1.image' => 'El archivo debe ser una imagen o un PDF.',
                'image1.mimes' => 'El archivo debe estar en formato jpeg, png, jpg o pdf.',
                'image1.max' => 'El archivo no debe exceder 2MB.'
            ]
        );

        $policy = Policy::findOrFail($id);

        // Crear una carpeta específica para la póliza
        $uploadPath = public_path('uploads/' . $policy->id);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'TP_' . preg_replace('/\D/', '', $policy->client_ci) .'.' . $extension;
            $image->move($uploadPath, $imageName);

            if ($policy->image_tp && file_exists($uploadPath . '/' . $policy->image_tp)) {
                unlink($uploadPath . '/' . $policy->image_tp);
            }
            $policy->image_tp = $imageName;
        }

        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $extension1 = $image1->getClientOriginalExtension();
            $imageName1 = 'CI_' . preg_replace('/\D/', '', $policy->client_ci) .'.' . $extension1;
            $image1->move($uploadPath, $imageName1);

            if ($policy->image_ci && file_exists($uploadPath . '/' . $policy->image_ci)) {
                unlink($uploadPath . '/' . $policy->image_ci);
            }
            $policy->image_ci = $imageName1;
        }

        $policy->update();

        return redirect()->back()->with('success', 'Documentos Cargados Correctamente');
    }

    public function upload_documents(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'image' => ['mimes:jpeg,png,jpg,pdf', 'max:2048'],
                'image1' => ['mimes:jpeg,png,jpg,pdf', 'max:2048']
            ], [
                'image.image' => 'El archivo debe ser una imagen o un PDF.',
                'image.mimes' => 'El archivo debe estar en formato jpeg, png, jpg o pdf.',
                'image.max' => 'El archivo no debe exceder 2MB.',
                'image1.image' => 'El archivo debe ser una imagen o un PDF.',
                'image1.mimes' => 'El archivo debe estar en formato jpeg, png, jpg o pdf.',
                'image1.max' => 'El archivo no debe exceder 2MB.'
            ]
        );

        $policy = Policy2::findOrFail($id);

        // Crear una carpeta específica para la póliza
        $uploadPath = public_path('uploads/solicitud/' . $policy->id);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'TP_' . preg_replace('/\D/', '', $policy->client_ci) .'.' . $extension;
            $image->move($uploadPath, $imageName);

            if ($policy->image_tp && file_exists($uploadPath . '/' . $policy->image_tp)) {
                unlink($uploadPath . '/' . $policy->image_tp);
            }
            $policy->image_tp = $imageName;
        }

        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $extension1 = $image1->getClientOriginalExtension();
            $imageName1 = 'CI_' . preg_replace('/\D/', '', $policy->client_ci) .'.' . $extension1;
            $image1->move($uploadPath, $imageName1);

            if ($policy->image_ci && file_exists($uploadPath . '/' . $policy->image_ci)) {
                unlink($uploadPath . '/' . $policy->image_ci);
            }
            $policy->image_ci = $imageName1;
        }

        $policy->update();

        return redirect()->back()->with('success', 'Documentos Cargados Correctamente');
    }

    public function procePolicy($id)
    {
        $user_id = Auth::user()->id;
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

           // Actualizar el estado de la póliza
        $policy2 = Policy2::findOrFail($id);
        $policy = new Policy();
        $data = $policy2->toArray();
        $data['idp'] = $data['id'];
        unset($data['id']);
        $policy->fill($data);
        $expiring_date = Carbon::now()->addYear();
        $policy->expiring_date = $expiring_date;
        $policy->foreign = $euro;
        $policy->dolar = $dolar;   
        $policy->save();
        if($policy->idp == $policy2->id){
            $policy2->delete();
        }

        return redirect('/user/index-policies')->with('success', 'Cotización N° ' . $policy->idp . ' procesada exitosamente, N° de Póliza: ' . $policy->id);
  }
  
  /**
   * TODO: Este metodo creo que queda depreciado
   */
  public function procePolicy2 (Request $request, $id)
{
    // Obtener tasas de cambio
    $euro = ForeignUnit::first()->foreign_reference;
    $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

    // Procesar la póliza
    $policy2 = Policy2::findOrFail($id);
    $policy = new Policy();
    
    $data = $policy2->toArray();
    $data['idp'] = $data['id'];
    unset($data['id']);
    
    $policy->fill($data);
    $policy->expiring_date = Carbon::now()->addYear();
    $policy->foreign = $euro;
    $dolar = $dolar;
    $policy->save();

    // Registrar el pago
    $pago = new Rreport();
    $pago->id_policy = $policy->id;
    $pago->user_id = auth()->id();
    $pago->id_bank = $request->input('bank');
    $pago->amount = str_replace(',', '.', $request->input('amount'));
    $pago->reference_number = $request->input('reference_number');
    $pago->save();

    // Eliminar la cotización original
    if($policy->idp == $policy2->id) {
        $policy2->delete();
    }

    return redirect('/user/index-policies')->with('success', 
        'Cotización N° ' . $policy->idp . ' procesada exitosamente, N° de Póliza: ' . $policy->id // -> idp == correlativo
    );
}




    public function show_admin($id)
    {
        $policy = Policy::findOrFail($id);
        $today = Carbon::now();
        $expiring_date = Carbon::parse($policy->expiring_date);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];

        return view('admin-modules.Policies.admin-policy-show', compact('policy', 'today', 'expiring_date', 'foreign_reference'));
    }

    public function admin_edit($id)
    {
        $type           = Auth::user()->type;
        $policy          = Policy::findOrFail($id);
        $user            = User::where('type', $type)->orderBy('name', 'asc')->get();
        $vehicles        = Vehicle::distinct()->orderBy('brand', 'asc')->get('brand');
        $prices          = Price::all();
        $estados         = Estado::all();
        $vehicle_classes = VehicleClass::all();
        $vehicle_type = VehicleType::distinct()->orderBy('type','asc')->get('type');


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

        return  view('admin-modules.Policies.admin-policy-edit', compact('policy', 'id','user', 'vehicle_type', 'vehicles', 'prices', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'vehicle_classes'));
    }


    public function admin_update(Request $request, $id)
    {

        $this->validate(
            $request,
            [

                'username' => ['max:255'],
                'client_name' => ['required', 'max:255', 'min:1'],
                'client_lastname' => ['required', 'max:255', 'min:1'],

                'client_ci' => ['required', 'max:10', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
                'client_name_contractor' => ['required', 'max:255', 'min:1'],
                'client_lastname_contractor' => ['required', 'max:255', 'min:1'],
                'client_ci_contractor' => ['required', 'max:10', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
                'estado' => ['required'],
                'municipio' => ['required'],
                'parroquia' => ['required'],
                'vehicleBrand' => ['required'],
                'vehicleModel' => ['required'],
                'vehicle_type' => ['required'],
                'vehicle_year' => ['required', 'numeric'],
                'vehicle_class' => ['required'],
                'vehicle_color' => ['required', 'max:25', 'min:1', 'regex:/^[a-zA-Z_ ]+$/'],
                'used_for' => ['required'],
                'vehicle_bodywork_serial' => ['required', 'max:25', 'min:1'],
                'vehicle_motor_serial'  => ['required', 'max:25', 'min:1'],
                'vehicle_certificate_number' => ['required', 'max:25', 'min:1'],
                'vehicle_registration' => ['required', 'max:15', 'min:1'],
                'vehicle_weight' => ['required', 'regex:/[^A-Za-z-\s]+$/'],
                'price' => ['required'],
                'client_phone'      => ['min:1', 'max:8', 'regex:/[^A-Za-z-\s]+$/']
            ], [

            ]
        );


        //Get Vehicle id from select option using pluck to get the value in an arry of 1 item
        $vehicle_id = Vehicle::where('brand', $request->vehicleBrand)
            ->where('model', $request->vehicleModel)
            ->pluck('id');

        $policy  = Policy::findOrFail($id);

        $vehicle_weight = $request->input('vehicle_weight') . 'Kg';

        // Numero de Poliza

        $policy->user_id             = $request->input('username');
        $policy->created_at          = $request->input('created_at');
        $policy->expiring_date       = $request->input('expiring_date');

        // Datos del vehiculo

        $policy->vehicle_id                 = 0;
        $policy->vehicle_type               = $request->input('vehicle_type');
        $policy->vehicle_brand              = strtoupper($request->input('vehicleBrand'));
        $policy->vehicle_model              = strtoupper($request->input('vehicleModel'));
        $policy->vehicle_class_id           = $request->input('vehicle_class');
        $policy->vehicle_year               = $request->input('vehicle_year');
        $policy->vehicle_color              = strtoupper($request->input('vehicle_color'));
        $policy->vehicle_weight             = $vehicle_weight;
        $policy->vehicle_bodywork_serial    = strtoupper($request->input('vehicle_bodywork_serial'));
        $policy->vehicle_motor_serial       = strtoupper($request->input('vehicle_motor_serial'));
        $policy->vehicle_certificate_number = strtoupper($request->input('vehicle_certificate_number'));
        $policy->vehicle_registration       = strtoupper($request->input('vehicle_registration'));
        $policy->used_for                   = strtoupper($request->input('used_for'));

        // Datos del cliente
        $client_names = strtolower($request->input('client_name'));
        $client_lastname = strtolower($request->input('client_lastname'));
        $client_ci      = $request->input('id_type') . $request->input('client_ci');

        $policy->client_name          = ucwords($client_names);
        $policy->client_lastname      = ucwords($client_lastname);
        $policy->client_email         = strtoupper($request->input('client_email'));
        $policy->client_phone         = $request->input('sp_prefix') . $request->input('client_phone');
        $policy->client_ci            = strtoupper($client_ci);
        $policy->id_estado            = $request->input('estado');
        $policy->id_municipio         = $request->input('municipio');
        $policy->id_parroquia         = $request->input('parroquia');
        $policy->client_address       = $request->input('client_address');

        // Datos del beneficiario
        $client_names_contractor    = strtolower($request->input('client_name_contractor'));
        $client_lastname_contractor = strtolower($request->input('client_lastname_contractor'));
        $client_ci_contractor       = $request->input('id_type_contractor') . $request->input('client_ci_contractor');
        $policy->client_name_contractor     = ucwords($client_names_contractor);
        $policy->client_lastname_contractor = ucwords($client_lastname_contractor);
        $policy->client_ci_contractor       = strtoupper($client_ci_contractor);

        // Datos del precio
        $price_info = Price::where('id', $request->input('price'))->withTrashed()->first();

        if($request->input('price') == $policy->price_id){
            $policy->save();
            // return redirect('/admin/index-policies');
            // return gettype($policy->price_id);
            return redirect('/admin/index-policies');
        }else{
            $policy->price_id = $request->input('price');

            $policy->total_premium = $price_info->total_premium;
            $policy->total_all = $price_info->total_all;


            $policy->save();
            return redirect('/admin/index-policies');
        }

    }


    public function admin_destroy($id)
    {
        $policies = Policy::findOrFail($id);
        $policies->delete();
        return redirect('/admin/index-policies');
    }

    public function user_exportpdf($id)
    {
        $policy = Policy2::find($id);
        $user  = Auth::user();
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        if (!$policy) {
            abort(404, 'Policy not found');
        }



        // Calcular la edad a partir de fecha_n
        $edad = null;
        $hoy = Carbon::now();
        if ($policy->fecha_n) {
            $edad = \Carbon\Carbon::parse($policy->fecha_n)->age;
        }

        $data = [
            'policy' => $policy,
            'euro' => $euro,
            'dolar' => $dolar,
            'edad' => $edad,
            'hoy' => $hoy,
            'user' => $user
        ];

        $pdf = PDF::loadView('solicitud-pdf', $data)->setPaper('letter', 'portrait');

        if ($policy->id) {
            $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');
        } else {
            $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');
        }

        return $pdf->stream($fileName . '.pdf');
    }




        public function exportpdf_so($id)
    {
        $policy = Policy::find($id);
        $doc = substr("$policy->client_ci_contractor",0,1);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        $qrCode = base64_encode(
            QrCode::format('png')
                  ->size(150)
                  ->color(0, 0, 0)
                  ->backgroundColor(255, 255, 255, 0)
                  ->generate('https://liderdeseguros.com/v/' . $policy->id)
        );



        $data = ['policy' => $policy,
                 'euro' => $euro,
                 'dolar' => $dolar,
                 'doc' => $doc,
                 'qrCode' => $qrCode
                ];


       // $customPaper = array(0,0,700,1050);

        $pdf = PDF::loadView('policy-pdf', $data)->setPaper('letter', 'portrait');

            $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');


        return $pdf->stream($fileName . '.pdf');
    }

    public function exportpdf_digital($id)
    {
        $policy = Policy::find($id);
        $doc = substr("$policy->client_ci_contractor",0,1);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        $qrCode = base64_encode(
            QrCode::format('png')
                  ->size(150)
                  ->color(0, 0, 0)
                  ->backgroundColor(255, 255, 255, 0)
                  ->generate('https://liderdeseguros.com/v/' . $policy->id)
        );



        $data = ['policy' => $policy,
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

    public function admin_exportpdf_digital2($id)
    {
        $policy = Policy::find($id);
        $doc = substr("$policy->client_ci_contractor",0,1);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
        $data = ['policy' => $policy,
                 'foreign_reference' => $foreign_reference,
                 'doc' => $doc
                ];


       // $customPaper = array(0,0,700,1050);

        $pdf = PDF::loadView('Poliza-Digital', $data)->setPaper('letter', 'portrait');

            $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');


        return $pdf->stream($fileName . '.pdf');
    }

            public function admin_exportpdf_digitaldo($id)
    {
        $policy = Policy::find($id);
        $doc = substr("$policy->client_ci_contractor",0,1);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
        $data = ['policy' => $policy,
                 'foreign_reference' => $foreign_reference,
                 'doc' => $doc
                ];


       // $customPaper = array(0,0,700,1050);

        $pdf = PDF::loadView('admin-modules.Policies.admin-policy-pdf-digital-d', $data)->setPaper('letter', 'portrait');

            $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');


        return $pdf->stream($fileName . '.pdf');
    }

    public function admin_vexportpdf()
    {
        $today = Carbon::now();
        $today = $today->format('Y-m-d');
        $policies = Policy::where('expiring_date', '<', $today)
        ->orderBy('user_id', 'asc')
        ->get();
        $pdf = PDF::loadView('admin-modules.Policies.admin-vencida-pdf', compact('policies'));

        return $pdf->stream('polizas-vencidas.pdf');
    }


    public function renew($id)
    {
        $policy          = Policy::findOrFail($id);
        $user1 = Auth::user();
        $user            = User::all();
        $vehicles        = Vehicle::distinct()->orderBy('brand', 'asc')->get('brand');
        $prices          = Price::all();
        $estados         = Estado::all();
        $vehicle_classes = VehicleClass::all();
        $vehicle_type = VehicleType::distinct()->orderBy('type','asc')->get('type');

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

        return  view('user-modules.Policies.policies-renew', compact('policy', 'id', 'vehicle_type', 'vehicles', 'prices', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'vehicle_classes', 'user1'));
    }

    public function renew_update(Request $request, $id)
    {
        $today = Carbon::now();
        
         $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;
        $this->validate(
            $request,
            [

                'client_name' => ['required', 'max:255', 'min:1'],
                'client_lastname' => ['required', 'max:255', 'min:1'],
                'client_ci' => ['required', 'max:10', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
                'client_name_contractor' => ['required', 'max:255', 'min:1'],
                'client_lastname_contractor' => ['required', 'max:255', 'min:1'],
                'client_ci_contractor' => ['required', 'max:10', 'min:1', 'regex:/[^A-Za-z-\s]+$/'],
                'fecha_n' => ['required','date_format:Y-m-d','before:2006-12-31'],
                'estadocivil' => ['required'],
                'genero' => ['required'],
                'estado' => ['required'],
                'municipio' => ['required'],
                'parroquia' => ['required'],
                'vehicle_color' => ['required', 'max:25', 'min:1', 'regex:/^[a-zA-Z_ ]+$/'],
              
                'vehicle_motor_serial'  => ['required', 'max:25', 'min:1'],
                'vehicle_registration' => ['required', 'max:15', 'min:1'],
                'price' => ['required'],
                'client_phone'      => ['min:1', 'max:8', 'regex:/[^A-Za-z-\s]+$/']
            ],[

            'fecha_n.before' => 'La fecha debe ser de un mayor de edad'
            ]
        );

        //Get Vehicle id from select option using pluck to get the value in an arry of 1 item
    $vehicle_id = Vehicle::where('brand', $request->vehicleBrand)->where('model', $request->vehicleModel)->pluck('id');

    $fecha_n = $request->input('fecha_n');

    $policy = Policy::findOrFail($id);
    $user           = Auth::user();
    $user_id        = $user->id;
    $type           = $user->type;
    $office           = $user->office;
    $cant = $office->cant - 1;
    $office->cant = $cant;
    $office->save();
    $client_ci      = $request->input('id_type') . $request->input('client_ci');
    $vehicle_weight = $request->input('vehicle_weight') . 'Kg';
     // Datos de identificacion de la poliza
    $policy->user_id            = $user_id;
    $policy->type               = $type;
    //Fecha de vencimiento
    $expiring_date = Carbon::now()->addYear();
    $policy->expiring_date = $expiring_date;
    // Datos del vehiculo
    $policy->vehicle_id                 = 0;
    $policy->vehicle_class_id           = $request->input('vehicle_class');
    $policy->vehicle_type               = strtoupper($request->input('vehicle_type'));
  
    $policy->vehicle_color              = ucwords($request->input('vehicle_color'));
    
    $policy->vehicle_motor_serial       = strtoupper($request->input('vehicle_motor_serial'));
    $policy->vehicle_registration       = strtoupper($request->input('vehicle_registration'));
    // Datos del cliente
    $client_names = strtolower($request->input('client_name'));
    $client_lastname = strtolower($request->input('client_lastname'));
    $policy->client_name          = ucwords($client_names);
    $policy->client_lastname      = ucwords($client_lastname);
    $policy->client_email         = strtoupper($request->input('client_email'));
    $policy->client_phone         = $request->input('sp_prefix') . $request->input('client_phone');
    $policy->vehicle_certificate_number = strtoupper($request->input('vehicle_certificate_number'));
    $policy->client_ci            = strtoupper($client_ci);
    $policy->id_estado            = $request->input('estado');
    $policy->id_municipio         = $request->input('municipio');
    $policy->id_parroquia         = $request->input('parroquia');
    $policy->client_address       = $request->input('client_address');
    // Datos del beneficiario
    $client_names_contractor    = strtolower($request->input('client_name_contractor'));
    $client_lastname_contractor = strtolower($request->input('client_lastname_contractor'));
    $client_ci_contractor       = $request->input('id_type_contractor') . $request->input('client_ci_contractor');
    $policy->client_name_contractor     = ucwords($client_names_contractor);
    $policy->client_lastname_contractor = ucwords($client_lastname_contractor);
    $policy->client_ci_contractor       = strtoupper($client_ci_contractor);
    $policy->fecha_n              = $fecha_n;
    $policy->estadocivil          = $request->input('estadocivil');
    $policy->genero               = $request->input('genero');

    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = 'Recaudo_' . $request->input('client_ci') . '.jpg';
        $image->move(public_path('uploads'), $imageName);

        // Eliminar la imagen antigua si existe
        if ($policy->image_tp && file_exists(public_path('uploads/' . $policy->image_tp))) {
            unlink(public_path('uploads/' . $policy->image_tp));
        }

        // Actualizar el campo en la base de datos
        $policy->image_tp = $imageName;
    }

    // Datos del precio al momento de crear la poliza
    $price_info = Price::where('id', $request->input('price'))->first();
    $policy->price_id = $request->input('price');
    $policy->total_premium = $price_info->total_premium;
    $policy->total_all = $price_info->total_all;
    $policy->damage_things = NULL;
    $policy->foreign = $euro;
    $policy->status = 0;
    $policy->report = 0;
    $policy->renew = $policy->renew + 1;
    $policy->created_at = $today;
    $policy->update();

   

    return redirect('/user/index-policies')->with('success', 'Póliza Renovada correctamente');
    }
    
        public function price_adminselect(Request $request)
    {
          $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

            if ($request->ajax()) {
                $data = Price::where('class_id',  $request->priceData)
                ->orderBy('description','asc')
                ->get();
                $output = '';
                $output = '<option value=""> - Seleccionar - </option>';

                foreach ($data as $row) {
                    $output .= '<option value="' . $row->id . '">'
                    . $row->description . ' - '
                    . $row->total_premium . '€' . ' - '
                    . number_format((($row->total_premium * $euro)/$dolar),2) . '$</option>';
                }

                return $output;

            }
    }




   public function price_select(Request $request)
    {
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

            if ($request->ajax()) {
                $data = Price::where('class_id',  $request->priceData)
                ->orderBy('description','asc')
                ->get();
                $output = '';
                $output = '<option value=""> - Seleccionar - </option>';

                foreach ($data as $row) {
                    $output .= '<option value="' . $row->id . '">'
                    . $row->description . ' - '
                    . $row->total_premium . '€' . ' - '
                    . number_format((($row->total_premium * $euro)/$dolar),2) . '$</option>';
                }

                return $output;

            }


        }

        public function price_view(Request $request)
    {
        if ($request->ajax()) {
            $data = Price::where('id', $request->priceId)->get();
            $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
            $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

            // Inicializa las variables de totales fuera del bucle
            $total_all = 0;
            $total_premium = 0;

             $price_info = '<table class="table table-striped">' .
                          '<thead>' .
                          '<tr>' .
                          '<th>Descripcion</th>' .
                          '<th>Cobertura</th>' .
                          '<th>Prima</th>' .
                          '</tr>' .
                          '</thead>' .
                          '<tbody>';

            foreach ($data as $row) {
                // Agrega los campos principales
                $price_info .= '<tr>' .
                               '<td>' . $row->campo . '</td>' .
                               '<td>' . number_format($row->campoc * $euro , 2) . ' Bs</td>' .
                               '<td>' . number_format($row->campop * $euro, 2) . ' Bs</td>' .
                               '</tr>';

                // Suma los totales (si no son nulos)
                $total_all += $row->campoc ?? 0;
                $total_premium += $row->campop ?? 0;

                // Mostrar los campos adicionales si no están vacíos o nulos
                if (!empty($row->campo1)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo1 . '</td>' .
                                   '<td>' . number_format($row->campoc1 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop1 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc1 ?? 0;
                    $total_premium += $row->campop1 ?? 0;
                }

                if (!empty($row->campo2)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo2 . '</td>' .
                                   '<td>' . number_format($row->campoc2 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop2 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc2 ?? 0;
                    $total_premium += $row->campop2 ?? 0;
                }

                if (!empty($row->campo3)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo3 . '</td>' .
                                   '<td>' . number_format($row->campoc3 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop3 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc3 ?? 0;
                    $total_premium += $row->campop3 ?? 0;
                }

                if (!empty($row->campo4)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo4 . '</td>' .
                                   '<td>' . number_format($row->campoc4 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop4 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc4 ?? 0;
                    $total_premium += $row->campop4 ?? 0;
                }

                if (!empty($row->campo5)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo5 . '</td>' .
                                   '<td>' . number_format($row->campoc5 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop5 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc5 ?? 0;
                    $total_premium += $row->campop5 ?? 0;
                }

                if (!empty($row->campo6)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo6 . '</td>' .
                                   '<td>' . number_format($row->campoc6 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop6 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc6 ?? 0;
                    $total_premium += $row->campop6 ?? 0;
                }
                 if (!empty($row->campo7)) {
                    $price_info .= '<tr>' .
                                   '<td>' . $row->campo6 . '</td>' .
                                   '<td>' . number_format($row->campoc7 * $euro, 2) . ' Bs</td>' .
                                   '<td>' . number_format($row->campop7 * $euro, 2) . ' Bs</td>' .
                                   '</tr>';
                    $total_all += $row->campoc7 ?? 0;
                    $total_premium += $row->campop7 ?? 0;
                }
            }

            // Agrega el total al final
            $price_info .= '<tr>' .
                           '<td><strong>Total</strong></td>' .
                           '<td><strong> </strong></td>' .
                           '<td><strong>' . number_format($total_premium * $euro, 2) . ' Bs</strong></td>' .
                           '</tr>';

            $price_info .= '</tbody>' .
                           '</table>';

            return $price_info;
        }
    }









    public function search(Request $request)
    {
        $user = Auth::user();
        $office = $user->office_id;

            if ($request->ajax()) {
                $data = Price::where('class_id',  $request->priceData)
                ->orwhere('office_id', $office)
                ->orderBy('description','asc')
                ->get();
                $output = '';
                $output = '<option value=""> - Seleccionar - </option>';

                foreach ($data as $row) {
                    $output .= '<option value="' . $row->id . '">' . $row->description . ' - '. $row->total_premium . '$</option>';
                }

                return $output;

            }
    }
        public function null_pay(Request $request, $id)
    {

        $policy  = Policy::findOrFail($id);
        $policy->status = 0;
        $policy->update();
        return redirect()->back()->with('success', 'Se ha revocado de pagos correctamente');
    }


        public function anular_user(Request $request, $id)
    {

        $user = Auth::user();
        $policy  = Policy::findOrFail($id);
        $policy->statusu = 1;

        $policy->save();

        return redirect()->back()->with('danger', 'Poliza Reportada');

    }


    public function nanular_admin(Request $request, $id)
    {


        $policy  = Policy::findOrFail($id);
        $policy->statusu = NULL;

        $policy->update();
        return redirect()->back()->with('success', 'Se ha revocado la anulación correctamente');
    }


    //Supervidor

    public function index_mod()
{
    $today = Carbon::now();
    $user = Auth::user();
    $admin_id = $user->id;
    $type = $user->type;
    $policies = Policy::orderBy('created_at', 'desc')
    ->whereIn('user_id', function($query) use ($admin_id) {
       $query->select('id')
             ->from('users')
             ->where('admin_id', $admin_id);
    })
    ->paginate(7);
    $counter = 0;

    return view('mod-modules.Policies.mod-policies-index', compact('policies', 'counter', 'today', 'type'));
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
        $doc = substr("$policy->client_ci_contractor",0,1);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
        $data = ['policy' => $policy,
                 'foreign_reference' => $foreign_reference,
                 'doc' => $doc
                ];


       // $customPaper = array(0,0,700,1050);

        $pdf = PDF::loadView('admin-modules.Policies.admin-policy-pdf-digital', $data)->setPaper('letter', 'portrait');



            $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');


        return $pdf->stream($fileName . '.pdf');
    }

    //COTIZACION DE POLIZAS

    public function indexs()
    {

        $user_id = Auth::id(); // Más eficiente que Auth::user()->id
        $status = Auth::user()->status;
        $today = Carbon::now();
        $today = $today->format('Y-m-d');
        $vehicle_classes = VehicleClass::orderBy('class', 'asc')->get();
        $policies = Policy2::where('user_id', '=', $user_id)
        ->orderBy('created_at', 'desc')
        ->paginate(10);
        $counter = 1;


        return view('user-modules.Policies.policies-solicitud', compact('user_id','vehicle_classes', 'policies', 'counter', 'today','status'));
    }

    public function show($id)
    {
        $policy = Policy2::findOrFail($id);
        $today = Carbon::now();
        $vehicle_classes = VehicleClass::orderBy('class', 'asc')->get();
        $expiring_date = Carbon::parse($policy->expiring_date);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
        $banks = Bank::all();
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;


        return view('user-modules.Policies.policy-show', compact('policy','vehicle_classes' , 'today', 'expiring_date','foreign_reference','banks', 'dolar'));
    }

     public function edit($id)
    {
        $policy          = Policy::findOrFail($id);
        $user1 = Auth::user();
        $user            = User::all();
        $vehicles        = Vehicle::distinct()->orderBy('brand', 'asc')->get('brand');
        $estados         = Estado::all();
        $vehicle_type = VehicleType::distinct()->orderBy('type','asc')->get('type');

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

        return  view('user-modules.Policies.policies-edit', compact('policy', 'id', 'vehicle_type', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'user1'));
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

        return redirect('/user/index-policies')->with('success', 'Poliza Actualizada correctamente');

    }

    public function edit_solicitud($id)
    {
        $policy          = Policy2::findOrFail($id);
        $user1 = Auth::user();
        $user            = User::all();
        $vehicles        = Vehicle::distinct()->orderBy('brand', 'asc')->get('brand');
        $prices          = Price::all();
        $estados         = Estado::all();
        $vehicle_classes = VehicleClass::distinct()->orderBy('class', 'asc')->get();
        $vehicle_type = VehicleType::distinct()->orderBy('type','asc')->get('type');

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

        return  view('user-modules.Policies.solicitud-edit', compact('policy', 'id', 'vehicle_type', 'vehicles', 'prices', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'vehicle_classes', 'user1'));
    }


    public function update_solicitud(Request $request, $id)
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
                // Poliza
                'vehicle_class' => ['required'],
                'price' => ['required'],
                // Imágenes (opcional, dependiendo de si son requeridas o no)
                'image' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
                'image1' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:4096']
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
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;


        $policy  = Policy2::findOrFail($id);

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
        $policy->vehicle_class_id           = $request->input('vehicle_class');
        $policy->vehicle_brand              = strtoupper($request->input('vehicleBrand'));
        $policy->vehicle_model              = strtoupper($request->input('vehicleModel'));
        $policy->vehicle_type               = strtoupper($request->input('vehicle_type'));
        $policy->vehicle_year               = $request->input('vehicle_year');
        $policy->vehicle_color              = strtoupper($request->input('vehicle_color'));
        $policy->used_for                   = strtoupper($request->input('used_for'));
        $policy->trailer                    = $request->input('trailer');
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

         // Datos del precio al momento de crear la poliza
        $price_info = Price::find($request->input('price'));
        $policy->price_id = $request->input('price');

        if ($request->input('trailer') == '1') {
            $ext = $price_info->total_premium * 20 / 100;
            $policy->total_premium = $price_info->total_premium + $ext;
        } elseif ($request->input('price') == '36'|| $request->input('price') == '97') {
            $peso = ceil($request->input('vehicle_weight') / 1000) - 12;
            $policy->total_premium = ($peso * 5) + $price_info->total_premium;
        } else {
            $policy->total_premium = $price_info->total_premium;
        }

        $policy->total_all = $price_info->total_all;
        $policy->foreign = $euro;
        $policy->dolar = $dolar;

        $policy->update();

        return redirect('/user/solicitud-policies')->with('success', 'Solicitud Actualizada correctamente');

    }
    public function downloadImagetp($id)
    {
        $policy = Policy::findOrFail($id);
        $imagePath = public_path("uploads/" . $policy->idp . "/" . $policy->image_tp);

        if (!file_exists($imagePath)) {
            abort(404, "Image not found at path: " . $imagePath);
        }

        return response()->download($imagePath);
    }
    public function downloadImageci($id)
    {
        $policy = Policy::findOrFail($id);
        $imagePath = public_path("uploads/" . $policy->idp . "/" . $policy->image_ci);

        if (!file_exists($imagePath)) {
            abort(404, "Image not found at path: " . $imagePath);
        }

        return response()->download($imagePath);
    }
    
        public function downloadImagetps($id)
    {
        $policy = Policy2::findOrFail($id);
        $imagePath = public_path("uploads/" . $policy->id . "/" . $policy->image_tp);

        if (!file_exists($imagePath)) {
            abort(404, "Image not found at path: " . $imagePath);
        }

        return response()->download($imagePath);
    }
    public function downloadImagecis($id)
    {
        $policy = Policy2::findOrFail($id);
        $imagePath = public_path("uploads/" . $policy->id . "/" . $policy->image_ci);

        if (!file_exists($imagePath)) {
            abort(404, "Image not found at path: " . $imagePath);
        }

        return response()->download($imagePath);
    }


  public function upload_profile(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'image' => ['mimes:jpeg,jpg', 'max:4096']
            ], [
                'image.image' => 'El archivo debe ser una imagen.',
                'image.mimes' => 'El archivo debe estar en formato jpeg o jpg.',
                'image.max' => 'El archivo no debe exceder 4MB.'
            ]
        );
    
        $user = User::findOrFail($id);
    
        // Crear la carpeta si no existe
        $uploadPath = public_path('uploads/fotosperfil/' . $user->id);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'Profile_' . preg_replace('/\D/', '', $user->client_ci) . '.' . $extension;
            $imagePath = $uploadPath . '/' . $imageName;
    
            // Eliminar la imagen anterior si existe
            if (!empty($user->image) && file_exists($uploadPath . '/' . $user->image)) {
                unlink($uploadPath . '/' . $user->image);
            }
    
            // Guardar la nueva imagen
            $image->move($uploadPath, $imageName);
            $user->image = $imageName;
            $user->save(); // Guarda los cambios en la base de datos
        }
    
        return redirect()->back()->with('success', 'Imagen Cargada Correctamente');
    }

    public function download_profile($id)
{
    $user = User::findOrFail($id);

    // Construir la ruta completa al archivo
    $filePath = public_path('uploads/fotosperfil/' . $user->id . '/' . $user->image);

    // Verificar si el archivo existe
    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'El archivo no existe.');
    }

    // Descargar el archivo
    return response()->download($filePath);
}

    
    public function deleteImages()
    {
        // Obtener todas las políticas eliminadas (soft deleted)
        $deletedPolicies = Policy::onlyTrashed()->get();
    
        foreach ($deletedPolicies as $policy) {
            $uploadPath = public_path('uploads/' . $policy->id);
    
            // Eliminar imagen TP si existe
            if ($policy->image_tp && file_exists($uploadPath . '/' . $policy->image_tp)) {
                unlink($uploadPath . '/' . $policy->image_tp);
            }
    
            // Eliminar imagen CI si existe
            if ($policy->image_ci && file_exists($uploadPath . '/' . $policy->image_ci)) {
                unlink($uploadPath . '/' . $policy->image_ci);
            }
    
            // Intentar eliminar el directorio si está vacío
            if (is_dir($uploadPath) && count(scandir($uploadPath)) == 2) { // 2 por . y ..
                rmdir($uploadPath);
            }
        }
    
        return response()->json([
            'message' => 'Imágenes de polizas eliminadas borradas correctamente',
            'deleted_policies_count' => $deletedPolicies->count()
        ]);
    }



}

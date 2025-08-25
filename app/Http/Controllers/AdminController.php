<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;
use App\User;
use App\Admin;
use App\Moderator;
use App\Payment;
use App\Vehicle;
use App\Policy;
use App\Office;
use App\ActivityLog;
use App\ForeignUnit;
use PDF;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentUserType = Auth::user()->type;
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $todayDate = Carbon::now()->toDateString();
        $sevenDaysFromNow = Carbon::now()->addDays(7)->toDateString();


        // *** Obtener todos los datos necesarios en el controlador ***

        // 1. Datos para las tarjetas de resumen (conteos y sumas)
        $policiesSoldAll = Policy::where('type', $currentUserType)->count();
        $policiesSoldMonth = Policy::where('type', $currentUserType)
                                   ->whereMonth('created_at', $currentMonth)
                                   ->whereYear('created_at', $currentYear)
                                   ->count();

        // Asumo que policies_anovalid busca pólizas que vencen *entre hoy y dentro de 7 días*
        $policiesAnovalid = Policy::where('type', $currentUserType)
                                  ->whereDate('expiring_date', '>=', $todayDate)
                                  ->whereDate('expiring_date', '<=', $sevenDaysFromNow)
                                  ->count();

        $policiesHoynovalid = Policy::where('type', $currentUserType)
                                    ->whereDate('expiring_date', $todayDate)
                                    ->count();

        $policiesNovalid = Policy::where('type', $currentUserType)
                                 ->whereDate('expiring_date', '<', $todayDate)
                                 ->count();

        $policiesMoneyMonth = Payment::where('type', $currentUserType)
                                    ->whereMonth('created_at', $currentMonth)
                                   ->whereYear('created_at', $currentYear)
                                   ->sum('total');
        $policiesMoneyMonth = $policiesMoneyMonth ?? 0; // Asegurarse de que es 0 si no hay pagos


        // 2. Datos para el mejor vendedor del mes
        $bestSellerMonthData = Policy::select('user_id', DB::raw('count(*) as policies_count'))
            ->where('user_id', '!=', null)
            ->where('type', $currentUserType)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('user_id')
            ->orderByDesc('policies_count')
            ->first();

        $bestSellerMonthName = 'No hay vendedor del mes';
        if ($bestSellerMonthData && $bestSellerUser = User::select('name', 'lastname')->find($bestSellerMonthData->user_id)) {
             $bestSellerMonthName = $bestSellerUser->name . ' ' . $bestSellerUser->lastname;
        }


        // 3. Datos para el gráfico de pólizas vendidas al mes
        // Obtener los conteos por mes en una sola consulta
        $monthlyPoliciesData = Policy::select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as count'))
                                     ->where('type', $currentUserType) // Asumo que el gráfico es para el tipo de usuario logueado
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


        // 4. Datos de unidades foráneas
        // Obtener solo los dos primeros ForeignUnit, una sola vez
        $foreign = ForeignUnit::limit(2)->get();


        // *** Pasar los datos a la vista ***
        return view('admin', compact( // Asegúrate de que 'admin' es el nombre correcto de tu vista
            'policiesSoldAll',
            'policiesSoldMonth',
            'policiesAnovalid',
            'policiesHoynovalid',
            'policiesNovalid',
            'policiesMoneyMonth',
            'bestSellerMonthName',
            'policiesMonthJson', // Datos del gráfico
            'foreign' // Unidades foráneas
        ));
    }
    public function index_users()
    {
        $type = Auth::user()->type;
        $startDate = '2025-05-15 23:59:59';       
        $users = User::where('type', $type) 
                 ->where('created_at', '<=', $startDate) 
                 ->get();
    

        return view('admin-modules.Users.admin-users-index', compact('users'));
    }

        public function index_users_m()
    {
        $type = Auth::user()->type;
        $startDate = '2025-05-16 00:00:00';       
        $users = User::where('type', $type) 
                 ->where('created_at', '>=', $startDate) 
                 ->get();
    

        return view('admin-modules.Users.admin-users-index-m', compact('users'));
    }



    public function index_mod(){
        $type = Auth::user()->type;
        if (is_null($type)) {
            $users = Moderator::all();
        } elseif ($type == 4) {
            // Si el tipo es 4, también se incluyen los usuarios con tipos 4, 5 y 6
            $users = Moderator::whereIn('type', [4, 5, 6])->get();
        } else {
            $users = Moderator::where('type', $type)->get();
        }
        return view('admin-modules.Mod.mod-users-index', compact('users'));
    }



        public function index_users_deleted(){
        $type = Auth::user()->type;
          if (is_null($type)) {
            $users = User::onlyTrashed()->get();
        } elseif ($type == 4) {
            // Si el tipo es 4, también se incluyen los usuarios con tipos 4, 5 y 6
            $users = User::onlyTrashed()->whereIn('type', [4, 5, 6])->get();
        } else {
            $users = User::onlyTrashed()->where('type', $type)->get();
        }

        return view('admin-modules.Users.admin-users-deleted', compact('users'));
    }

    public function show_user($id)
    {
        $user = User::findOrFail($id);

        $vehicle_types =  Policy::select('vehicle_type')->where('user_id', $id)->get();
        $carros = [];
        $motos = [];

        foreach ($vehicle_types as $row) {
            if ($row->vehicle_type == FALSE) {
                array_push($carros, $row);
            }
            else {
                array_push($motos, $row);
            }
        }
        $count_motorcycles = count($motos);
        $count_cars = count($carros);

        return view('admin-modules.Users.admin-user-show', compact('user', 'count_motorcycles', 'count_cars'));
    }

     public function pdf_user($id)
    {
        $user = User::find($id);
        $data = ['user' => $user
                ];

        // $customPaper = array(0,0,700,1050);
        $pdf = PDF::loadView('admin-modules.Users.admin-user-export', $data)->setPaper('letter','portrait');

        $fileName = 'Acta de Compromiso '.$user->name.' '.$user->lastname;

        return $pdf->stream($fileName . '.pdf');
    }

    public function pdf_user_contrator($id)
    {
        $user = User::find($id);
        $data = ['user' => $user
                ];

        // $customPaper = array(0,0,700,1050);
        $pdf = PDF::loadView('admin-modules.Users.admin-user-contrac', $data)->setPaper('legal','portrait');
        $fileName = 'Honoraios Profesionales'.$user->name.' '.$user->lastname;
        return $pdf->stream($fileName . '.pdf');
    }

    public function pdf_user_author($id)
    {
        $user = User::find($id);
        $data = ['user' => $user
                ];

        // $customPaper = array(0,0,700,1050);
        $pdf = PDF::loadView('admin-modules.Users.admin-user-author', $data)->setPaper('letter','portrait');

        $fileName = 'Acta de Compromiso '.$user->name.' '.$user->lastname;

        return $pdf->stream($fileName . '.pdf');
    }

      public function index_users_pdf(){

         $type = Auth::user()->type;

         $users = User::where('type', $type)->get();
         $data = ['users' => $users
                ];
        $pdf = PDF::loadView('admin-modules.Users.admin-users-index-pdf', $data)->setPaper('letter','portrait');
        return $pdf->stream('Usuarios.pdf');

    }

    public function edit($id)
    {
                $type = Auth::user()->type;

            $supervisor = Moderator::where('type', $type)->get();
     
        $user    = User::findOrFail($id);
        $admin = Admin::where('type','>', 3)->get();

        $cedula = $user->ci;
        $id_type = substr($cedula, 0, 2);

        $identification = preg_split('/[A-Z].*?-/', $cedula);
        array_push($identification, $id_type);

        $phone = $user->phone_number;
        $phone_number = preg_split('/-/', $phone);

        $offices = Office::all();
        return  view('admin-modules.Users.admin-user-edit', compact('user', 'id', 'offices', 'identification', 'phone_number', 'admin','supervisor'));
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z_ ]+$/'],
            'username' => ['required', 'string', 'min:2', 'max:255', 'regex:/^\S*$/u'],
            'lastname' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z_ ]+$/'],
            'ci' => ['required', 'max:10', 'min:7', 'regex:/[^A-Za-z-\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'office_id' =>['required'],
            'profit_percentage' => ['required', 'numeric'],
            'id_type' =>['required']
        ], [
            'name.required' => 'Este campo no puede estar vacio',
            'name.min'      => 'El nombre debe tener al menos 2 letras',
            'name.max'      => 'El nombre no puede ser mas largo que 255 caracteres',
            'name.string'   => 'El nombre no debe contener caracteres especiales',
            'name.regex'    => 'El nombre no puede contener numeros',
            'lastname.required' => 'Este campo no puede estar vacio',
            'lastname.string'   => 'El apellido no puede contener caracteres especiales',
            'lastname.regex'    => 'El apellido no puede contener numeros',
            'lastname.max'      => 'El apellido no puede mas largo que 255 caracteres',
            'lastname.min'      => 'El apellido debe tener al menos 2 letras',
            'ci.required'       => 'Este campo no puede estar vacio',
            'ci.regex'          => 'Este campo no puede contener letras o espacios',
            'ci.min'            => 'La cedula debe contener al menos 2 numeros',
            'ci.max'            => 'La cedula no debe tener mas de 10 numeros',
            'ci.unique'         => 'Esta cedula ya esta en uso',
            'email.required'=> 'Este campo no puede estar vacio',
            'email.email'   => 'Correo electronico invalido',
            'email.max'     => 'El correo electronico no puede ser mas largo que 255 caracteres',
            'office_id.required'=> 'Este campo no puede estar vacio',
            'username.required' => 'Este campo no puede estar vacio',
            'username.min'      => 'El nombre de usuario no debe tener al menos 2 letras',
            'username.max'      => 'El nombre de usuario no puede ser mas largo que 255 caracteres',
            'username.regex'    => 'El nombre de usuario no puede contener espacios',
            'profit_percentage.required' => 'Este campo no puede estar vacio',
            'profit_percentage.numeric'  => 'Este campo solo puede contener numeros'
        ]);
        $user_ci = $request->input('id_type').$request->input('ci');

        $user                    = User::find($id);

         $user->mod_id           = $request->input('superv');
        $user->name              = $request->input('name');
        $user->lastname          = $request->input('lastname');
        $user->username          = $request->input('username');
        $user->ci                = $user_ci;
        $user->email             = $request->input('email');
        $user->office_id         = $request->input('office_id');
        $user->profit_percentage = $request->input('profit_percentage');
        $user->ncontra           = $request->input('ncontra');
        $user->save();

       return redirect('/admin/index-users')->with('success', 'Actualizado Correctamente');

    }
    public function admin_cant(Request $request, $id){
        $user = User::findOrFail($id);
        $numeroct  = $request->input('numeroc1');
        $user->ncontra = $user->ncontra + $numeroct;
        $user->save();
        return redirect()->back()->with('success', 'Contratos agregados');
    }

    public function edit_password($id)
    {
        return view('admin-modules.Users.admin-user-password', compact('id'));
    }

    public function update_password(Request $request, $id)
    {
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/admin/index-users')->with('success', 'Contraseña actualizada correctamente');
    }

    public function admin_update_password(Request $request, $id){
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Admin::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/admin')->with('success', 'Contraseña actualizada correctamente');
    }

    public function destroy($id)
    {
        $users = User::findOrFail($id);
        $users->delete();
        return redirect('/admin/index-users');
    }

        public function destroy_mod($id)
    {
        $users = Moderator::findOrFail($id);
        $users->delete();
        return redirect('/admin/index-mod')->with('success', 'Eliminado correctamente');
    }

        public function restore($id)
    {
        $users = User::withTrashed()->find($id)->restore();

        return redirect('/admin/index-users');
    }

    public function lock($id)
    {
        $users = User::findOrFail($id);
        $users->status = 1;
        $users->update();
        return redirect('/admin/index-users')->with('warning', 'Bloqueado correctamente');

    }

    public function unlock($id)
    {
        $users = User::findOrFail($id);
        $users->status = 0;
        $users->update();
        return redirect('/admin/index-users')->with('success', 'Desbloqueado correctamente');

    }


        public function sms($id){
        $user = User::findOrFail($id);
        if(!$user->sms){

        $user->sms = 1;
        $user->update();
        return redirect('/admin/index-users')->with('success', 'Notificacion enviada');
        }else{
            $user->sms = NULL;
            $user->update();
            return redirect('/admin/index-users')->with('success', 'Notificacion eliminada');
        }


    }

    // Registros de actividad admin

    public function admin_activity_log(){
        $id = Auth::user()->id;
        $activities = ActivityLog::where('causer_id', $id)->orderBy('created_at', 'desc')->paginate(100);

        return view('admin-modules.Activity.admin-activitylog-index', compact('activities'));
    }

    public function admin_activity_log_all(){
        $type = Auth::user()->type;
        $activities1 = ActivityLog::where('causer_id', 'like', '%9995%')->orderBy('created_at', 'desc')->paginate(100);
        $activities2 = ActivityLog::where('causer_id', 'not like', '%9995%')->orderBy('created_at', 'desc')->paginate(100);

        return view('admin-modules.Activity.admin-activitylog-index-all', compact('activities1', 'activities2'));
    }

    public function admin_activity_log_user($id){
        $activities = ActivityLog::select()->where('causer_id', $id)->orderBy('created_at', 'desc')->paginate(100);
        return view('admin-modules.Activity.admin-activitylog-index-user', compact('activities'));
    }

    public function admin_activity_log_admin($id){
        $activities = ActivityLog::select()->where('causer_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin-modules.Activity.admin-activitylog-index-admin', compact('activities'));
    }


    // CRUD ADMINS

    public function index_users_admins(){
        $type = Auth::user()->type;
        $users = Admin::where('type', $type)->get();
        return view('admin-modules.Admins.admin-adminusers-index', compact('users'));
    }

    public function show_admin($id){
        $user = Admin::findOrFail($id);
        return view('admin-modules.Admins.admin-adminuser-show', compact('user'));
    }

    public function edit_admin($id){
        $user = Admin::findOrFail($id);

        $cedula = $user->ci;
        $id_type = substr($cedula, 0, 2);

        $identification = preg_split('/[A-Z].*?-/', $cedula);
        array_push($identification, $id_type);

        $phone = $user->phone_number;
        $phone_number = preg_split('/-/', $phone);

        return  view('admin-modules.Admins.admin-adminuser-edit', compact('user', 'id', 'identification', 'phone_number'));
    }

    public function update_admin(Request $request, $id){
        $this->validate($request, [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'ci' => ['required', 'max:10', 'min:7', 'regex:/[^A-Za-z-\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'id_type' =>['required']
        ]);

        $user_ci = $request->input('id_type').$request->input('ci');

        $user                    = Admin::find($id);
        $user->name              = $request->input('name');
        $user->ci                = $user_ci;
        $user->email             = $request->input('email');
        $user->save();

        return redirect('/admin/index-admins');
    }

    public function destroy_admin($id){
        $users = Admin::findOrFail($id);
        $users->delete();
        return redirect('/admin/index-admins');
    }

    //Supervisor


   public function edit_mod($id){
        $user = Moderator::findOrFail($id);
        $moderatorId = $user->id;
        $admins = Admin::all();

        $cedula = $user->ci;
        $id_type = substr($cedula, 0, 2);

        $identification = preg_split('/[A-Z].*?-/', $cedula);
        array_push($identification, $id_type);

        $phone = $user->phone_number;
        $phone_number = preg_split('/-/', $phone);

        $type = Auth::user()->type;
        if (is_null($type)) {
            $users = User::all();
        } elseif ($type == 4) {
            // Si el tipo es 4, también se incluyen los usuarios con tipos 4, 5 y 6
            $users = User::whereIn('type', [4, 5, 6])->where('mod_id', NULL)->orwhere('mod_id', $moderatorId)->get();
        } else {
            $users = User::orderby('mod_id', 'desc')->where('type', $type)
            ->where(function($query) use ($moderatorId) {
                $query->whereNull('mod_id')
                      ->orWhere('mod_id', $moderatorId);
            })->get();
        }

        $selectedUserIds = $users->where('mod_id', $moderatorId)->pluck('id')->toArray();

        return  view('admin-modules.Mod.mod-user-edit', compact('user', 'id', 'identification', 'phone_number','users','selectedUserIds','admins'));
    }


    public function update_mod(Request $request, $id){
        $this->validate($request, [
            
            'names' => ['required', 'string', 'max:255', 'min:2'],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'ci' => ['required', 'max:10', 'min:7', 'regex:/[^A-Za-z-\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'profi_percentaje' => ['required', 'numeric']
          
        ]);

        $user_ci = $request->input('id_type').$request->input('ci');

        $mod = Moderator::find($id);
        $mod->names = $request->input('names');
        $mod->name = $request->input('name');
        $mod->profi_percentaje = $request->input('profi_percentaje');
        $mod->ci = $user_ci;
        $mod->email = $request->input('email');
        $mod->profi_percentaje = $request->input('profi_percentaje');
        $mod->save();

        // Obtener los IDs de los usuarios seleccionados
        $selectedUsers = $request->input('selected_users', []);

        // Actualizar el campo 'mod_id' de los usuarios seleccionados
        User::whereIn('id', $selectedUsers)->update(['mod_id' => $mod->id]);

        // Establecer mod_id en null para los usuarios no seleccionados previamente asignados a este moderador
        User::where('mod_id', $mod->id)->whereNotIn('id', $selectedUsers)->update(['mod_id' => null]);

        return redirect('/admin/index-mod')->with('success', 'Supervisor editado correctamente');
    }

      public function updatemod_password(Request $request, $id)
    {
        $this->validate($request, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Moderator::findOrFail($id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect('/admin/index-mod')->with('success', 'Contraseña actualizada correctamente');
    }
    
     public function lock_user_region()
    {
        // Obtener las oficinas con id_estado = 2
        $offices = Office::where('id_estado', 2)->get();

        // Extraer los IDs de las oficinas
        $officeIds = $offices->pluck('id');

        // Actualizar el status de los usuarios que pertenecen a esas oficinas
        User::whereIn('office_id', $officeIds)->update(['status' => 0]);

        return redirect('/admin/index-users')->with('warning', 'Bloqueados correctamente');

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
}


<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Office;
use App\Models\Bank;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    /**
     * Lista de usuarios moderados
     */
    public function index()
    {
        $users = Auth::user()->usuarios_moderados;
        return view('mod-modules.Users.index', compact('users'));
    }

    /**
     * Formualario para crear un usuario
     */
    public function create()
    {
        $type = Auth::user()->type;
        //$users = Moderator::where('type', $type)->get(); /// ??????
        $offices = Office::where('type', $type)->get();
        $banks = Bank::all();

        return view('mod-modules.Users.create', compact('offices', 'banks'));
    }

    /**
     * Función que crea al usuario
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();// TODO quizas sea mejor pasar esto a un request
        // Crear el usuario
        event(new Registered($user = $this->crear_usuario($request->all())));
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

        return redirect(route('moderador.users.index'))->with('success', 'Usuario registrado correctamente');
    }

    /**
     * Agrega un número de contratos extra a un usuario determinado
     */
    public function agregar_contratos(Request $request, User $user)
    {
        $numeroct  = $request->input('numeroc1');
        $user->ncontra = $user->ncontra + $numeroct;
        $user->update();
        return redirect()->back()->with('success', 'Contratos agregados');
    }

    /**
     * Asigna un número de contratos determinado a un usuario determinador
     */
    public function editar_numero_contratos(Request $request, User $user)
    {
        $numeroct  = $request->input('numeroc1');
        $user->ncontra = $numeroct;
        $user->update();
        return redirect()->back()->with('success', 'Editado Correctamente');
    }

    public function pdf(User $user)
    {
        $data = [
            'user' => $user
        ];
        $pdf = PDF::loadView('admin-modules.Users.admin-user-export', $data)->setPaper('letter', 'portrait');
        $fileName = 'Acta de Compromiso ' . $user->name . ' ' . $user->lastname;
        return $pdf->stream($fileName . '.pdf');
    }

    /**
     * Usada para crear un usuario
     */
    protected function crear_usuario(array $data)
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

    /**
     * Validador para usuarios
     */
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
}

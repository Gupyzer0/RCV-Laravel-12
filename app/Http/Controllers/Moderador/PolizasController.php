<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Policy;
use App\Models\ForeignUnit;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Estado;
use App\Models\VehicleType;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PolizasController extends Controller
{
    /**
     * Index de polizas para el moderador con busqueda
     */
    public function index(Request $request)
    {
        $request->validate([
            'filtro' => 'sometimes|nullable|max:255',
        ]);

        $policies = Policy::whereIn('user_id', Auth::user()->usuarios_moderados->pluck('id')) // solo polizas de los supervisados
            ->FiltrarNPoliza($request->filtro_poliza)
            ->FiltrarCedulaCliente($request->filtro_cedula)
            ->FiltrarPlaca($request->filtro_placa)
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        return view('mod-modules.Policies.index', [
            'policies' => $policies,
            'today' => Carbon::now(),
            'type' => Auth::user()->type
        ]);
    }

    /**
     * Muestra la poliza
     */
    public function show(Policy $policy)
    {
        return view('mod-modules.Policies.show',[
            'policy' => $policy,
            'today' => Carbon::now(),
            'foreign_reference' => ForeignUnit::first()->pluck('foreign_reference')[0]
        ]);
    }

    /**
     * Reporte PDF
     */
    public function pdf(Policy $policy) 
    {
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

        $pdf = PDF::loadView('policy-pdf-digital', $data)->setPaper('letter', 'portrait');
        $fileName = $policy->id . $policy->client_name . \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y');
        return $pdf->stream($fileName . '.pdf');
    }

    /**
     * Muestra formulario de editar poliza.
     * TODO: Implementar seguridad, solo entrar en urls de polizas pertinentes
     */
    public function edit(Policy $policy)
    {
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

        return  view('mod-modules.Policies.edit', compact('policy', 'vehicle_type', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'user1'));
    }

    /**
     * Actualizar Poliza
     * TODO: Implementar seguridad, solo entrar en urls de polizas pertinentes
     */
    public function update(Request $request, Policy $policy)
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

        //$policy  = Policy::findOrFail($id);

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

        return redirect(route('moderador.polizas.show', $policy))->with('success', 'Poliza Actualizada correctamente');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Estado;
use App\Models\VehicleType;
use App\Models\Policy;
use App\Models\ForeignUnit;

/**
 * Nuevo controlador para un manejo "general" de las polizas de usuario
 */
class PolizasController extends Controller
{
    /**
     * Index de polizas
     */
    public function index(Request $request)
    {
        // Filtrando posibles vendedores a mostrar para los filtros, por logica solo los administradores y
        // supervisores requieren de esto.
        $vendedores = User::filtrarPorUsuarioAutenticado()->get();
        
        $polizas = Policy::orderByDesc('created_at')//whereIn('user_id', Auth::user()->usuarios_moderados->pluck('id')) // solo polizas de los supervisados
            ->FiltrarNPoliza($request->filtro_poliza)
            ->FiltrarCedulaCliente($request->filtro_cedula)
            ->FiltrarPlaca($request->filtro_placa)
            ->FiltrarEstatus($request->filtro_estatus)
            ->FiltrarVendedor($request->filtro_vendedor)
            ->paginate(7);

        return view('Polizas.index', [
            'polizas' => $polizas,
            'today' => Carbon::now(),
            'vendedores' => $vendedores ?? [],
            'filtro_poliza' => $request->filtro_poliza,
            'filtro_cedula' => $request->filtro_cedula,
            'filtro_placa' => $request->filtro_placa,
            'filtro_estatus' => $request->filtro_estatus,
            'filtro_vendedor' => $request->filtro_vendedor,
        ]);
    }

    /**
     * Muestra la poliza
     */
    public function show(Policy $poliza)
    {
        return view('Polizas.show',[
            'poliza' => $poliza,
            'today' => Carbon::now(),
            'foreign_reference' => ForeignUnit::first()->pluck('foreign_reference')[0]
        ]);
    }

    /**
     * Vista para editar la poliza
     */
    public function edit(Policy $poliza)
    {
        $user1 = Auth::user();
        $user            = User::all();
        $vehicles        = Vehicle::distinct()->orderBy('brand', 'asc')->get('brand');
        $estados         = Estado::all();
        $vehicle_type = VehicleType::distinct()->orderBy('type', 'asc')->get('type');

        // $cant = $user->cantidadp;
        $cedula = $poliza->client_ci;
        $cedula_contractor = $poliza->client_ci_contractor;
        $id_type = substr($cedula, 0, 2);
        $id_type_contractor = substr($cedula_contractor, 0, 2);

        $identification = preg_split('/[A-Z].*?-/', $cedula);
        array_push($identification, $id_type);
        $identification_contractor = preg_split('/[A-Z].*?-/', $cedula_contractor);
        array_push($identification_contractor, $id_type_contractor);

        $kilos = $poliza->vehicle_weight;
        $weight_num = preg_split('/[A-Z].*/', $kilos);

        $phone = $poliza->client_phone;
        $client_phone = preg_split('/-/', $phone);

        return  view('Polizas.edit', compact('poliza', 'vehicle_type', 'identification', 'identification_contractor', 'weight_num', 'client_phone', 'estados', 'user1'));
    }

    /**
     * Actualiza la poliza
     */
    public function update(Request $request, Policy $poliza)
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

        //$poliza  = Policy::findOrFail($id);

        // Datos del Asegurado
        $poliza->client_name_contractor = ucwords(strtolower($request->input('client_name_contractor')));
        $poliza->client_lastname_contractor = ucwords(strtolower($request->input('client_lastname_contractor')));
        $poliza->client_ci_contractor       = $request->input('id_type_contractor') . $request->input('client_ci_contractor');

        // Datos del Tomador
        $poliza->client_ci            = $request->input('id_type') . $request->input('client_ci');
        $poliza->client_name          = ucwords(strtolower($request->input('client_name')));
        $poliza->client_lastname      = ucwords(strtolower($request->input('client_lastname')));
        $poliza->client_email         = $request->input('client_email');
        $poliza->fecha_n              = $request->input('fecha_n');
        $poliza->estadocivil          = $request->input('estadocivil');
        $poliza->genero               = $request->input('genero');
        $poliza->client_phone         = $request->input('sp_prefix') . $request->input('client_phone');
        $poliza->id_estado            = $request->input('estado');
        $poliza->id_municipio         = $request->input('municipio');
        $poliza->id_parroquia         = $request->input('parroquia');
        $poliza->client_address       = $request->input('client_address');


        // Datos del vehiculo

        $poliza->vehicle_brand              = strtoupper($request->input('vehicleBrand'));
        $poliza->vehicle_model              = strtoupper($request->input('vehicleModel'));
        $poliza->vehicle_type               = strtoupper($request->input('vehicle_type'));
        $poliza->vehicle_year               = $request->input('vehicle_year');
        $poliza->vehicle_color              = strtoupper($request->input('vehicle_color'));
        $poliza->used_for                   = strtoupper($request->input('used_for'));
        $poliza->vehicle_bodywork_serial    = strtoupper($request->input('vehicle_bodywork_serial'));
        $poliza->vehicle_motor_serial       = strtoupper($request->input('vehicle_motor_serial'));
        $poliza->vehicle_certificate_number = strtoupper($request->input('vehicle_certificate_number'));
        $poliza->vehicle_weight             = $request->input('vehicle_weight') . 'Kg';
        $poliza->vehicle_registration       = strtoupper($request->input('vehicle_registration'));

        // Crear o verificar la carpeta de subida
        $uploadPath = public_path('uploads/' . $poliza->id);
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Procesar la imagen 'image' (Título de Propiedad)
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = 'TP_' . preg_replace('/\D/', '', $poliza->client_ci) . '.' . $extension;

            // Eliminar la imagen anterior si existe
            if ($poliza->image_tp && file_exists($uploadPath . '/' . $poliza->image_tp)) {
                unlink($uploadPath . '/' . $poliza->image_tp);
            }

            // Mover la nueva imagen a la carpeta de subida
            $image->move($uploadPath, $imageName);
            $poliza->image_tp = $imageName;
        }

        // Procesar la imagen 'image1' (Cédula o RIF)
        if ($request->hasFile('image1')) {
            $image1 = $request->file('image1');
            $extension1 = $image1->getClientOriginalExtension();
            $imageName1 = 'CI_' . preg_replace('/\D/', '', $poliza->client_ci) . '.' . $extension1;

            // Eliminar la imagen anterior si existe
            if ($poliza->image_ci && file_exists($uploadPath . '/' . $poliza->image_ci)) {
                unlink($uploadPath . '/' . $poliza->image_ci);
            }

            // Mover la nueva imagen a la carpeta de subida
            $image1->move($uploadPath, $imageName1);
            $poliza->image_ci = $imageName1;
        }


        $poliza->update();

        return redirect(route('polizas.show', $poliza))->with('success', 'Poliza Actualizada correctamente');
    }

    /**
     * Eliminar la poliza
     */
    public function delete(Policy $poliza)
    {
        $poliza->delete();
        return redirect(route('polizas.index'))->with('success','Poliza eliminada');
    }

    /**
     * Anular Poliza
     */
    public function anular(Policy $poliza)
    {
        $poliza->statusu = 1;
        $poliza->save();
        return redirect()->back()->with('danger', 'Poliza Reportada');
    }

    /**
     * Desanular Poliza
     */
    public function desanular(Policy $poliza)
    {
        $poliza->statusu = NULL;
        $poliza->update();
        return redirect()->back()->with('success', 'Se ha revocado la anulación correctamente');
    }

    /**
     * Reporte PDF
     */
    public function pdf(Policy $poliza)
    {
        $doc = substr("$poliza->client_ci_contractor",0,1);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        $qrCode = base64_encode(
            QrCode::format('png')
                  ->size(150)
                  ->color(0, 0, 0)
                  ->backgroundColor(255, 255, 255, 0)
                  ->generate('https://liderdeseguros.com/v/' . $poliza->id)
        );

        $data = ['poliza' => $poliza,
                 'euro' => $euro,
                 'dolar' => $dolar,
                 'doc' => $doc,
                 'qrCode' => $qrCode
                ];


       // $customPaper = array(0,0,700,1050);

        $pdf = PDF::loadView('Polizas.reportes.pdf', $data)->setPaper('letter', 'portrait');
        $fileName = $poliza->id . $poliza->client_name . \Carbon\Carbon::parse($poliza->created_at)->format('d-m-Y');
        return $pdf->stream($fileName . '.pdf');
    }

    /**
     * Reporte PDF Digital
     */
    public function pdf_digital(Policy $poliza)
    {
        $doc = substr("$poliza->client_ci_contractor",0,1);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        $qrCode = base64_encode(
            QrCode::format('png')
                  ->size(150)
                  ->color(0, 0, 0)
                  ->backgroundColor(255, 255, 255, 0)
                  ->generate('https://liderdeseguros.com/v/' . $poliza->id)
        );

        $data = ['policy' => $poliza,
                 'euro' => $euro,
                 'dolar' => $dolar,
                 'doc' => $doc,
                 'qrCode' => $qrCode
                ];

        $pdf = PDF::loadView('policy-pdf-digital', $data)->setPaper('letter', 'portrait');
        $fileName = $poliza->id . $poliza->client_name . \Carbon\Carbon::parse($poliza->created_at)->format('d-m-Y');

        return $pdf->stream($fileName . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accidents;
use App\Models\Bank;
use App\Models\Policy;
use Auth;

class AccidentsController extends Controller
{
    public function index()
    {
        $type = Auth::user()->type;
        $accidents = Accidents::where('type', $type)
        ->get();
        $counter = 0;

        return view('admin-modules.Accidents.index', compact('accidents', 'counter'));
    }

    public function show($id)
    {
        $siniestro = Accidents::findOrFail($id);
        return view('admin-modules.Accidents.show', compact('siniestro'));
    }

    public function create()
    {
        $banks = Bank::all();
        return view('admin-modules.Accidents.create', compact('banks'));
    }

    public function getPolicyData($policyNumber)
    {
        $policy = Policy::where('id', $policyNumber)->first();

        if ($policy) {
            return response()->json([
                'success' => true,
                'vehicle_brand' => $policy->vehicle_brand,
                'vehicle_model' => $policy->vehicle_model,
                'vehicle_registration' => $policy->vehicle_registration,
                'vehicle_color' => $policy->vehicle_color,
                'vehicle_year' => $policy->vehicle_year,
                'policy_plan' => $policy->price->description,
                'used_for' => $policy->used_for,
                'insured_name' => $policy->client_name.' '.$policy->client_lastname,
                'insured_ci' => $policy->client_ci,

  
                
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Póliza no encontrada.']);
        }
    }


      public function store(Request $request)
    {
       
        // Validación de campos
         $this->validate(
        $request, [
        'policy_number' => 'required|string|max:50|exists:policies,id',
        'accident_date' => 'required|date',
        'accident_time' => 'required|date_format:H:i',
        'accident_type' => 'required|string|max:50',
        'accident_location' => 'required|string|max:255',
        'accident_district' => 'required|string|max:100',
        'accident_description' => 'required|string',
        
        // Terceros (opcionales)
        'third_party_name' => 'nullable|string|max:100',
        'third_party_dni' => 'nullable|string|max:20',
        'third_party_insurance' => 'nullable|string|max:100',
        'third_party_plate' => 'nullable|string|max:20',
        
        // Documentos
       
        // 5MB
        'police_report' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240', // 10MB
        'other_documents' => 'nullable|array',
        'other_documents.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:10240', // 10MB
        
        // Datos de pago
        'bank_id' => 'required|exists:bank,id',
        'account_type' => 'required|in:Ahorros,Corriente',
        'account_number' => 'required|string|max:50'
        ], [
        // Mensajes para policy_number
        'policy_number.required' => 'El número de póliza es obligatorio.',
        'policy_number.string' => 'El número de póliza debe ser texto.',
        'policy_number.max' => 'El número de póliza no debe exceder los 50 caracteres.',
        'policy_number.exists' => 'La póliza ingresada no existe en nuestros registros.',
        
        // Mensajes para accident_date
        'accident_date.required' => 'La fecha del siniestro es obligatoria.',
        'accident_date.date' => 'La fecha del siniestro no tiene un formato válido.',
        
        // Mensajes para accident_time
        'accident_time.required' => 'La hora del siniestro es obligatoria.',
        'accident_time.date_format' => 'La hora del siniestro debe estar en formato HH:MM.',
        
        // Mensajes para accident_type
        'accident_type.required' => 'Debe seleccionar el tipo de siniestro.',
        'accident_type.string' => 'El tipo de siniestro debe ser texto.',
        'accident_type.max' => 'El tipo de siniestro no debe exceder los 50 caracteres.',
        
        // Mensajes para accident_location
        'accident_location.required' => 'La ubicación del siniestro es obligatoria.',
        'accident_location.string' => 'La ubicación debe ser texto.',
        'accident_location.max' => 'La ubicación no debe exceder los 255 caracteres.',
        
        // Mensajes para accident_district
        'accident_district.required' => 'El distrito/estado del siniestro es obligatorio.',
        'accident_district.string' => 'El distrito/estado debe ser texto.',
        'accident_district.max' => 'El distrito/estado no debe exceder los 100 caracteres.',
        
        // Mensajes para accident_description
        'accident_description.required' => 'La descripción del siniestro es obligatoria.',
        'accident_description.string' => 'La descripción debe ser texto.',
        
        // Mensajes para terceros
        'third_party_name.string' => 'El nombre del tercero debe ser texto.',
        'third_party_name.max' => 'El nombre del tercero no debe exceder los 100 caracteres.',
        
        'third_party_dni.string' => 'El C.I./RIF del tercero debe ser texto.',
        'third_party_dni.max' => 'El C.I./RIF del tercero no debe exceder los 20 caracteres.',
        
        'third_party_insurance.string' => 'El nombre de la aseguradora del tercero debe ser texto.',
        'third_party_insurance.max' => 'El nombre de la aseguradora no debe exceder los 100 caracteres.',
        
        'third_party_plate.string' => 'La placa del vehículo del tercero debe ser texto.',
        'third_party_plate.max' => 'La placa del vehículo no debe exceder los 20 caracteres.',
        
        // Mensajes para accident_photos
        'accident_photos.required' => 'Debe subir al menos 3 fotos del siniestro.',
        'accident_photos.array' => 'Las fotos deben enviarse como un conjunto de archivos.',
        'accident_photos.min' => 'Debe subir al menos :min fotos del siniestro.',
        
        'accident_photos.*.image' => 'Cada foto debe ser una imagen válida.',
        'accident_photos.*.mimes' => 'Las fotos deben ser de tipo: jpeg, png, jpg, gif.',
        'accident_photos.*.max' => 'Cada foto no debe pesar más de 5MB.',
        
        // Mensajes para police_report
        'police_report.file' => 'El parte policial debe ser un archivo válido.',
        'police_report.mimes' => 'El parte policial debe ser PDF, DOC, DOCX, JPG o PNG.',
        'police_report.max' => 'El parte policial no debe pesar más de 10MB.',
        
        // Mensajes para other_documents
        'other_documents.array' => 'Los documentos adicionales deben enviarse como un conjunto de archivos.',
        
        'other_documents.*.file' => 'Cada documento adicional debe ser un archivo válido.',
        'other_documents.*.mimes' => 'Los documentos adicionales deben ser PDF, DOC, DOCX, JPG o PNG.',
        'other_documents.*.max' => 'Cada documento adicional no debe pesar más de 10MB.',
        
        // Mensajes para bank_id
        'bank_id.required' => 'Debe seleccionar un banco.',
        'bank_id.exists' => 'El banco seleccionado no es válido.',
        
        // Mensajes para account_type
        'account_type.required' => 'Debe seleccionar el tipo de cuenta.',
        'account_type.in' => 'El tipo de cuenta seleccionado no es válido.',
        
        // Mensajes para account_number
        'account_number.required' => 'El número de cuenta es obligatorio.',
        'account_number.string' => 'El número de cuenta debe ser texto.',
        'account_number.max' => 'El número de cuenta no debe exceder los 50 caracteres.'
        ]);  

        
            // Obtener la póliza relacionada
            $policy = Policy::where('id', $request->policy_number)->firstOrFail();

            // Procesar archivos subidos
             $baseDirectory = 'accidents/policy_' . $policy->id;
            // Procesar archivos subidos con la nueva estructura de directorios
            $photosPaths = $this->uploadFiles($request->file('accident_photos'), $baseDirectory . '/photos');
            $policeReportPath = $request->hasFile('police_report') 
                ? $this->uploadFile($request->file('police_report'), $baseDirectory . '/reports') 
                : null;

            $otherDocsPaths = $request->hasFile('other_documents') 
                ? $this->uploadFiles($request->file('other_documents'), $baseDirectory . '/documents') 
                : [];


            // Crear el registro del siniestro
            $accident = Accidents::create([
                'policy_id' => $policy->id,
                'type' => $policy->type,
                'accident_date' => $request->accident_date,
                'accident_time' => $request->accident_time,
                'accident_type' => $request->accident_type,
                'location' => $request->accident_location,
                'district' => $request->accident_district,
                'description' => $request->accident_description,
                'third_party_name' => $request->third_party_name,
                'third_party_dni' => $request->third_party_dni,
                'third_party_insurance' => $request->third_party_insurance,
                'third_party_plate' => $request->third_party_plate,
                'photos' => json_encode($photosPaths),
                'police_report' => $policeReportPath,
                'other_documents' => json_encode($otherDocsPaths),
                'bank_id' => $request->bank_id,
                'account_type' => $request->account_type,
                'account_number' => $request->account_number,
                'status' => 'En revisión', // Estado inicial
                'registered_by' => auth()->id() // Si tienes autenticación
            ]);

            // Redireccionar con mensaje de éxito
            return redirect()->route('show.siniestros', $accident->id)
                ->with('success', 'Siniestro registrado correctamente. N° de caso: ' . $accident->id);
      

    }

    /**
     * Métodos auxiliares para manejo de archivos
     */
    private function uploadFile($file, $directory)
    {
        // Asegurarse de que el directorio existe
        Storage::disk('public')->makeDirectory('uploads/siniestros/' . $directory);
        
        return $file->store('uploads/siniestros/' . $directory, 'public');
    }
    
    private function uploadFiles($files, $directory)
    {
        $paths = [];
        if ($files) {
            foreach ($files as $file) {
                $paths[] = $this->uploadFile($file, $directory);
            }
        }
        return $paths;
    }

     private function deleteFile($path)
    {
        // Elimina 'uploads/siniestros/' del path si está incluido
        $relativePath = str_replace('uploads/siniestros/', '', $path);
        Storage::disk('public')->delete('uploads/siniestros/' . $relativePath);
    }

    private function deleteFiles($paths)
    {
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Policy;
use App\Vehiculo;
use PDF;
use Carbon\Carbon;
use App\ForeignUnit;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VerifyController extends Controller
{
    public function show_policy($id)
    {
        // Obtener la póliza primero
        $policy = Policy::findOrFail($id);

        // Obtener la fecha actual
        $today = Carbon::now();
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        // Obtener la fecha de vencimiento de la póliza y calcular los días restantes
        $expirationDate = Carbon::parse($policy->expiring_date);
        $days = $today->diffInDays($expirationDate, false);

        // Retornar la vista con los datos necesarios
        return view('verificar', compact('policy', 'today', 'days','dolar','euro'));
    }

    public function showPolicyPdf($id)
    {
        $policy = Policy::find($id);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        // Generar el código QR
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
            'qrCode' => $qrCode,
        ];

        // Generar el PDF
        $pdf = PDF::loadView('policy-pdf-digital', $data)
                  ->setPaper('letter', 'portrait');

        // Generar el nombre del archivo
        $fileName = 'Poliza N°'.$policy->id.'_'.$policy->client_name;

        return $pdf->download($fileName . '.pdf');
    }

    public function downloadConditions()
    {
        // Ruta al archivo en public/uploads
        $filePath = public_path('uploads/Condicionado.pdf'); // Ajusta el nombre si es dinámico

        // Verifica si el archivo existe
        if (!file_exists($filePath)) {
            return back()->with('error', 'El archivo condicionado no está disponible.');
        }

        // Descarga el archivo
        return response()->download($filePath, 'Condicionado_Poliza.pdf');
    }

}


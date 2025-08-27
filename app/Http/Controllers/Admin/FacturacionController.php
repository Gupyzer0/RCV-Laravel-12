<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\FacturacionApiRequest;
use App\Http\Services\TheFactoryHKA\TheFactoryHka;

use App\Models\Policy;

class FacturacionController extends Controller
{
    /**
     * Vista para darle un inicio a este modulo de facturación, luego vamos
     * viendo que se le va agregando
     */
    public function index()
    {
        $polizas_sin_facturar = Policy::where('facturado',false)->orderBy('created_at', 'asc')->get();
        return view('admin-modules.Facturacion.index',[
            'polizas_sin_facturar' => $polizas_sin_facturar,
        ]);
    }
    
    /**
     * Emite las facturas de todas las polizas que no las poseen al momento
     */
    public function emitir_facturas()
    {
        // Usamos el Job de facturacion para que se encargue de este proceso
        FacturacionApiRequest::dispatch();
        session()->flash('message', 'Facturación lista');
        return redirect(route('facturacion.index'));
    }

    /**
     * Descargamos la factura
     */
    public function descargar_factura(Policy $poliza)
    {
        $sistemaFacturacion = new TheFactoryHka();
        return $sistemaFacturacion->descargar_archivo('01',(string) $poliza->idp); // 01 == Factura
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

use App\Models\User;
use App\Models\Policy;
use App\Models\ForeignUnit;

/**
 * Nuevo controlador para reemplazar controlador de pagos . . .
 */
class PagosController extends Controller
{
    /**
     * Muestra los pagos pendientes agrupados por vendedor
     */
    public function pendientes(Request $request)
    {
        $user = Auth::user();
        // Usuarios relacionados al administrador logueado

        $users = User::with('policies')->withCount('policies')
            ->FiltrarPorUsuarioAutenticado()
            ->FiltrarPorModerador($request->filtro_moderador)
            ->whereHas('policies', function ($query) {
                $query->where('status', 0);
            })
            ->orderBy('policies_count')
            ->paginate(10);

        return view('Pagos.pendientes',[
            'users' => $users,
            // rellenar moderadores si el usaurio tiene rol de administrador
            'moderadores' => $user->hasRole('administrador') ? User::role('moderador')->where('type', $user->type)->get() : [],
            'filtro_moderador' => $request->filtro_moderador,
        ]);
    }

    /**
     * Realiza el cierre de los pagos pendientes
     */
    public function cierre_pagos_pendientes()
    {
        Policy::where('report',0)->update(['report' => 1]);
        return redirect()->back()->with('success', 'Se ha realizado el cierre de las polizas correctamente');
    }

    /**
     * Realiza cierre para un vendedor en particular
     */
    public function cierre_por_usuario(User $user)
    {
        Policy::where('user_id', $user->id)->where('report', FALSE)->update(['report' => TRUE]);
        return redirect()->back()->with('success', 'Se ha realizado el cierre correctamente');
    }

    /**
     * Reporte pdf de polizas pendientes por pagar para un usuario
     */
    public function pdf_polizas_pendientes_por_pagar(User $user)
    {
        $polizas = $user->policies->where('status', FALSE)->where('report', TRUE);

        $data = [
            'polizas' => $polizas,
            'user' => $user,
            'euro' => ForeignUnit::first()->pluck('foreign_reference')[0],
            'dolar' => ForeignUnit::skip(1)->first()->foreign_reference,
        ];

        // pdf_polizas_cerradas_pendientes.blade
        $pdf = PDF::loadView('Pagos.pdf_polizas_cerradas_pendientes', $data)->setPaper('letter', 'portrait');
        return $pdf->stream('Correlativo' . '.pdf');
    }

    /**
     * Lista los pagos pendientes para un usuario
     */
    public function pendientes_por_usuario(User $user) 
    {
        $not_paid = $user->policies->where('status', FALSE); // no deberian de ser reportadas?
        return view('Pagos.pendientes-por-usuario', compact('not_paid', 'user'));
    }

    /**
     * Lista de pagos realizados
     */
    public function index() {

    }
}

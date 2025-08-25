<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentYear = Carbon::now()->year;

        // *** Obtener todos los datos necesarios en el controlador ***

        // Datos para las tarjetas de resumen
        $policiesSoldUser = Policy::where('user_id', $user->id)->count();
        $policiesValidUser = Policy::where('user_id', $user->id)
                                 ->whereDate('expiring_date', '>=', Carbon::now()->toDateString())
                                 ->count();
        // Basado en policies_sold_weeks - revisa la lógica de 'status', FALSE
        $policiesPorPagar = Policy::where('user_id', $user->id)
                               ->where('status', FALSE) // Asegúrate de que FALSE es correcto
                               // ->where('deleted_at', null) // Quitar si usas Soft Deletes
                               ->count();

        // Datos para Ganancia Quincenal y Total Pagar
        // Obtener las pólizas relevantes una vez
        $relevantPolicies = Policy::where('user_id', $user->id)
                               ->where('status', FALSE) // Asegúrate de que FALSE es correcto
                               // ->where('deleted_at', null) // Quitar si usas Soft Deletes
                               ->get(); // Obtener la colección

        $totalPagar = 0;
        $gananciaQuincenal = 0;



        // Datos para el gráfico de pólizas vendidas al mes
       

        // *** Pasar los datos a la vista ***
        return view('dashboard', compact(
            'policiesSoldUser',
            'policiesValidUser',
            'policiesPorPagar',
            'totalPagar',
            'gananciaQuincenal'
             // Pasar los datos del gráfico como JSON
        ));
    }
}

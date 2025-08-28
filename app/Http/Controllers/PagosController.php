<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Moderator;
use App\Models\Policy;

/**
 * Nuevo controlador para reemplazar controlador de pagos . . .
 */
class PagosController extends Controller
{
    /**
     * Muestra los pagos pendientes agrupados por vendedor
     */
    public function pendientes_por_vendedor()
    {
        $user = Auth::user();
        // Usuarios relacionados al administrador logueado

        $users = User::with('policies')->withCount('policies')
            ->FiltrarPorUsuarioAutenticado()
            ->whereHas('policies', function ($query) {
                $query->where('status', 0)->where('report', 1);
            })
            //->where('type', $user->type) //
            ->orderBy('policies_count')
            ->paginate(10);
        //polizas_reportadas_sin_pagar()->count()

        return view('Pagos.pendientes_por_vendedor', compact('users'));
    }

    /**
     * Muestra los pagos pendientes agrupados por moderador (supervisor)
     */
    public function pendientes_por_supervisor() {
        $user = Auth::user();
        $type = $user->type;

        $moderators = User::role(['moderador'])->where('type', $user->type)->get();

        // Iteramos sobre cada moderador (supervisor) para agregarle los datos del sumario
        // de ventas y los totales en comisiones.   
        foreach ($moderators as $mod) {
            // Una manera mas sencilla de obtener los IDs de los usuarios supervisados
            $ids_usuarios_supervisados = $mod->usuarios_moderados->pluck('id');
            // Obtener resúmenes de pólizas
            $sumario_de_polizas = Policy::selectRaw("
                    SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as notPaidCount,
                    SUM(CASE WHEN status = 0 AND report = 1 THEN 1 ELSE 0 END) as reportPaidCount,
                    SUM(CASE WHEN statusu = 1 THEN 1 ELSE 0 END) as nulasCount,
                    SUM(CASE WHEN status = 0  THEN total_premium ELSE 0 END) as totalVendido
                ")
                ->whereIn('user_id', $ids_usuarios_supervisados)
                ->whereNull('deleted_at')
                ->first(); // Usar first() porque solo necesitamos los resúmenes

            // le asignamos esta nueva propiedad al moderador de esta iteracion
            // para acceder a este facilmente desde la vista.
            $mod->sumario = $sumario_de_polizas;

            // Calcular la comisión y el total a recibir
            $mod->comision = $mod->sumario->totalVendido * $mod->profi_percentaje / 100;
            $mod->totalARecibir = $mod->sumario->totalVendido - $mod->comision;
        }
        return view('Pagos.pendientes_por_supervisor', compact('moderators'));
    }    
}

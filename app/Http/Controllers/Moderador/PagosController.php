<?php

namespace App\Http\Controllers\Moderador;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Policy;
use App\Models\ForeignUnit;

class PagosController extends Controller
{
    /**
     * Lista de usuarios que tienen polizas pendientes por pagar
     */
    public function index_pendientes()
    {
        $user = Auth::user();
        $mod_id = $user->id;
        
        $users = $user->usuarios_moderados;        

        // Obtener listas para datalists
        $users3 = User::whereIn('mod_id', $mod_id == 4 ? [4, 5, 6] : [$mod_id])->get(['id', 'username', 'name', 'lastname']);

        // Obtener resúmenes de políticas
        $policiesSummaries = Policy::selectRaw("
                 user_id,
                 SUM(CASE WHEN status = 0 AND report = 1 THEN 1 ELSE 0 END) as reportPaidCount,
                 SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as notPaidCount,
                 SUM(CASE WHEN status = 0 AND statusu = 1 THEN 1 ELSE 0 END) as nulasCount,
                 SUM(CASE WHEN status = 0 AND statusu IS NULL THEN total_premium ELSE 0 END) as totalNotPaidPrice
             ")
            ->whereIn('user_id', $users->pluck('id'))
            ->whereNull('deleted_at')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        return view('mod-modules.Payments.mod-payments-index-notpaid',  compact('users', 'users3', 'policiesSummaries'));
    }

    /**
     * Muestra los pagos pendientes para un usuario determinado
     */
    public function index_pendientes_por_usuario(User $user)
    {
        $not_paid = $policies = Policy::where('deleted_at', null)
            ->where('user_id', $user->id)
            ->where('status', FALSE)
            ->get();
        $foreign_reference = ForeignUnit::all()->pluck('foreign_reference')[0];

        return view('mod-modules.Payments.mod-payment-show-notpaid', compact('not_paid', 'foreign_reference'));
    }
}

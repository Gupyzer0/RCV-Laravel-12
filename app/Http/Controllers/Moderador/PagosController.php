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

    

    /**
     * Funciopn ajax para pagar las polizas de un usuario en particular
     */
    // public function selected_pay(Request $request, $id)
    // {
    //     $policies_selected = count($request->update_checkbox);

    //     $from_raw = Policy::where('user_id', $id)
    //         ->where('status', FALSE)
    //         ->orderBy('created_at', 'asc')
    //         ->limit(1)
    //         ->get('created_at');

    //     $until_raw = Policy::where('user_id', $id)
    //         ->where('status', FALSE)
    //         ->orderBy('created_at', 'desc')
    //         ->limit(1)
    //         ->get('created_at');

    //     // To store all the values of policies prices got from the user selection
    //     $total_all_raw = [];
    //     for ($i = 0; $i < $policies_selected; $i++) {
    //         array_push($total_all_raw, Policy::where('user_id', $id)
    //             ->where('status', FALSE)
    //             ->where('id', $request->update_checkbox[$i])
    //             ->pluck('total_premium')[0]);
    //     }

    //     $total_all = null;
    //     foreach ($total_all_raw as $total) {
    //         $total_all = $total_all + $total;
    //     }
    //     $total_payment = PaymentsController::profit_percentage($total_all, $request->profit_percentage);
    //     $from = Carbon::parse($from_raw[0]->created_at);
    //     $until = Carbon::parse($until_raw[0]->created_at);

    //     $payment = new Payment;
    //     $payment->name = $request->name;
    //     $payment->office = $request->office;
    //     $payment->user_id = $id;
    //     $payment->total = $total_all;
    //     $payment->profit_percentage = $request->profit_percentage;
    //     $payment->total_payment = $total_payment;
    //     $payment->from = $from;
    //     $payment->until = $until;
    //     $payment->save();

    //     $payment_id = Payment::orderBy('created_at', 'desc')->limit(1)->pluck('id');

    //     $relations = [];

    //     for ($i = 0; $i < $policies_selected; $i++) {
    //         $relation = [];
    //         $relation = new Relation_po_pa;
    //         $relation['policy_id'] = $request->update_checkbox[$i];
    //         $relation['payment_id'] = $payment_id[0];
    //         $relations[$i] = $relation;
    //         $relation->save();
    //     }

    //     // This updates the selected policies that are being "paid"
    //     for ($i = 0; $i < $policies_selected; $i++) {
    //         Policy::where('user_id', $id)->where('status', FALSE)->where('id', $request->update_checkbox[$i])->update(['status' => TRUE]);
    //     }

    //     if (PaymentsController::policies_not_paid($id) == 0) {
    //         return redirect('/mod/index-payments/notpaidd/')->with('success', 'Pago realizado correctamente');
    //     } else {
    //         return redirect()->back()->with('success', 'Pago realizado correctamente');
    //     }
    // }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use App\Policy;
use Carbon\Carbon;

class LockController extends Controller
{

    public function lock_auto()
    {

        // Obtener los IDs de usuario de las políticas que cumplen la condición
        $userIds = Policy::where('report', false)
                         ->whereNull('deleted_at')
                         ->pluck('user_id');

        // Si hay IDs, actualizar el status en la tabla User
        if ($userIds->count() > 0) {
            User::whereIn('id', $userIds)->update(['status' => 1]);
        }
    }

}

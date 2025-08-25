<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use App\User;
use App\Soporte;


class TicketController extends Controller
{

    public function index()
    {
        $type = Auth::user()->type;

        if ($type != 999502) {
            $datos = Soporte::where('status', '<', 3)
                            ->where('type', $type)
                            ->orderBy('status', 'desc')
                            ->get();
        } else {
            $datos = Soporte::where('status', '<', 3)
                            ->orderBy('status', 'desc')
                            ->get();
        }

        return view('admin-modules.Soporte.index', compact('datos'));
    }

  public function store(Request $request)
{
    $user = Auth::user();
    $request->validate([
        'descripcion' => 'required|string|max:255',
        'tsolicitud' => 'required',
        'nivel' => 'required|string',
        'adjunto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Manejar la subida del archivo
    $filePath = $request->file('adjunto') ? $request->file('adjunto')->store('solicitudes') : null;

    // Guardar la solicitud
    Soporte::create([
        'nombre' => $user->name,
        'descripcion' => $request->descripcion,
        'tsolicitud' => $request->tsolicitud,
        'nivel' => $request->nivel,
        'imagen' => $filePath,
        'status' => 1,
    ]);

    return redirect()->back()->with('success', 'Solicitud creada exitosamente.');
}

public function updateStatus($id, $status)
{
    $ticket = Soporte::find($id);

    if ($ticket) {
        $ticket->status = $status;
        $ticket->save();
        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }

    return redirect()->back()->with('error', 'Ticket no encontrado.');
}





}

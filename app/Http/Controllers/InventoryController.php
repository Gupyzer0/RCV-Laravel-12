<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Office;
use App\Models\Inventory;
use App\Models\Estado;

class InventoryController extends Controller
{

    public function index_inventory(){

      $offices = Office::all();
      $type = Auth::user()->type;
      $usuarios = Inventory::distinct()
        ->where('type', $type)
        ->where('user_id', '!=', NULL)
        ->pluck('user_id');

      $users = [];

      for($i = 0; $i < count($usuarios); $i++){
        array_push($users, User::where('id', $usuarios[$i])->withTrashed()->first());
      }

      return view('admin-modules.Inventario.admin-inventory-index', compact('users','offices'));
    }


    public function create_admin()
    {

        $offices = Office::all();
        $type = Auth::user()->type;
        $users    = User::where('type', $type)->get();
        $inventory = Inventory::all();
        return view('admin-modules.Inventario.admin-inventory-create', compact(  'offices','users','inventory'));
    }



    public function store_admin(Request $request)
    {
        $this->validate(
            $request,
            [
              'username'=> ['required'],
              'descripcion'=> ['required'],
              'status'=> ['required']
            ]
        );

        $type = Auth::user()->type;
        $inventory  = new Inventory;
        $inventory->type           = $type;
        $inventory->user_id        = $request->input('username');
        $inventory->descripcion    = ucwords($request->input('descripcion'));
        $inventory->marca          = ucwords($request->input('marca'));
        $inventory->modelo         = ucwords($request->input('modelo'));
        $inventory->serial         = strtoupper($request->input('serial'));
        $inventory->status         = $request->input('status');


        $inventory->save();
         return redirect('/admin/index-inventory/')->with('success' . 'Articulo Registrado Correctamente');
    }

    public function edit_admin($id)
    {
        $inventory = Inventory::findOrFail($id);
        $type = Auth::user()->type;
        $users = User::where('type', $type)->get();

        return view('admin-modules.Inventario.admin-inventory-edit', compact('users', 'inventory'))->with('id', $id);
    }

      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update_admin(Request $request, $id){
        $this->validate(
            $request,
            [
              'username'=> ['required'],
              'descripcion'=> ['required'],
              'status'=> ['required']
            ]
        );


        $type = Auth::user()->type;
        $inventory  = Inventory::findOrFail($id);
        $inventory->type           = $type;
        $inventory->user_id        = $request->input('username');
        $inventory->descripcion    = ucwords($request->input('descripcion'));
        $inventory->marca          = ucwords($request->input('marca'));
        $inventory->modelo         = ucwords($request->input('modelo'));
        $inventory->serial         = strtoupper($request->input('serial'));
        $inventory->status         = $request->input('status');
        $inventory->update();


           // Redirigir a la misma página de edición con un mensaje de éxito
    return redirect()->route('show.inventory', ['id' => $inventory->user->id])
    ->with('success', 'Inventario actualizado correctamente');

    }


    public function admin_destroy($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventory->delete();
        return redirect('/admin/index-inventory');
    }

    public static function inventoryid($user_id)
    {
      $inventorys = Inventory::where('deleted_at', null)
                          ->where('user_id', $user_id)
                          ->get();

      // Check if inventorys is not null to avoid errors
      if($inventorys->first() != null){
        $inventorys_to_num = [];

        // Iterate over $inventorys to push each element to an array with the purpose of count the elements in it
        foreach ($inventorys as $row) {
          array_push($inventorys_to_num, $row);
        }

        // Count and return the counted elements
        $counted_inventorys = count($inventorys_to_num);
        return $counted_inventorys;
      }

      // Return 0 if $inventorys is null
      return 0;
    }

    public static function inventory_count($user_id)
    {
      $policies = Inventory::where('deleted_at', null)
                          ->where('user_id', $user_id)
                          ->where('status', FALSE)
                          ->get();

      // Check if policies is not null to avoid errors
      if($policies->first() != null){
        $policies_to_num = [];

        // Iterate over $policies to push each element to an array with the purpose of count the elements in it
        foreach ($policies as $row) {
          array_push($policies_to_num, $row);
        }

        // Count and return the counted elements
        $counted_policies = count($policies_to_num);
        return $counted_policies;
      }

      // Return 0 if $policies is null
      return 0;
    }


    public function show_inventory($user_id)
    {
        $type = Auth::user()->type;
      $users    = User::where('type', $type)->get();
      $estados         = Estado::all();
      $inventory  = Inventory::where('deleted_at', null)
                                ->where('user_id', $user_id)
                                ->get();



      return view('admin-modules.Inventario.admin-inventory-show', compact('inventory','estados','users'));
    }

//Controlador Supervisor

public function index_mod(){

    $offices = Office::all();
    $user = Auth::user();
    $type = $user->type;
    $mod_id = $user->id;

    $usuarios = Inventory::distinct()
        ->where('type', $type)
        ->where('user_id', '!=', NULL)
        ->pluck('user_id');

    $users = [];
    $contador = 0;

    foreach ($usuarios as $user_id) {
        $usuario = User::where('id', $user_id)
            ->where('mod_id', $mod_id)
            ->withTrashed()
            ->first();

        if ($usuario) {
            array_push($users, $usuario);
            $contador++; // Incrementa el contador por cada usuario que cumpla la condición
        }
    }

    return view('mod-modules.Inventario.mod-inventory-index', compact('users', 'offices', 'contador'));
}

public function create_mod()
    {

        $offices = Office::all();
        $id = Auth::user()->id;
        $users    = User::where('mod_id', $id)->get();
        $inventory = Inventory::all();
        return view('mod-modules.Inventario.mod-inventory-create', compact(  'offices','users','inventory'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store_mod(Request $request)
    {
        $this->validate(
            $request,
            [
              'username'=> ['required'],
              'descripcion'=> ['required'],
              'marca'=> ['required'],
              'modelo'=> ['required'],
              'serial'=> ['required'],
              'status'=> ['required']
            ]
        );

        $type = Auth::user()->type;
        $inventory  = new Inventory;
        $inventory->type           = $type;
        $inventory->user_id        = $request->input('username');
        $inventory->descripcion    = ucwords($request->input('descripcion'));
        $inventory->marca          = ucwords($request->input('marca'));
        $inventory->modelo         = ucwords($request->input('modelo'));
        $inventory->serial         = strtoupper($request->input('serial'));
        $inventory->status         = $request->input('status');


        $inventory->save();
         return redirect('/mod/index-inventory/')->with('success' . 'Articulo Registrado Correctamente');
    }

    public function show_inventory_mod($user_id)
    {
        $id = Auth::user()->id;
        $users    = User::where('mod_id', $id)->get();
      $estados         = Estado::all();
      $inventory  = Inventory::where('deleted_at', null)
                                ->where('user_id', $user_id)

                                ->get();



      return view('mod-modules.Inventario.mod-inventory-show', compact('inventory','estados','users'));
    }



//Fin Supervisor


}




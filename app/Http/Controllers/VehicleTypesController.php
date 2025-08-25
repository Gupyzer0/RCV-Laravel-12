<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleType;

class VehicleTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vehicle_types = VehicleType::all();
        return view('user-modules.Vehicles.vehicle-types-index', compact('vehicle_types'));
    }

    public function index_admin()
    {
        $vehicle_types = VehicleType::all();
        return view('admin-modules.Vehicles.admin-vehicle-types-index', compact('vehicle_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user-modules.Vehicles.vehicle-types-create');
    }

    public function create_admin()
    {
        return view('admin-modules.Vehicles.admin-vehicle-types-create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'type' => ['required', 'unique:vehicle_types'],
        ],[
            'type.unique' => 'El Tipo de Vehiculo ya se encuentra registrado',
        ]);

        $vehicle_type = new VehicleType;
        $vehicle_type->type = $request->input('type');
        $vehicle_type->save();

        $previous_url = [];
        preg_match('/\/user\/register-vehicle/', url()->previous(), $previous_url);

        if (in_array('/user/register-vehicle', $previous_url)) {
            return redirect('/user/register-vehicle')->with('success', 'Registro exitoso');            
        } elseif($previous_url == null){
            return redirect('/user/index-types')->with('success', 'Registro exitoso');
        }
    }


    public function store_admin(Request $request)
    {
        $this->validate($request, [
            'type' => ['required', 'unique:vehicle_types'],
        ],[
            'type.unique' => 'El Tipo de Vehiculo ya se encuentra registrado',
        ]);

        $vehicle_type = new VehicleType;
        $vehicle_type->type = $request->input('type');
        $vehicle_type->save();

        $previous_url = [];
        preg_match('/\/admin\/register-vehicle/', url()->previous(), $previous_url);
    
        if (in_array('/admin/register-vehicle', $previous_url)) {
            return redirect('/admin/register-vehicle')->with('success', 'Registro exitoso');            
        } elseif($previous_url == null){
            return redirect('/admin/index-types')->with('success', 'Registro exitoso');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehicle_type = VehicleType::findOrFail($id);

        return view('user-modules.Vehicles.vehicle-type-edit', compact('vehicle_type')); 
    }

    public function edit_admin($id)
    {
        $vehicle_type = VehicleType::findOrFail($id);

        return view('admin-modules.Vehicles.admin-vehicle-type-edit', compact('vehicle_type')); 
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vehicle_type = VehicleType::findOrFail($id);
        $vehicle_type->type = $request->input('type');
        $vehicle_type->save();

        return redirect('/user/index-types')->with('success', 'Tipo de vehículo actualizado correctamente');
    }

    public function update_admin(Request $request, $id)
    {
        $vehicle_type = VehicleType::findOrFail($id);
        $vehicle_type->type = $request->input('type');
        $vehicle_type->save();

        return redirect('/admin/index-types')->with('success', 'Tipo de vehículo actualizado correctamente');

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle_type = VehicleType::findOrFail($id);
        $vehicle_type->delete();

        return redirect('/admin/index-types')->with('success', 'Tipo de vehículo eliminado correctamente');
    }
}

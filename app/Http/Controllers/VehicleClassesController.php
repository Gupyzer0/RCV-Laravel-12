<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleClass;

class VehicleClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_admin()
    {
        $vehicle_classes = VehicleClass::all();
        return view('admin-modules.Vehicles.admin-vehicle-classes-index', compact('vehicle_classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_admin()
    {
        return view('admin-modules.Vehicles.admin-vehicle-classes-create');
    }

    public function store_admin(Request $request)
    {
        $this->validate($request, [
            'class' => ['required'],
        ]);

        $vehicle_class = new VehicleClass;
        $vehicle_class->class = $request->input('class');
        $vehicle_class->save(); 

        $previous_url = [];
        preg_match('/(\/admin\/register-price)/', url()->previous(), $previous_url);

        if (in_array('/admin/register-price', $previous_url)) {
            return redirect('/admin/register-price')->with('success', 'Registro exitoso');
        } elseif ($previous_url == null) {
            return redirect('/admin/index-classes')->with('success', 'Registro exitoso');
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_admin($id)
    {
        $vehicle_class = VehicleClass::findOrFail($id);
        return view('admin-modules.Vehicles.admin-vehicle-class-edit', compact('vehicle_class'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_admin(Request $request, $id)
    {
        $this->validate($request, [
            'class' => ['required'],
        ]);

        $vehicle_class = VehicleClass::findOrFail($id);
        $vehicle_class->class = $request->input('class');
        $vehicle_class->save();

        return redirect('/admin/index-classes')->with('success', 'Clase de vehículo actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle_class = VehicleClass::findOrFail($id);
        $vehicle_class->delete();
        return redirect('/admin/index-classes')->with('success', 'Clase de vehículo eliminada correctamente');
    }
}

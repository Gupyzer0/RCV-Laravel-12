<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use Auth;
use Session;
use App\User;

class LocationController extends Controller
{

    public function showMap()
    {
        $locations = Location::all(); // Obtener todas las ubicaciones de la base de datos
        return view('admin-modules.Users.admin-map', compact('locations'));
    }


    public function extractCoordinates(Request $request, $id)
    {
        $request->validate([
            'google_maps_link' => 'required|url'
        ]);

        // Buscar las coordenadas en el enlace proporcionado
        preg_match('/@(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $request->google_maps_link, $matches);
        $latitude = $matches[1] ?? null;
        $longitude = $matches[2] ?? null;

        // Guardar las coordenadas en la base de datos
        $user = User::findOrFail($id);
        $location = New Location;
        $location->google_maps_link = $request->google_maps_link;
        $location->latitude = $latitude;
        $location->longitude = $longitude;
        $location->type = $user->type;
        $location->user_id = $user->id;
        $location->save();


        $user->url =  $request->input('google_maps_link');
        $user-> update();


        return redirect()->route('map');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Establecer el campo 'url' en null en el modelo User
        $user->url = null;
        $user->save();

        // Buscar las ubicaciones asociadas a este usuario y eliminarlas
        $maps = Location::where('user_id', $id)->get();
        foreach ($maps as $map) {
            $map->delete();
        }

        return redirect()->back()->with('success', 'Usuario y ubicaciones asociadas eliminadas correctamente');
    }



}

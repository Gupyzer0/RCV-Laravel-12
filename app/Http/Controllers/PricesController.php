<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Price;
use App\Models\VehicleClass;
use App\Models\Office;
use App\Models\ForeignUnit;

use PDF;

class PricesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $office = Auth::user()->office_id;

        $prices = Price::where('office_id', $office)

        ->orwhere('office_id', 0)->get();

        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;

        return view('user-modules.Prices.prices-index', compact('prices', 'dolar','euro'));
    }


    public function index_admin(){
        $prices = Price::orderby('id', 'asc')->get();
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];
        $counter = 0;

        return view('admin-modules.Prices.admin-prices-index', compact('prices', 'counter', 'foreign_reference'));
    }




    public function index_mod()    {

        $type = Auth::user()->type;
        $prices = Price::orderby('description', 'asc')->where('office_id', 0)->get();
       $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $dolar = ForeignUnit::skip(1)->first()->foreign_reference;
        $counter = 0;

        return view('mod-modules.Prices.mod-prices-index', compact('prices', 'counter', 'dolar','euro'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */



        

        public function export($id)
    {
        $price = price::find($id);
        $euro = ForeignUnit::first()->pluck('foreign_reference')[0];
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0] / ForeignUnit::skip(1)->first()->foreign_reference;
        $counter = 0;

        $data = ['price' => $price,
                 'foreign_reference'=> $foreign_reference,
                 'euro'=> $euro
                ];

        // $customPaper = array(0,0,700,1050);
        $pdf = PDF::loadView('admin-modules.Prices.admin-price-export', $data)->setPaper('letter','portrait');

        $fileName = $price->description;

        return $pdf->stream($fileName . '.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price = Price::find($id);
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];



        return view('user-modules.Prices.price-show', compact('price','foreign_reference'));
    }


    public function show_admin($id)
    {
        $price = Price::find($id);
        $offices = Office::all();
        $foreign_reference = ForeignUnit::first()->pluck('foreign_reference')[0];

        return view('admin-modules.Prices.admin-price-show', compact('price', 'foreign_reference','offices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_edit($id)
    {
        $price = Price::findOrFail($id);

        $vehicle_classes = VehicleClass::all();
        $offices = Office::all();
        return  view('admin-modules.Prices.admin-price-edit', compact('price', 'id','vehicle_classes', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function admin_update(Request $request, $id)
    {
        $this->validate($request, [
            'vehicle_class'    => ['required'],
            'office_id'        => ['required'],
            'description'      => ['required','min:1'],

        ]);

        $price = Price::findOrFail($id);

        $price->description       = strtoupper($request->input('description'));
        $price->class_id          = $request->input('vehicle_class');
        $price->office_id           = $request->input('office_id');

        $price->campo             = strtoupper($request->input('campo'));

        // Verificación para campos campoc y campop
        $price->campoc            = $request->input('campoc') > 0 ? $request->input('campoc') : null;
        $price->campop            = $request->input('campop') > 0 ? $request->input('campop') : null;

        $price->campo1            = strtoupper($request->input('campo1'));
        $price->campoc1           = $request->input('campoc1') > 0 ? $request->input('campoc1') : null;
        $price->campop1           = $request->input('campop1') > 0 ? $request->input('campop1') : null;

        $price->campo2            = strtoupper($request->input('campo2'));
        $price->campoc2           = $request->input('campoc2') > 0 ? $request->input('campoc2') : null;
        $price->campop2           = $request->input('campop2') > 0 ? $request->input('campop2') : null;

        $price->campo3            = strtoupper($request->input('campo3'));
        $price->campoc3           = $request->input('campoc3') > 0 ? $request->input('campoc3') : null;
        $price->campop3           = $request->input('campop3') > 0 ? $request->input('campop3') : null;

        $price->campo4            = strtoupper($request->input('campo4'));
        $price->campoc4           = $request->input('campoc4') > 0 ? $request->input('campoc4') : null;
        $price->campop4           = $request->input('campop4') > 0 ? $request->input('campop4') : null;

        $price->campo5            = strtoupper($request->input('campo5'));
        $price->campoc5           = $request->input('campoc5') > 0 ? $request->input('campoc5') : null;
        $price->campop5           = $request->input('campop5') > 0 ? $request->input('campop5') : null;

        $price->campo6            = strtoupper($request->input('campo6'));
        $price->campoc6           = $request->input('campoc6') > 0 ? $request->input('campoc6') : null;
        $price->campop6           = $request->input('campop6') > 0 ? $request->input('campop6') : null;

        $price->campo7            = strtoupper($request->input('campo7'));
        $price->campoc7           = $request->input('campoc7') > 0 ? $request->input('campoc7') : null;
        $price->campop7           = $request->input('campop7') > 0 ? $request->input('campop7') : null;

        // Suma de totales
        $price->total_all =
            ($price->campoc ?? 0) + ($price->campoc1 ?? 0) + ($price->campoc2 ?? 0) +
            ($price->campoc3 ?? 0) + ($price->campoc4 ?? 0) + ($price->campoc5 ?? 0) +
            ($price->campoc6 ?? 0) + ($price->campoc7 ?? 0);

        $price->total_premium =
            ($price->campop ?? 0) + ($price->campop1 ?? 0) + ($price->campop2 ?? 0) +
            ($price->campop3 ?? 0) + ($price->campop4 ?? 0) + ($price->campop5 ?? 0) +
            ($price->campop6 ?? 0) + ($price->campop7 ?? 0);

        $price->update();




        return redirect('/admin/index-prices')->with('success', 'Plan Actualizado ');

    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prices = Price::findOrFail($id);
        $name = $prices->description;
        $prices->delete();
        return redirect('/admin/index-prices')->with('danfger', 'Se Elimino ' . $name);
    }

    ///nuevos precios


    public function create()
    {
        $type = Auth::user()->type;
        $vehicle_classes = VehicleClass::all();
        $offices = Office::where('type', $type)->orwhere('id', 0)->get();

      return view('admin-modules.Prices.admin-prices-create', compact('vehicle_classes', 'offices'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $this->validate($request, [
        'vehicle_class'     => ['required'],
        'description'       => ['required','min:1']
    ]);

    $price = new Price;

    $price->description       = strtoupper($request->input('description'));
    $price->class_id          = $request->input('vehicle_class');
    $price->office_id           = $request->input('office_id');
    $price->campo             = strtoupper($request->input('campo'));

    // Verificación para campos campoc y campop
    $price->campoc            = $request->input('campoc') > 0 ? $request->input('campoc') : null;
    $price->campop            = $request->input('campop') > 0 ? $request->input('campop') : null;

    $price->campo1            = strtoupper($request->input('campo1'));
    $price->campoc1           = $request->input('campoc1') > 0 ? $request->input('campoc1') : null;
    $price->campop1           = $request->input('campop1') > 0 ? $request->input('campop1') : null;

    $price->campo2            = strtoupper($request->input('campo2'));
    $price->campoc2           = $request->input('campoc2') > 0 ? $request->input('campoc2') : null;
    $price->campop2           = $request->input('campop2') > 0 ? $request->input('campop2') : null;

    $price->campo3            = strtoupper($request->input('campo3'));
    $price->campoc3           = $request->input('campoc3') > 0 ? $request->input('campoc3') : null;
    $price->campop3           = $request->input('campop3') > 0 ? $request->input('campop3') : null;

    $price->campo4            = strtoupper($request->input('campo4'));
    $price->campoc4           = $request->input('campoc4') > 0 ? $request->input('campoc4') : null;
    $price->campop4           = $request->input('campop4') > 0 ? $request->input('campop4') : null;

    $price->campo5            = strtoupper($request->input('campo5'));
    $price->campoc5           = $request->input('campoc5') > 0 ? $request->input('campoc5') : null;
    $price->campop5           = $request->input('campop5') > 0 ? $request->input('campop5') : null;

    $price->campo6            = strtoupper($request->input('campo6'));
    $price->campoc6           = $request->input('campoc6') > 0 ? $request->input('campoc6') : null;
    $price->campop6           = $request->input('campop6') > 0 ? $request->input('campop6') : null;

    $price->campo7            = strtoupper($request->input('campo7'));
    $price->campoc7           = $request->input('campoc7') > 0 ? $request->input('campoc7') : null;
    $price->campop7           = $request->input('campop7') > 0 ? $request->input('campop7') : null;

    // Suma de totales
    $price->total_all =
        ($price->campoc ?? 0) + ($price->campoc1 ?? 0) + ($price->campoc2 ?? 0) +
        ($price->campoc3 ?? 0) + ($price->campoc4 ?? 0) + ($price->campoc5 ?? 0) +
        ($price->campoc6 ?? 0) + ($price->campoc7 ?? 0);

    $price->total_premium =
        ($price->campop ?? 0) + ($price->campop1 ?? 0) + ($price->campop2 ?? 0) +
        ($price->campop3 ?? 0) + ($price->campop4 ?? 0) + ($price->campop5 ?? 0) +
        ($price->campop6 ?? 0) + ($price->campop7 ?? 0);

    $price->save();

    return redirect('/admin/index-prices')->with('success', 'Registro Existoso');
    }
}

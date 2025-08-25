@extends('layouts.app')

@section('module')
<div class="card">
  <div class="card-body">
    <h3 class="card-title text-center">Descripción del plan</h3>
    <h5 class="card-title text-center">Clase de vehículo: <strong>{{$price->class->class}}</strong></h5>
    <div class="row border-bottom border-dark mb-4">
      <div class="col-6 text-center border-right border-dark">
        <h6><span class="font-weight-bold mr-2">Daños a cosas: </span><span class="prices_se">{{number_format($price->damage_things * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Daños a personas: </span><span class="prices_se">{{number_format($price->damage_people * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Asistencia jurídica: </span><span class="prices_se">{{number_format($price->legal_assistance * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Muerte: </span><span class="prices_se">{{number_format($price->death * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Invalidez: </span><span class="prices_se">{{number_format($price->disability * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Gastos médicos: </span><span class="prices_se">{{number_format($price->medical_expenses * $foreign_reference, 2)}}</span> Bs.S</h6>
        @if($price->damage_passengers == 0)
        <h6><span class="font-weight-bold mr-2">Daño a pasajeros: </span>No aplica</h6>
        @else
        <h6><span class="font-weight-bold mr-2">Daño a pasajeros: </span><span class="prices_se">{{number_format($price->damage_passengers * $foreign_reference, 2)}}</span> Bs.S</h6>
        @endif  
        @if($price->crane == 0)
        <h6><span class="font-weight-bold mr-2">Grua: </span>No aplica</h6>
        @else
        <h6><span class="font-weight-bold mr-2">Grua: </span><span class="prices_se">{{number_format($price->crane * $foreign_reference, 2)}}</span> Bs.S</h6>
        @endif       
        <h6 class="mt-4"><span class="font-weight-bold mr-2">Total Cobertura: </span><span class="prices_se">{{number_format($price->total_all * $foreign_reference, 2)}}</span> Bs.S</h6>
      </div>
      <div class="col-6 text-center">
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium1 * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium2 * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium3 * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium4 * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium5 * $foreign_reference, 2)}}</span> Bs.S</h6>     
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium6 * $foreign_reference, 2)}}</span> Bs.S</h6>
        <h6><span class="font-weight-bold mr-2">Prima: </span><span>{{number_format($price->premium7 * $foreign_reference, 2)}}</span> Bs.S</h6>
        <h6 class="mt-5"><span class="font-weight-bold mr-2">Total Prima: </span><span>{{number_format($price->total_premium * $foreign_reference, 2)}}</span> Bs.S</h6>     
      </div>
    </div>



    <a href="{{url()->previous()}}" class="btn btn-danger active">Volver</a>
  </div>
</div>  

@endsection
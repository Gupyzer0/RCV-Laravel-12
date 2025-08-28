@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
    <div class="card-header py-3">
		<a href="{{ url()->previous() }}" class="float-right btn btn-danger text-white">X</a>
	</div>

    <div class="card-body">
		<div class="table-responsive">
			<form action="/admin/selected-payr/{{$policies[0]->user->id}}" enctype="multipart/form-data" method="POST">
                @csrf
                <button type="submit" class="pagar btn btn-success pl-4 pr-4" style="float: right"> Pagar</button>
				<table class="table table-bordered text-center" id="" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
						<th>N° Poliza</th>
                        <th>Seleccionar</th>
						<th>Vendedor</th>
						<th>Beneficiario</th>
						<th>Vehículo</th>
						<th>Placa</th>
						<th>Fecha de Emisión</th>
						<th>Plan</th>
						<th>Total Prima Poliza</th>

					</tr>
				</thead>
				<tbody>
                    <?php $suma=0; $dato=0; $coun=0;?>
					@foreach($policies as $policy)
					<tr>
                        @if($policy->idp)
                        <td>{{$policy->idp}}</td>
                        @else
                        <td>{{$policy->id}}</td>
                        @endif
                        @if($policy->report)
                        <td><input type="checkbox" checked='checked' class="settings" name="update_checkbox[]" value="{{$policy->id}}"></td>
                        @else
                        <td><input type="checkbox" class="settings" name="update_checkbox[]" value="{{$policy->id}}"></td>
                        @endif

						<input type="hidden" value="{{$policy->user->name.' '.$policy->user->lastname}}" name="name">
						<td>{{$policy->user->name.' '.$policy->user->lastname}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
						<input type="hidden" value="{{$policy->user->office->office_address}}" name="office">
						<input type="hidden" value="{{$policy->user->profit_percentage}}" name="profit_percentage">
                        @if(!$policy->damage_things)
						<td>{{$policy->price->description}}</td>
                        @else
                        <td>x </td>
                        @endif
                    	<td>{{number_format($policy->total_premium, 2)}} €</td>


						<?php
						$dato = $policy->total_premium * $policy->foreign;
						$suma = $suma + $dato;
						$coun = $coun + 1;
						?>

					</tr>
					@endforeach
				</tbody>
			</table>
        </form>
        </div>
    </div>
</div>





@endsection



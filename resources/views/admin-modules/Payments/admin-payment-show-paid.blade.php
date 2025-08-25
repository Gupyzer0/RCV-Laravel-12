@extends('layouts.admin-modules')
<?php use \App\Http\Controllers\PaymentsController; ?>

@section('module')
<a class="btn btn-light mb-2 shadow" href="{{ route('index.payments')}}">Ver Consultas de pago</a>
<a class="btn btn-success float-right shadow" href="/admin/index-payment/{{$policies[0]->user_id}}">Regresar</a>
<div class="card shadow mb-2">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Polizas asociadas a este pago</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
				<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
						<th>N. Poliza</th>
						<th>Cliente</th>
						<th>Vehiculo</th>
						<th>Fecha</th>
						<th>Total Prima Poliza</th>
						<th>Telefono</th>
					</tr>
				</thead>
				<tbody>
					@foreach($policies as $policy)
					<tr>
                        @if($policy->idp)
                        <td>{{$policy->idp}}</td>
                        @else
                        <td>{{$policy->id}}</td>
                        @endif
						<input type="hidden" value="{{$policy->user->name.' '.$policy->user->lastname}}" name="name">
						<input type="hidden" value="{{$policy->user->office->office_address}}" name="office">
						<input type="hidden" value="{{$policy->user->profit_percentage}}" name="profit_percentage">
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
						<td>{{number_format($policy->total_premium, 2)}} @if(!$policy->damage_things)€ @else $ @endif</td>
						<td>{{$policy->client_phone}}</td>

					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script>
	$(document).ready(function() {
	$("tbody").find('tr').each(function() {
		let objects = $(this).find('span.prices_ce');
		console.log(objects);
		for(object of objects){
			console.log(object.innerText);
			object.innerText = number_format(object.innerText);
		}
	})
});

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
  prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
  sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
  dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
  s = '',
  toFixedFix = function(n, prec) {
  	var k = Math.pow(10, prec);
  	return '' + Math.round(n * k) / k;
  };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
  	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
  	s[1] = s[1] || '';
  	s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}
</script>
@endsection

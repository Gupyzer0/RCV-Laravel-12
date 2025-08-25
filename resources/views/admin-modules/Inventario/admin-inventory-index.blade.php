@extends('layouts.admin-modules')
<?php use \App\Http\Controllers\InventoryController; ?>

@section('module')

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Inventario</h6>
		<a class="btn btn-success float-right" href="{{ route('register.inventory')}}">Registrar</a>
	</div>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
				<thead>

					<tr>
					    <th>Id</th>
						<th>Vendedor</th>
						<th>Oficina</th>
						<th>Estado</th>
						<th>Cantidad de Equipos</th>
						<th>Acciones</th>

					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)

					<tr>
					    {{-- Id Vendedor --}}
						<td>{{$user->id}}</td>
						{{-- Fin Id vendedor --}}

						{{-- Vendedor --}}
						<td>{{$user->name.' '.$user->lastname}}</td>
						{{-- Fin vendedor --}}

						{{-- Oficina --}}
						<td>{{$user->office->office_address}}</td>
						{{-- Fin oficina --}}

						{{-- Oficina Estado --}}
						<td>{{$user->office->estado->estado}}</td>
						{{-- Fin oficina --}}

						<td>{{InventoryController::inventory_count($user->id)}}</td>

						<td class="p-0">
						<a href="/admin/index-inventory/{{$user->id}}" class="btn btn-primary mt-2">Ver</a>
						</td>

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
		let objects = $(this).find('span.prices_se');
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

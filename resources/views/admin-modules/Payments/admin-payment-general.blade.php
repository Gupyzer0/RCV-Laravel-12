@extends('layouts.admin-modules')
@section('module')
<div class="card shadow mb-2">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Reporte General</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<div class="card-header py-2">
                @if(isset($user))
                <form action="{{ route('vendedor.seach1') }}" method="GET" style="display: inline;">
                    <input type="hidden" name="fechai" value="{{ $fechai ?? '' }}">
                    <input type="hidden" name="fechaf" value="{{ $fechaf ?? '' }}">
                    <input type="hidden" name="user" value="{{ $user ?? '' }}">
                    <input type="hidden" name="export" value="pdf">
                    <button type="submit" class="btn btn-success float-right" formtarget="_blank">Exportar PDF</button>
                </form>
                @endif
                <div class="fomf">
                    <form class="form-inline d-flex align-items-center" method="GET" name="formFechas" id="formFechas" action="{{ route('vendedor.seach1') }}">
                        <div class="form-group mx-2">
                            <label for="fechai" class="mr-2">Desde</label>
                            <input type="date" class="form-control" name="fechai" id="fechai" value="{{ request('fechai') }}">
                        </div>
                        <div class="form-group mx-2">
                            <label for="fechaf" class="mr-2">Hasta</label>
                            <input type="date" class="form-control" name="fechaf" id="fechaf" value="{{ request('fechaf') }}">
                        </div>
                        <div class="form-group mx-2">
                            <select class="js-example-basic-single form-control" id="user" onchange="this.form.submit()" name="user">
                                <option value="">Filtrar por Vendedor</option>
                                <option value="0">Todos</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name.' '.$user->lastname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mx-2">
                            <a class="btn btn-primary" href="{{ route('index.payments') }}">Limpiar</a>
                        </div>
                    </form>
                </div>
            </div>

				<table class="table table-bordered text-center" id="" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
						<th>Num. Afiliación</th>
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
                       
                        <td>{{$policy->id}}</td>
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


@extends('layouts.app')
<?php use \App\Http\Controllers\PaymentsController; ?>

@section('module')
<!--<a class="btn btn-light shadow" href="{{ route('index.payments')}}">Ver Consultas de pago</a>-->
<a class="btn btn-warning shadow" href="{{  URL::previous()}}">Regresar</a>
<div class="card shadow mb-4">
    <div class="card-header py-2">
        @foreach($not_paid as $policy)
        @endforeach
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Polizas por pagar del vendedor: {{$policy->user->name.' '.$policy->user->lastname}}</h6>



	</div>
	<div class="card-body">
		<div class="table-responsive">
			<form action="/mod/selected-pay/{{$not_paid[0]->user->id}}" method="POST">
				@csrf
				<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
					    <th>N°</th>
						<th>Asegurado</th>
						<th>Vehículo</th>
						<th>Placa</th>
						<th>Fecha de Emisión</th>
						<th>Plan</th>
						<th>Total Prima Poliza</th>
                        <th>Seleccionar</th>

					</tr>
				</thead>
				<tbody>
                    <?php $suma=0; $dato=0; $coun=0;?>
					@foreach($not_paid as $policy)
					<tr>


						<input type="hidden" value="{{$policy->user->name.' '.$policy->user->lastname}}" name="name">
						<td>{{$policy->id}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
						<input type="hidden" value="{{$policy->user->office->office_address}}" name="office">
						<input type="hidden" value="{{$policy->user->profit_percentage}}" name="profit_percentage">
						<td>{{$policy->price->description}}</td>
						<td>{{number_format($policy->total_premium, 2)}} €</td>
                       @if(!$policy->statusu)
                        @if($policy->report)
                        <td><input type="checkbox" checked='checked' class="settings" name="update_checkbox[]" value="{{$policy->id}}"></td>
                        @else
                        <td><input type="checkbox" class="settings" name="update_checkbox[]" value="{{$policy->id}}"></td>
                        @endif
                        @else
                        <td><p class="text-danger">ANULADA</p></td>
                        @endif

						<?php
						$dato = $policy->total_premium * $policy->foreign;
						$suma = $suma + $dato;
						$coun = $coun + 1;
						?>

					</tr>
                    <div class="modal fade" id="deleteModal-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">INFORMACIÓN</h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body"><span>La primera poliza creada no se puede Eliminar por aqui, para eliminarla debe ir a polizas</div>
								<div class="modal-footer">
									<form action="" method="POST">
										@csrf
										<a href="/mod/index-policy/{{$policy->id}}" class="btn btn-info float-right">Ver Poliza</a>
										<button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
									</form>
								</div>
							</div>
						</div>
					</div>

                    {{-- Borrar Poliza --}}
                    <div class="modal fade" id="deleteModal1-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">eliminar</strong> esta poliza?</h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">eliminar</span> esta poliza</div>
								<div class="modal-footer">
									<form action="/admin/delete-policy-pay/{{$policy->id}}" method="POST">
										@csrf
										@method('DELETE')
										<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
										<button type="submit" class="btn btn-primary">Continuar</button>
									</form>
								</div>
							</div>
						</div>
					</div>

					@endforeach
				</tbody>
			</table>
			<button type="button" class="check-all btn btn-primary" style="float: right">Seleccionar Todos</button>

			<button type="submit" class="pagar btn btn-success pl-4 pr-4" style="float: right"> Pagar</button>




		</form>
	</div>
	</div>
</div>

@endsection

@section('scripts')





<script>
	var checked = false;

$('.check-all').on('click',function(){

if(checked == false) {
$('.settings').prop('checked', true);
checked = true;
} else {
$('.settings').prop('checked', false);
checked = false;
}

});


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

@extends('layouts.admin-modules')
<?php use \App\Http\Controllers\PaymentsController; ?>

@section('module')
<a class="btn btn-warning shadow mb-2" href="{{ route('index.notpaid')}}">Ver no pagados</a>
<div class="card shadow mb-2">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Consultas de Pago: Lista de vendedores</h6>
	</div>
	    <nav class="navbar navbar-light" >
        <form class="form-inline" action="{{route('search.users')}}" method="GET">
            <input  autocomplete="off" list="lista" type="text" name="users" onkeypress="return check(event)" id="users" class="form-control" placeholder=" Buscar Vendedor" data-id="{{ old('id') }}">
            <datalist id="lista">
                @foreach($users3 as $user)
                <option value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>
                @endforeach
            </datalist>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
      </nav>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center" id="" width="100%" cellspacing="0" style="font-size: 12px;">
				<thead>
					<tr>
						<th>Vendedor</th>
						<th>Oficina</th>
						<th>Último pago</th>
						<th>Porcentaje (%)</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)

						@if($user->payments()->count() > 0)
						<tr>
							<td>{{ $user->nombre_completo }}</td>
							<td>{{$user->office->office_address}}</td>
							<td class="text-success">TODO: Fecha del ultiumo pago a este vendedor</td>
							<td class="text-warning">{{$user->profit_percentage.'%'}}</td>
							<?php $total_all = PaymentsController::policies_not_paid_price($user->id)?>
							<td class="text-success text-center">
								<a href="/admin/index-payment/{{$user->id}}" class="btn btn-primary">Ver</a>
							</td>
						</tr>

						<div class="modal fade" id="{{"openForm-".$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Seguro que desea efectuar esta operacion?</h5>
										<button class="close" type="button" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">Seleccione "continuar" para efectuar la operacion, seleccione "cancelar" para cancelar la operacion</div>
									<div class="modal-footer">
										<form action="/admin/register-payment/{{$user->id}}" method="POST"
											class="d-inline-block">
											@csrf
											<input type="hidden" value="{{$user->name.' '.$user->lastname}}" name="name">
											<input type="hidden" value="{{$user->office->office_address}}" name="office">
											<input type="hidden" value="{{$user->id}}" name="user_id">
											<input type="hidden" value="{{PaymentsController::policies_not_paid_price($user->id)}}" name="total">
											<input type="hidden" value="{{$user->profit_percentage}}" name="profit_percentage">
											<input type="hidden" value="{{PaymentsController::profit_percentage($total_all, $user->profit_percentage)}}" name="total_payment">

											<button class="btn btn-secondary" type="button" data-dismiss="modal">cancelar</button>
											<button type="submit" class="btn btn-primary">continuar</button>
										</form>
									</div>
								</div>
							</div>
						</div> 
						@endif
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

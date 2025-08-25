@extends('layouts.app')
<?php use \App\Http\Controllers\PaymentsController; ?>

@section('module')
<!--<a class="btn btn-light shadow" href="{{ route('index.payments')}}">Ver Consultas de pago</a>-->

<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Correlativo</h6>
        @if($not_paid->count() > 0)
            <a class="btn btn-info float-right" target="blank" href="{{ route('user.payments.export')}}">Exportar</a>
        @endif
	</div>
	<div class="card-body">
		<div class="table-responsive">
				@csrf
				<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
						<th>Num. Afiliación</th>
						<th>Beneficiario</th>
						<th>Vehículo</th>
						<th>Fecha de Emisión</th>
						<th>Precio</th>
						<th>Telefono</th>
					</tr>
				</thead>
				<tbody>
					@foreach($not_paid as $policy)
					<tr>
                        <td>{{$policy->id}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
						<input type="hidden" value="{{$policy->user->office->office_address}}" name="office">
						<input type="hidden" value="{{$policy->user->profit_percentage}}" name="profit_percentage">
						<td>{{number_format($policy->total_premium)}} €</td>
						<td>{{$policy->client_phone}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			    <div class="modal fade" id="reporte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"> <strong class="text-danger">Reportar Polizas</strong></h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Al reportar las polizas actuales aparecera el boton de <strong>exportar</strong></div>
                        <div class="modal-footer">
                            <form action="/user/reportpayment" method="POST">
                                @csrf
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Continuar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
	    </div>
	</div>
</div>

@endsection

@section('scripts')


<script>
    $(document).ready(function(){
        var isMobile = window.innerWidth < 768;

        if (isMobile) {
            $('#modal-mobil').modal('toggle');
        } else {
            $('#modal-pc').modal('toggle');
        }
    });
    </script>

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
        var k = Math.pow(50, prec);
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

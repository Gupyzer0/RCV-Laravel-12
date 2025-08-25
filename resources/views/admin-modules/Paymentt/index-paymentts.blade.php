@extends('layouts.admin-modules')

@section('module')

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Reporte de Pagos</h6>

	</div>

	<div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Tipo de Pago</th>
                        <th>Banco Emisor</th>
                        <th>Referencia</th>
                        <th>Cantidad de Polizas</th>
                        <th>Total Vendido</th>
                        <th>Tasa </th>
                        <th>% Vendedor</th>
                        <th>Total a Pagar</th>
                        <th>Total Recibido</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagos as $pago)
                    @if($pago->total_policies > 0)
                        <tr>
                            <td>{{$pago->user->name.' '.$pago->user->lastname }}</td>
                            <td>   @if ($pago->type_payment == 'cash')
                                Efectivo @if($pago->currency == 'dolar') Dolar @else Bolivar @endif
                                @elseif ($pago->type_payment == 'transfer')
                               Transferencia (Bs)
                                @elseif ($pago->type_payment == 'pm')
                                Pago Movil (Bs)
                                @endif</td>
                            <td>{{$pago->bank->name ?? '-'}}</td>
                            <td>{{$pago->referenceNumber}}</td>
                            <td>{{$pago->total_policies}}</td>
                            {{-- <td>{{number_format((($pago->total * $pago->tasae) / $pago->tasad),2)}} $</td> --}}
                            <td>{{($pago->total * $pago->tasae)/$pago->tasad}} $</td>
                            <td>{{$pago->tasad}} Bs</td>
                            <td>
                                @php
                                $total = $pago->total * $pago->profit_percentage;
                                @endphp
                                @if($pago->currency == 'dolar')
                                {{($total) /100}} $
                                @else
                                {{($total /100)* $pago->tasae}} Bs
                                @endif

                            </td>
                            <td>

                                @if($pago->currency == 'dolar')
                                {{$pago->total - ($total / 100)}} $
                                @else
                                {{($total / 100)* $pago->tasae}} Bs
                                @endif
                            </td>

                            <td>
                                {{number_format($pago->amount, 2)}}
                                @if($pago->currency == 'dolar')
                                 $
                                @else
                                 Bs
                                @endif
                            </td>




                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('report.export', $pago->id) }}" target="_blank">Exportar Reportadas</a>
                                        <a class="dropdown-item" href="/admin/index-payment/not-paid/{{ $pago->user->id }}" target="_blank">Ver Todas Vendidas</a>
                                        <a class="dropdown-item" href="{{ route('show.paymentss', $pago->id) }}">Pagar Reportadas</a>

                                    </div>
                                </div>


                        </tr>
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


</script>
@endsection

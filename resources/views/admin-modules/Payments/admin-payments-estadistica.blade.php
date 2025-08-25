@extends('layouts.admin-modules')
<?php use \App\Http\Controllers\PaymentsController; ?>

@section('module')
<a class="btn btn-success shadow" href="{{ route('index.payments')}}">Ver pagados</a>
<div class="card shadow mb-2">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">No pagados</h6>
	</div>


	<div class="card-body">
		<div class="table-responsive">
            @php
            $months = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ];
        @endphp
			<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0" style="font-size: 12px;">
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Estado</th>
                        <th>Mes</th>
                        <th>Cantidad de Polizas</th>
                        <th>Total Vendido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary as $item)
                        @if($item->total_premium_sum > 50)

                            <tr>
                                <td>{{ $item->user->name.' '.$item->user->lastname }}</td>
                                <td>{{$item->user->office->estado->estado}}</td>
                                <td>{{ $months[$item->month] }}</td>
                                <td>{{ $item->policy_count }}</td>
                                <td>{{ number_format($item->total_premium_sum, 0, ',', '.') }} €</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="py-3">
                <h6 class="m-0 font-weight-bold text-info float-right">
                    Total: {{ number_format($totalSum, 0, ',', '.') }} $
                </h6>
            </div>
		</div>
	</div>
</div>

@endsection

@section('scripts')


@endsection

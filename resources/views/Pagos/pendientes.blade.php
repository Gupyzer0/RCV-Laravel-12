@extends('layouts.app')
@section('module')
<a class="btn btn-success shadow mb-2" href="{{ route('index.payments')}}">Ver pagados</a>

<div class="card shadow mb-2">

	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary">Pagos pendientes</h6>
	</div>

    <nav class="navbar navbar-light" style="text-align: right">
        {{-- filtros --}}
        @hasrole('administrador')
        <form class="form-inline" action="{{route('pagos.pendientes')}}" method="GET">
            <select class="form-control mr-sm-2" name="filtro_moderador">
                <option value="">Supervisor</option>
                @foreach ($moderadores as $moderador)
                    <option value="{{ $moderador->id }}" {{ $filtro_moderador == $moderador->id ? 'selected':'' }} >{{ $moderador->nombre_completo }}</option>
                @endforeach
            </select>
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
        @endhasrole
        {{-- /filtros --}}
        <a class="btn btn-success float-right" style="color: white;" data-toggle="modal" data-target="{{'#'."reporte"}}">Hacer Cierre</a>
    </nav>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center" width="100%" cellspacing="0" style="font-size: 12px;">
				<thead>
					<tr>
					    <th>Supervisor</th>
						<th>Vendedor</th>
						<th>Último pago</th>
						<th>Pólizas Vendidas</th>
						<th>Pólizas Reportadas</th>
						<th>Pólizas Nulas</th>
						<th>Total Vendido </th>
						<th>Comisión</th>
						<th>Total a Recibir</th>
						<th>Efectuar Pago</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
                        <tr>
                            <td>{{ $user->moderator->nombre_completo ?? '' }}</td>
                            <td>{{ $user->nombre_completo }}</td>
                            {{-- Último pago --}}
                            <td>
                                {{ $user->ultimo_pago_recibido() ?? 'No se ha efectuado el primer pago' }}
                            </td>
                            
                            {{-- Resumen de pólizas --}}
                            <td>{{ $user->polizas_vendidas_sin_pagar()->count() }}</td> {{-- Vendidas sin pagar --}}
                            <td>{{ $user->polizas_reportadas_sin_pagar()->count() }}</td> {{-- Reportadas sin pagar --}}
                            <td>{{ $user->polizas_anuladas()->count() }}</td> {{-- Polizas anuladas --}}

                            {{-- Comisiones --}}
                            <td>{{ number_format($user->total_polizas_sin_pagar(),2) }} €</td>
                            <td>
                                Total: {{ number_format($user->comision_polizas_sin_pagar()['comision_total'],2) }} € <br>
                                Vendedor: {{ number_format($user->comision_polizas_sin_pagar()['comision_usuario'],2) }} € <br>
                                Supervisor: {{ number_format($user->comision_polizas_sin_pagar()['comision_moderador'],2) }} € 
                            </td> 
                            <td class="text-success">{{ number_format($user->total_a_recibir(),2) }} €</td>

                            {{-- Acciones --}}
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu">
                                        <form action="{{ route('pagos.pendientes.cierre-por-usuario', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="dropdown-item" href="{{ route('pagos.pendientes.cierre-por-usuario', $user) }}">Hacer Cierre</button>
                                        </form>
                                        @if($user->polizas_vendidas_sin_pagar()->count() > 0)
                                            <a class="dropdown-item" href="{{ route('pagos.pendientes.pdf', $user) }}" target="_blank">Exportar</a>
                                            <a class="dropdown-item" href="{{ route('pagos.pendientes.por-usuario', $user) }}" target="_blank">Pagar</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            {{-- /Acciones --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
	    </div>
    </div>

    <div class="modal fade" id="reporte" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> <strong class="text-danger">Realizar Cierre</strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Al Realizar el cierre se mostraran todas las polizas realizadas hasta hoy </div>
                <div class="modal-footer">
                    <form action="{{ route('pagos.pendientes.cierre') }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Continuar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

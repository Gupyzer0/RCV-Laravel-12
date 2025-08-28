@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Siniestros</h6>
		<a class="btn btn-success float-right" href="{{route('register.siniestro')}}">Nuevo Reporte</a>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>N° Poliza</th>
						<th>Vehiculo</th>
						<th>Fecha Siniestro</th>
						<th>Estatus</th>
                        <th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($accidents as $accident)
					<tr>
						<td>{{ $accident->id }}</td>
						<td>{{ $accident->policy->id }}</td>
						<td>{{ $accident->policy->vehicle_brand.', '.$accident->policy->vehicle_model }}</td>
						<td>{{ $accident->accident_date }}</td>
						<td>{{ $accident->status }}</td>
						<td>
							<a href="{{ route('show.siniestros', $accident->id) }}" class="btn btn-info">Ver</a>
							<a href="{{ route('admin.edit.siniestro', $accident->id) }}" class="btn btn-warning">Editar</a>
							
						</td>
					</tr>
					@endforeach
					
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

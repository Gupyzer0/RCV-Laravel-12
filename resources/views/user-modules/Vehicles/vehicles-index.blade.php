@extends('layouts.app')

@section('module')
<a class="btn btn-light shadow" href="{{ route('user.index.vehicle.types') }}">Ver tipos</a>
<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Vehiculos</h6>
		<a class="btn btn-success float-right" href="{{ route('user.register.vehicle')}}">Registrar Vehiculo</a>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Marca</th>
						<th>Modelo</th>
					</tr>
				</thead>
				<tbody>
					@foreach($vehicles as $vehicle)
					@if(!$vehicle->deleted_at)
					<tr>
						
						<td>{{$vehicle->brand}}</td>
						<td>{{$vehicle->model}}</td>
					</tr>
			  @endif
			  @endforeach
			</tbody>

		</table>
	</div>
</div>
</div>


@endsection



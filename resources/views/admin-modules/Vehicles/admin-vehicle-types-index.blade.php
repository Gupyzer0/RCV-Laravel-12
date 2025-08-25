@extends('layouts.admin-modules')

@section('module')
{{-- <a class="btn btn-light shadow" href="{{ route('index.vehicles')}}">Ver vehículos</a> --}}
<a class="btn btn-light shadow" href="{{ route('index.vehicle.classes')}}">Ver Clases</a>
<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Uso de Vehículo</h6>
		<a class="btn btn-success float-right" href="{{ route('register.type') }}">Registrar Uso</a>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Uso</th>
						<th>Acciones</th>	    
					</tr>
				</thead>
				<tbody>
					@foreach($vehicle_types as $type)
					@if(!$type->deleted_at)
					<tr>
						<td>{{$type->type}}</td>
						<td class="text-center">	
							<a href="/admin/edit-type/{{$type->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;"><i class="fas fa-edit"></i></a>
							{{-- Button to open the modal --}}
							<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$type->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span>
						</td>
					</tr>
					@endif
					<div class="modal fade" id="deleteModal-{{$type->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">eliminar</strong> este Uso de vehículo?</h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">eliminar</span> este uso de vehículo</div>
								<div class="modal-footer">
									<form action="/admin/delete-type/{{$type->id}}" method="POST">
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
		</div>
	</div>
</div>				
@endsection
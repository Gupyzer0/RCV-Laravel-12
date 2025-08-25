@extends('layouts.admin-modules')
@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Inventario</h6>

	<ul class="nav nav-pills card-header-pills">
		<span class="btn btn-success float-right" id="openModal" data-toggle="modal" data-target="{{'#'."modal-register"}}">Agregar Nuevo</span>
		<li class="nav-item ml-auto"><a href="{{url()->previous()}}" class="nav-link bg-danger active">X</a></li>
    </ul>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>N°</th>
						<th>Descripción</th>
						<th>Marca y Modelo</th>
						<th>Serial</th>
						<th>Observación</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>

						@foreach($inventory as $inventory)
					<tr>

						<td>{{$inventory->id}}</td>
						<td>{{$inventory->descripcion}}</td>
						<td>{{$inventory->marca. ', '.$inventory->modelo}}</td>
						<td>{{$inventory->serial}}</td>
						<td>{{$inventory->status}}</td>
						<td class="justify-content-center">
							<a href="/admin/edit-inventory/{{$inventory->id}}" class="btn bg-transparent text-primary action-button pr-3 mt-1" style="width: 5px;"><i class="fas fa-edit"></i></a>
							<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$inventory->id}}" style="width: 5px;"><i class="fas fa-trash-alt">Eliminar</i></span>
						</td>
					</tr>
					<!--Modal Eliminar-->
					<div class="modal fade" id="deleteModal-{{$inventory->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
									<form action="/admin/delete-inventory/{{$inventory->id}}" method="POST">
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

		<!--Modal Crear-->
<div class="modal fade" id="modal-register" tabindex="-1" role="dialog" aria-labelledby="modal-renew-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
    	</div>


    <div class="modal-footer">
	  <form action="{{ route('register.inventory.submit')}}" method="POST" id="form_inventory">
			@csrf
			<h5>Datos del Equipo</h5>
			<div class="form-row">

			<div class="form-group col-md-4">
					<label for="descripcion">Descripcion</label>
					<input autocomplete="off" type="text" name="descripcion" id="descripcion" class="form-control" placeholder="..." value="{{ old('descripcion') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="marca">Marca</label>
					<input autocomplete="off" type="text" name="marca" id="marca" class="form-control" placeholder="..." value="{{ old('marca') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="modelo">Modelo</label>
					<input autocomplete="off" type="text" name="modelo" id="modelo" class="form-control" placeholder="..." value="{{ old('modelo') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="serial">Serial</label>
					<input autocomplete="off" type="text" name="serial" id="serial" class="form-control" placeholder="..." value="{{ old('serial') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="status">Observación</label>
					<input autocomplete="off" type="text" name="status" id="status" class="form-control" placeholder="..." value="{{ old('status') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="vendedor">Vendedor</label>
					<select id="username" name="username" class="form-control">
					<option>---</option>
						@foreach($users as $user)
						<option value="{{$user->id}}">{{$user->name}}</option>
						@endforeach
					</select>

				</div>


			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Guardar</button>
		</form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="modal-renew-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
    	</div>


    <div class="modal-footer">
	  <form action="{{ route('register.inventory.submit')}}" method="POST" id="form_inventory">
			@csrf
			<h5>Datos del Equipo</h5>
			<div class="form-row">

			<div class="form-group col-md-4">
					<label for="descripcion">Descripcion</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="descripcion" id="descripcion" class="form-control" placeholder="..." value="{{ old('descripcion') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="marca">Marca</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="marca" id="marca" class="form-control" placeholder="..." value="{{ old('marca') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="modelo">Modelo</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="modelo" id="modelo" class="form-control" placeholder="..." value="{{ old('modelo') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="serial">Serial</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="serial" id="serial" class="form-control" placeholder="..." value="{{ old('serial') }}">
				</div>

				<div class="form-group col-md-4">
					<label for="status">Observación</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="200" name="status" id="status" class="form-control" placeholder="..." value="{{ old('status') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="vendedor">Vendedor</label>
					<select id="username" name="username" class="form-control">
					<option>---</option>
						@foreach($users as $user)
						<option value="{{$user->id}}">{{$user->name}}</option>
						@endforeach
					</select>

				</div>


			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Guardar</button>
		</form>
      </div>
<!-- -->

@endsection

@extends('layouts.admin-modules')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Inventario</h6>
		<a href="{{route('index.inventory')}}" class="float-right btn btn-danger text-white">X</a>

	</div>
	<div class="card-body">

		<form action="/admin/edit-inventory/{{$id}}" method="POST">
			@csrf
            <input type="hidden" name="_method" value="PUT">
			<h5>Datos del Equipo</h5>
			<div class="form-row">

				<div class="form-group col-md-4">
					<label for="descripcion">Descripcion</label>
					<input required autocomplete="off" type="text" minlength="1" maxlength="25" name="descripcion" id="descripcion" class="form-control" placeholder="..." value="{{$inventory->descripcion }}">

				</div>

				<div class="form-group col-md-4">
					<label for="marca">Marca</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="marca" id="marca" class="form-control" placeholder="..." value="{{ $inventory->marca }}">
				</div>

				<div class="form-group col-md-4">
					<label for="modelo">Modelo</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="modelo" id="modelo" class="form-control" placeholder="..." value="{{ $inventory->modelo }}">
				</div>

				<div class="form-group col-md-4">
					<label for="serial">Serial</label>
					<input autocomplete="off" type="text" minlength="1" maxlength="25" name="serial" id="serial" class="form-control" placeholder="..." value="{{ $inventory->serial }}">
				</div>

				<div class="form-group col-md-4">
					<label for="status">Observación del Equipo</label>
					<input required autocomplete="off" type="text" minlength="1" maxlength="200" name="status" id="status" class="form-control" placeholder="..." value="{{ $inventory->status }}">
				</div>
				<div class="form-group col-md-4">
					<label for="vendedor">Vendedor</label>
					<select id="username" name="username" class="form-control" required>
					<option value="{{$inventory->user->id}}">{{$inventory->user->name.' '.$inventory->user->lastname}}</option>
						@foreach($users as $user)
						<option value="{{$user->id}}"">{{$user->name.' '.$user->lastname}}</option>
						@endforeach
					</select>

				</div>
            </div>

			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Guardar</button>
		</form>
	</div>
</div>

@endsection

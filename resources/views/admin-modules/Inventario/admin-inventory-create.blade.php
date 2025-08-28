@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Inventario</h6>
		<a href="{{route('index.inventory')}}" class="float-right btn btn-danger text-white">X</a>

	</div>
	<div class="card-body">

		<form action="{{ route('register.inventory.submit')}}" method="POST">
			@csrf
			<h5>Datos del Equipo</h5>
			<div class="form-row">

				<div class="form-group col-md-4">
					<label for="descripcion">Descripcion</label>
					<input required autocomplete="off" type="text" minlength="1" maxlength="25" name="descripcion" id="descripcion" class="form-control" placeholder="..." value="{{ old('descripcion') }}">

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
					<label for="status">Observación del Equipo</label>
					<input required autocomplete="off" type="text" minlength="1" maxlength="200" name="status" id="status" class="form-control" placeholder="..." value="{{ old('status') }}">
				</div>
				<div class="form-group col-md-4">
					<label for="vendedor">Vendedor</label>
					<select id="username" name="username" class="form-control" required>
					<option></option>
						@foreach($users as $user)
						<option value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>
						@endforeach
					</select>

				</div>

			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Guardar</button>
		</form>
	</div>
</div>

@endsection

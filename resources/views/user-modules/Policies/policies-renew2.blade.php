@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Editar Póliza</h6>
		<a href="{{route('user.index.policies')}}" class="float-right btn btn-danger text-white">X</a>

	</div>
	<div class="card-body">
		<form action="/user/renew-policy2/{{$id}}" method="POST" id="form_policies">
			@csrf
			<input type="hidden" name="_method" value="PUT">


			<h3>Datos del cliente</h3>
			{{-- CONTRATANTE --}}
			<h5>Tomador</h5>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="client_name_contractor">Nombre</label>
					<input autocomplete="off" type="text" name="client_name_contractor" id="client_name_contractor" class="form-control @error('client_name_contractor') is-invalid @enderror" placeholder="..." value="{{ $policy->client_name_contractor }}">

					@error('client_name_contractor')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-4">
					<label for="client_lastname_contractor">Apellido</label>
					<input autocomplete="off" type="text" name="client_lastname_contractor" id="client_lastname_contractor" class="form-control @error('client_lastname_contractor') is-invalid @enderror" placeholder="..." value="{{$policy->client_lastname_contractor}}">

					@error('client_lastname_contractor')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-4">
					<label for="ci_contractor">Documento de identificación</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="id_type_contractor" class="form-control @error('id_type_contractor')is-invalid @enderror custom-select" id="id_type_contractor" required>
								<option value="{{$identification_contractor[2]}}">{{$identification_contractor[2]}}</option>
								<option value="V-">V</option>
								<option value="E-">E</option>
								<option value="I-">I</option>
								<option value="J-">J</option>
								<option value="G-">G</option>
							</select>
						</div>

						@error('id_type_contractor')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

						<input autocomplete="off" type="text" class="form-control @error('client_ci_contractor') is-invalid @enderror" name="client_ci_contractor" id="ci_contractor" placeholder="..." value="{{ $identification_contractor[1] }}">

						@error('client_ci_contractor')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>
			</div>
			{{-- FIN DATOS DEL CONTRATANTE --}}

			{{-- BENEFICIARIO --}}
			<h5>Asegurado</h5>
			<div class="form-row border-bottom border-dark">
				<div class="form-group col-md-6">
					<label for="client_name">Nombre</label>
					<input autocomplete="off" type="text" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" placeholder="..." value="{{ $policy->client_name }}">

					@error('client_name')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-6">
					<label for="client_lastname">Apellido</label>
					<input autocomplete="off" type="text" name="client_lastname" id="client_lastname" class="form-control @error('client_lastname') is-invalid @enderror" placeholder="..." value="{{ $policy->client_lastname }}">

					@error('client_lastname')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-3">
					<label for="ci">Documento de identificación</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="id_type" class="form-control custom-select @error('id_type') is-invalid @enderror" id="id_type">
								<option value="{{$identification[2]}}">{{$identification[2]}}</option>
								<option value="V-">V</option>
								<option value="E-">E</option>
								<option value="I-">I</option>
								<option value="J-">J</option>
								<option value="G-">G</option>
							</select>
						</div>

						@error('id_type')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
						<input autocomplete="off" type="text" class="form-control @error('client_ci') is-invalid @enderror" name="client_ci" id="ci" placeholder="..." value="{{$identification[1]}}">

						@error('client_ci')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>

                <div class="form-group col-md-3">
					<label for="fecha_n">Fecha de Nacimiento</label>
					<input required type="date" name="fecha_n" id="fecha_n" class="form-control @error('fecha_n') is-invalid @enderror">

					@error('fecha_n')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname">Estado Civil</label>
					<select name="estadocivil" id="estadocivil" class="form-control @error('estadocivil') is-invalid @enderror custom-select">
                        <option value="Soltero/a">Soltero/a</option>
                        <option value="Casado/a">Casado/a</option>
                        <option value="Divorciado/a">Divorciado/a</option>
                        <option value="Viudo/a">Viudo/a</option>
                    </select>

					@error('estadocivil')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname">Genero</label>
					<select name="genero" id="genero" class="form-control @error('genero') is-invalid @enderror custom-select" >
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
					@error('client_lastname')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-4">
					<label for="client_phone" class="text-md-right">Número de teléfono</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="sp_prefix" class="form-control custom-select @error('sp_prefix') is-invalid @enderror" id="number_code">
								<option value="{{$client_phone[0]}}-">{{$client_phone[0]}}</option>

								<option value="412-">412</option>
								<option value="416-">416</option>
								<option value="426-">426</option>
								<option value="414-">414</option>
								<option value="424-">424</option>
							</select>
						</div>

						@error('sp_prefix')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

						<input type="text" name="client_phone" id="client_phone" value="{{$client_phone[1]}}"class="form-control @error('client_phone') is-invalid @enderror" placeholder="...">

						@error('client_phone')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>

				<div class="form-group col-md-4">
					<label for="client_email">Correo Electronico</label>
					<input autocomplete="off" type="email" class="form-control @error('client_email') is-invalid @enderror" name="client_email" id="client_email" placeholder="..." value="{{ $policy->client_email }}">

					@error('client_email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				{{-- Direccion del cliente --}}
				<div class="form-group col-md-4">
					<label for="estado">Estado</label>
					<select name="estado" id="estado" class="form-control @error('estado') is-invalid @enderror custom-select">
						<option value="{{$policy->id_estado}}">{{$policy->estado->estado}}</option>
						@foreach($estados as $estado)
						<option value="{{$estado->id_estado}}">{{$estado->estado}}</option>
						@endforeach
					</select>

					@error('estado')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-4">
					<label for="municipio">Municipio</label>
					<select name="municipio" id="municipio" class="form-control @error('municipio') is-invalid @enderror custom-select">
						<option value="{{$policy->id_municipio}}">{{$policy->municipio->municipio}}</option>
					</select>

					@error('municipio')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-4">
					<label for="parroquia">Parroquia</label>
					<select name="parroquia" id="parroquia" class="form-control @error('parroquia') is-invalid @enderror custom-select">
						<option value="{{$policy->id_parroquia}}">{{$policy->parroquia->parroquia}}</option>
					</select>

					@error('parroquia')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-12">
					<label for="client_address">Dirección</label>
					<textarea name="client_address" id="client_address" class="form-control @error('client_address') is-invalid @enderror">{{$policy->client_address}}</textarea>

					@error('client_address')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
				{{-- FIN DE DIRECCION DEL BENEFICIARIO --}}
			</div>
			{{-- FIN DATOS DEL CLIENTE --}}

			<h3 class="mt-4">Datos del vehiculo</h3>

			<div class="form-row border-bottom border-dark">
				<div class="form-group col-md-6">
					<label for="brand">Marca</label>
					<input autocomplete="off" type="text" name="vehicleBrand" id="brand" class="form-control @error('vehicleBrand') is-invalid @enderror" placeholder="..." value="{{ $policy->vehicle_brand }}">


					@error('vehicleBrand')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="model">Modelo</label>
					<input autocomplete="off" type="text" name="vehicleModel" id="model" class="form-control @error('vehicleModel') is-invalid @enderror" placeholder="..." value="{{ $policy->vehicle_model }}">

					@error('vehicleModel')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_type">Tipo</label>

					<input autocomplete="off" type="text" name="vehicle_type" id="vehicle_type" class="form-control @error('vehicle_type') is-invalid @enderror" placeholder="..." value="{{ $policy->vehicle_type }}">

					@error('vehicle_type')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_year">Año</label>
					<input autocomplete="off" type="number" name="vehicle_year" id="vehicle_year" class="form-control @error('vehicle_year') is-invalid @enderror" placeholder="..." value="{{ $policy->vehicle_year }}">

					@error('vehicle_year')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_color">Color</label>
					<input autocomplete="off" type="text" name="vehicle_color" id="vehicle_color" class="form-control @error('vehicle_color') is-invalid @enderror" placeholder="..." value="{{ $policy->vehicle_color }}">

					@error('vehicle_color')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror


					<label for="used_for">Uso del vehiculo</label>
					<select id="used_for" name="used_for" class="form-control @error('used_for') is-invalid @enderror custom-select">
						<option value="{{$policy->used_for}}">{{$policy->used_for}}</option>
                    	@foreach($vehicle_type as $vehicle_type)
						<option value="{{$vehicle_type->type}}">{{$vehicle_type->type}}</option>
						@endforeach
					</select>

					@error('used_for')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-6">

					<label for="vehicle_bodywork_serial">Serial de carroceria</label>
					<input autocomplete="off" type="text" name="vehicle_bodywork_serial" id="vehicle_bodywork_serial" class="form-control @error('vehicle_bodywork_serial') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ $policy->vehicle_bodywork_serial }}">

					@error('vehicle_bodywork_serial')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_motor_serial">Serial del motor</label>
					<input autocomplete="off" type="text" name="vehicle_motor_serial" id="vehicle_motor_serial" class="form-control @error('vehicle_motor_serial') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ $policy->vehicle_motor_serial }}">

					@error('vehicle_motor_serial')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					 @if($user1->type == 3)

                	<label for="vehicle_certificate_number">Cantidad de Puestos</label>
					<input autocomplete="off" type="number" name="vehicle_certificate_number" id="vehicle_certificate_number" class="form-control @error('vehicle_certificate_number') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ $policy->vehicle_certificate_number }}">
					 @else
 				 	<label for="vehicle_certificate_number">Numero de certificado</label>
					<input autocomplete="off" type="text" name="vehicle_certificate_number" id="vehicle_certificate_number" class="form-control @error('vehicle_certificate_number') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ $policy->vehicle_certificate_number }}">


					 @endif


					@error('vehicle_certificate_number')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_weight">Peso del vehiculo</label>
					<div class="input-group">
						<input autocomplete="off" type="number" name="vehicle_weight" id="vehicle_weight" class="form-control @error('vehicle_weight') is-invalid @enderror" placeholder="Kg" value="{{ $weight_num[0]}}">
						<div class="input-group-append">
							<span class="input-group-text">Kg</span>
						</div>
					</div>

					@error('vehicle_weight')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_registration">Matricula</label>
					<input autocomplete="off" type="text" name="vehicle_registration" id="vehicle_registration" class="form-control @error('vehicle_registration') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ $policy->vehicle_registration }}">

					@error('vehicle_registration')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

				</div>

			</div>

			<h3 class="mt-4">Poliza</h3>

			<div class="form-group">
				<label for="vehicle_class">Clase de vehículo</label>
				<select name="vehicle_class" id="vehicle_class" class="form-control @error('vehicle_class') is-invalid @enderror custom-select" >
					<option>Seleccione</option>
					@foreach($vehicle_classes as $class)
					<option value="{{$class->id}}">{{$class->class}}</option>
					@endforeach
				</select>

				@error('vehicle_class')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				<label for="price">Seleccionar poliza</label>
				<select name="price" class="form-control @error('price') is-invalid @enderror custom-select" id="price"  >
					<option value="{{$policy->price_id}}"></option>
				</select>
			</div>

			@error('price')
			<span class="invalid-feedback" role="alert">
				<strong>{{ $message }}</strong>
			</span>
			@enderror


			<div class="row mb-4" id="quick_view">
				{{-- price view ajax request --}}
			</div>

			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Actualizar Precio</button>
		</form>
	</div>
</div>

@endsection


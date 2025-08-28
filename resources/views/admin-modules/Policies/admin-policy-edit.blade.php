@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Editar Póliza</h6>
		<a href="{{route('index.policies')}}" class="float-right btn btn-danger text-white">X</a>

	</div>
	<div class="card-body">
		<form action="/admin/edit-policy/{{$id}}" method="POST" id="form_policies">
			@csrf
			<input type="hidden" name="_method" value="PUT">



			<div class="form-row">

			   <div class="form-group col-md-4">
					<Label>Vendedor</Label>
					  <select name="username" id="username" class="form-control @error('username') is-invalid @enderror custom-select">
					@if(isset($policy->admin_id))
						<option value="{{$policy->admin_id}}">Administrador</option>
					@else
						<option value="{{$policy->user_id}}">{{$policy->user->name.' '.$policy->user->lastname}}</option>
						@endif
						@foreach($user as $user)
						<option value="{{$user->id}}">{{$user->name.' '.$user->lastname}}</option>
						@endforeach
					</select>
				</div>

                <div class="form-group col-md-4">
					<Label>Fecha de Emisión</Label>
					<input type="date" name="created_at" id="created_at" class="form-control" value="{{\Carbon\Carbon::parse($policy->created_at)->format('Y-m-d')}}">
				</div>

				<div class="form-group col-md-4">
					<Label>Fecha de Vencimiento</Label>
					<input type="date" name="expiring_date" id="expiring_date" class="form-control" value="{{$policy->expiring_date}}">
				</div>
			</div>
			<h3>Datos del cliente</h3>
			{{-- CONTRATANTE --}}
			<h5 style="color:black;">Asegurado</h5>
			<div class="form-row border-bottom border-dark">

                <div class="form-group col-md-3">
					<label for="ci_contractor">Documento de identificación</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="id_type_contractor" class="form-control @error('id_type_contractor')is-invalid @enderror custom-select" id="id_type_contractor">
								<option value="{{$identification_contractor[2]}}">{{$identification_contractor[2]}}</option>
								<option value="V-" {{old('id_type_contractor') == 'V-' ? 'selected' : ''}}>V</option>
								<option value="E-" {{old('id_type_contractor') == 'E-' ? 'selected' : ''}}>E</option>
								<option value="I-" {{old('id_type_contractor') == 'I-' ? 'selected' : ''}}>I</option>
								<option value="J-" {{old('id_type_contractor') == 'J-' ? 'selected' : ''}}>J</option>
								<option value="G-" {{old('id_type_contractor') == 'G-' ? 'selected' : ''}}>G</option>
							</select>
						</div>

						@error('id_type_contractor')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

						<input  autocomplete="off" type="text" class="form-control @error('client_ci_contractor') is-invalid @enderror" name="client_ci_contractor" id="ci_contractor" value="{{ $identification_contractor[1] }}">


						@error('client_ci_contractor')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

					</div>
				</div>

				<div class="form-group col-md-3">
					<label for="client_name_contractor">Nombre(s)</label>
					<input  autocomplete="off" type="text" maxlength="40" name="client_name_contractor" id="client_name_contractor" class="form-control @error('client_name_contractor') is-invalid @enderror"  value="{{ $policy->client_name_contractor }}">

					@error('client_name_contractor')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname_contractor">Apellido(s)</label>
					<input autocomplete="off" type="text" name="client_lastname_contractor" id="client_lastname_contractor" class="form-control @error('client_lastname_contractor') is-invalid @enderror" value="{{$policy->client_lastname_contractor}}">

					@error('client_lastname_contractor')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

			</div>
			{{-- FIN DATOS DEL CONTRATANTE --}}

			{{-- BENEFICIARIO --}}
			<h5 style="color:black; padding-top: 10px;" >Tomador</h5>
			{{-- COPIAR DATOS --}}


			{{-- FIN COPIAR DATOS --}}

			<div class="form-row border-bottom border-dark">
                <div class="form-group col-md-3">
					<label for="ci">Documento de identificación</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="id_type" class="form-control @error('id_type')
							is-invalid @enderror custom-select" id="id_type">
                            <option value="{{$identification[2]}}">{{$identification[2]}}</option>
								<option value="V-" {{old('id_type') == 'V-' ? 'selected' : ''}}>V</option>
								<option value="E-" {{old('id_type') == 'E-' ? 'selected' : ''}}>E</option>
								<option value="I-" {{old('id_type') == 'I-' ? 'selected' : ''}}>I</option>
								<option value="J-" {{old('id_type') == 'J-' ? 'selected' : ''}}>J</option>
								<option value="G-" {{old('id_type') == 'G-' ? 'selected' : ''}}>G</option>
							</select>
						</div>

						@error('id_type')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

						<input  autocomplete="off" type="text" class="form-control @error('client_ci') is-invalid @enderror" name="client_ci" id="ci"  value="{{$identification[1]}}">

						@error('client_ci')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>

				<div class="form-group col-md-3">
					<label for="client_name">Nombre(s)</label>
					<input  autocomplete="off" type="text" maxlength="40" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ $policy->client_name }}">

					@error('client_name')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname">Apellido(s)</label>
					<input autocomplete="off" type="text" name="client_lastname" id="client_lastname" class="form-control @error('client_lastname') is-invalid @enderror" value="{{ $policy->client_lastname }}">

					@error('client_lastname')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
                    <label for="client_email">Correo Electronico:</label>
                    <input  autocomplete="off" type="email" maxlength="40" name="client_email" id="client_email" class="form-control @error('client_email') is-invalid @enderror" placeholder="..." value="{{ $policy->client_email }}">

                    @error('client_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

				<div class="form-group col-md-3">
					<label for="fecha_n">Fecha de Nacimiento</label>
					<input  type="date" name="fecha_n" id="fecha_n" class="form-control @error('fecha_n') is-invalid @enderror" value="{{ $policy->fecha_n }}">

					@error('fecha_n')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname">Estado Civil</label>
					<select name="estadocivil" id="estadocivil" class="form-control @error('estadocivil') is-invalid @enderror custom-select">
                        <option value="{{$policy->estadocivil}}">{{$policy->estadocivil}}</option>
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
                        <option value="{{$policy->genero}}">{{$policy->genero}}</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
					@error('client_lastname')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-3">
					<label for="client_phone" class="text-md-right">Número de teléfono</label>
					<div class="input-group">
						<div class="input-group-prepend">
						<select name="sp_prefix" class="form-control @error('sp_prefix') is-invalid @enderror custom-select" id="number_code">
							<option value="{{$client_phone[0]}}-">{{$client_phone[0]}}</option>

								<option value="412-" {{old('sp_prefix') == '412-' ? 'selected' : ''}}>412</option>
								<option value="416-" {{old('sp_prefix') == '416-' ? 'selected' : ''}}>416</option>
								<option value="426-" {{old('sp_prefix') == '426-' ? 'selected' : ''}}>426</option>
								<option value="414-" {{old('sp_prefix') == '414-' ? 'selected' : ''}}>414</option>
								<option value="424-" {{old('sp_prefix') == '424-' ? 'selected' : ''}}>424</option>
							</select>
						</div>

						@error('sp_prefix')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

						<input  type="text" name="client_phone" id="client_phone" value="{{$client_phone[1]}}" class="form-control @error('client_phone') is-invalid @enderror" >

						@error('client_phone')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>


				{{-- FIN DATOS DE CONTACTO E IDENTIFICACION --}}

				{{-- Direccion del cliente --}}
				<div class="form-group col-md-3">
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

				<div class="form-group col-md-3">
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

				<div class="form-group col-md-3">
					<label for="parroquia">Parroquia</label>
					<select  name="parroquia" id="parroquia" class="form-control @error('parroquia') is-invalid @enderror custom-select">
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
			</div>


			<h3 class="mt-4" style="color:black;">Datos del vehiculo</h3>

			<div class="form-row border-bottom border-dark">
				<div class="form-group col-md-6">
					<label for="brand">Marca</label>
					<input  autocomplete="off" type="text" name="vehicleBrand" onkeypress="return check(event)" id="brand" class="form-control @error('vehicleBrand') is-invalid @enderror" value="{{ $policy->vehicle_brand }}">

					@error('vehicleBrand')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="model">Modelo</label>
					<input  autocomplete="off" type="text" name="vehicleModel" onkeypress="return check(event)" maxlength="25" id="model" class="form-control @error('vehicleModel') is-invalid @enderror" value="{{ $policy->vehicle_model }}">


					@error('vehicleModel')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_type">Tipo</label>
					<input  autocomplete="off" type="text" name="vehicle_type" onkeypress="return check(event)" id="vehicle_type" class="form-control @error('vehicle_type') is-invalid @enderror" value="{{ $policy->vehicle_type }}">


					@error('vehicle_type')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror



					<label for="vehicle_year">Año</label>
					<input  autocomplete="off" type="number" name="vehicle_year" id="vehicle_year" class="form-control @error('vehicle_year') is-invalid @enderror" value="{{ $policy->vehicle_year }}">

					@error('vehicle_year')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_color">Color</label>
					<input  autocomplete="off" type="text" name="vehicle_color" id="vehicle_color" class="form-control @error('vehicle_color') is-invalid @enderror" value="{{ $policy->vehicle_color }}">

					@error('vehicle_color')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror


					<label for="used_for">Uso del vehiculo</label>
					<select id="used_for"   name="used_for" class="form-control @error('used_for') is-invalid @enderror custom-select">
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

                    <label for="trailer">Posee Trailer ?</label>
					<select id="trailer"  name="trailer" class="form-control custom-select">
                        <option value="{{$policy->trailer}}">@if($policy->trailer == 1) SI @else NO @endif</option>
						<option value="">No</option>
                        <option value="1">Si</option>
					</select>



				</div>

				<div class="form-group col-md-6">

					<label for="vehicle_bodywork_serial">Serial de carroceria</label>
					<input  autocomplete="off" type="text" name="vehicle_bodywork_serial" id="vehicle_bodywork_serial" class="form-control @error('vehicle_bodywork_serial') is-invalid @enderror" style="text-transform:uppercase;" value="{{ $policy->vehicle_bodywork_serial }}">

					@error('vehicle_bodywork_serial')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_motor_serial">Serial del motor</label>
					<input  autocomplete="off" type="text" name="vehicle_motor_serial" id="vehicle_motor_serial" class="form-control @error('vehicle_motor_serial') is-invalid @enderror" style="text-transform:uppercase;" value="{{ $policy->vehicle_motor_serial }}">

					@error('vehicle_motor_serial')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror


					<label for="vehicle_certificate_number">Cantidad de Puestos</label>
					<input  autocomplete="off" type="number" name="vehicle_certificate_number" id="vehicle_certificate_number" class="form-control @error('vehicle_certificate_number') is-invalid @enderror" style="text-transform:uppercase;" value="{{ $policy->vehicle_certificate_number }}">



					@error('vehicle_certificate_number')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_weight">Capacidad de Carga</label>
					<div class="input-group">
						<input  autocomplete="off" type="number" name="vehicle_weight" id="vehicle_weight" class="form-control @error('vehicle_weight') is-invalid @enderror" value="{{ $weight_num[0]}}">
						<div class="input-group-append">
							<span class="input-group-text">Kg</span>
						</div>
					</div>

					@error('vehicle_weight')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_registration">Placa o Matricula</label>
					<input  autocomplete="off" type="text" name="vehicle_registration" id="vehicle_registration" class="form-control @error('vehicle_registration') is-invalid @enderror" style="text-transform:uppercase;" value="{{ $policy->vehicle_registration }}">

					@error('vehicle_registration')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

                    <div>
                        <label for="image">Titulo de Propiedad</label>
                        <input type="file" name="image" id="image" class="form-control-file" accept=".jpg, .jpeg, .png">
                        @error('image')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                        <div id="preview-image">
                            <!-- Mostrar la imagen existente si está disponible -->
                            @if ($policy->image_tp)
                                <img src="{{ asset('uploads/' . $policy->id . '/' . $policy->image_tp) }}" alt="Titulo de Propiedad" style="max-width: 100px; margin-top: 10px;">
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="image1">Cedula o Rif</label>
                        <input type="file" name="image1" id="image1" class="form-control-file" accept=".jpg, .jpeg, .png">
                            @error('image1')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        <div id="preview-image1">
                            <!-- Mostrar la imagen existente si está disponible -->
                            @if ($policy->image_ci)
                                <img src="{{ asset('uploads/' . $policy->id . '/' . $policy->image_ci) }}" alt="Cedula o Rif" style="max-width: 100px; margin-top: 10px;">
                            @endif
                        </div>
                    </div>
				</div>

			</div>

			<h3 class="mt-4" style="color:black;">Poliza</h3>

			<div class="form-group">
				<label for="vehicle_class">Clase de vehículo</label>
				<select name="vehicle_class" id="vehicle_class" class="form-control @error('vehicle_class') is-invalid @enderror custom-select">
					<option value="{{$policy->class->id}}">{{$policy->class->class}}</option>
					@foreach($vehicle_classes as $class)
					<option value="{{$class->id}}">{{$class->class}}</option>
					@endforeach
				</select>

				@error('vehicle_class')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror

				<label for="price">Seleccionar Plan</label>
				<select   name="price" class="form-control @error('price') is-invalid @enderror custom-select" id="price" >
                    <option value="{{$policy->price->id}}">{{$policy->price->description}}</option>
					<option value=""> - Seleccionar - </option>
				</select>

				@error('price')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
				@enderror
			</div>

			<div class="row mb-4" id="quick_view">
				{{-- price view ajax request --}}
			</div>

			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Actualizar Poliza</button>
		</form>
	</div>
</div>

@endsection

@section('scripts')
{{-- <script>
	const form = document.getElementById('form_policies');
	let notValidated = [];
	const emailReg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	const reg = /^\d+$/;
	const numReg = /^[0-9]+$/;
	const btn = document.getElementById('submitButton');

	function removeA(arr) {
		var what, a = arguments, L = a.length, ax;
		while (L > 1 && arr.length) {
			what = a[--L];
			while ((ax= arr.indexOf(what)) !== -1) {
				arr.splice(ax, 1);
			}
		}
		return arr;
	}

	const client_name = document.getElementById('client_name');
	client_name.addEventListener('keyup', () => {
		if (client_name.value === '' || client_name.value == null) {
			client_name.classList.add('is-invalid');
			if (!notValidated.includes(1)) {
				notValidated.push(1);
			}
		} else {
			client_name.classList.remove('is-invalid');
			client_name.classList.add('is-valid');
			removeA(notValidated, 1);
		}
	});

	const client_lastname = document.getElementById('client_lastname');
	client_lastname.addEventListener('keyup', () => {
		if (client_lastname.value === "" || client_lastname.value == null) {
			client_lastname.classList.add('is-invalid');
			if (!notValidated.includes(2)) {
				notValidated.push(2);
			}
		} else {
			client_lastname.classList.remove('is-invalid');
			client_lastname.classList.add('is-valid');
			removeA(notValidated, 2);
		}
	});

	const id_type = document.getElementById('id_type');
	id_type.addEventListener('change', () => {
		if(id_type.value === ''){
			id_type.classList.add('is-invalid');
			if (!notValidated.includes(3)) {
				notValidated.push(3);
			}
		}else {
			id_type.classList.remove('is-invalid');
			id_type.classList.add('is-valid');
			removeA(notValidated, 3);
		}
	});

	const client_ci = document.getElementById('ci');
	client_ci.addEventListener('keyup', () => {
		if (reg.test(client_ci.value) == false || client_ci.value === '' || client_ci.value == null ||  client_ci.value.length > 9 || client_ci.value.length < 7) {
			client_ci.classList.add('is-invalid');
			if (!notValidated.includes(4)) {
				notValidated.push(4);
			}
		} else {
			client_ci.classList.remove('is-invalid');
			client_ci.classList.add('is-valid');
			removeA(notValidated, 4);
		}
	})

	const number_code = document.getElementById('number_code');
	number_code.addEventListener('change', () => {
		if (number_code.value === '' || number_code.value == null) {
			number_code.classList.add('is-invalid');
			if (!notValidated.includes(5)) {
				notValidated.push(5);
			}
		}else {
			number_code.classList.remove('is-invalid');
			number_code.classList.add('is-valid');
			removeA(notValidated, 5);
		}
	})

	const client_phone = document.getElementById('client_phone');
	client_phone.addEventListener('keyup', () => {
		if (reg.test(client_phone.value) == false || client_phone.value === '' || client_phone.value == null ||  client_phone.value.length > 8 || client_phone.value.length < 7){
			client_phone.classList.add('is-invalid');
			if (!notValidated.includes(6)) {
				notValidated.push(6);
			}
		}else {
			client_phone.classList.remove('is-invalid');
			client_phone.classList.add('is-valid');
			removeA(notValidated, 6);
		}
	})

	const client_email = document.getElementById('client_email');
	client_email.addEventListener('keyup', () => {
		if (emailReg.test(client_email.value) == false || client_email === "" || client_email == null) {
			client_email.classList.add('is-invalid');
			if (!notValidated.includes(7)) {
				notValidated.push(7);
			}
		}else {
			client_email.classList.remove('is-invalid');
			client_email.classList.add('is-valid');
			removeA(notValidated, 7);
		}
	});

	const vehicle_brand = document.getElementById('brand');
	vehicle_brand.addEventListener('change', () => {
		if(vehicle_brand.value === '' || vehicle_brand.value == null){
			vehicle_brand.classList.add('is-invalid');
			if (!notValidated.includes(8)) {
				notValidated.push(8);
			}

		}else {
			vehicle_brand.classList.remove('is-invalid');
			vehicle_brand.classList.add('is-valid');
			removeA(notValidated, 8);
		}
	})

	const vehicle_model = document.getElementById('model');
	vehicle_model.addEventListener('change', () => {
		if(vehicle_model.value === '' || vehicle_model.value == null){
			vehicle_model.classList.add('is-invalid');
			if (!notValidated.includes(9)) {
				notValidated.push(9);
			}
		}else {
			vehicle_model.classList.remove('is-invalid');
			vehicle_model.classList.add('is-valid');
			removeA(notValidated, 9);
		}
	})

	const vehicle_year = document.getElementById('vehicle_year');
	vehicle_year.addEventListener('keyup', () => {
		if (vehicle_year.value > 2100 || vehicle_year.value < 1920 || vehicle_year.value === "" || vehicle_year.value == null) {
			vehicle_year.classList.add('is-invalid');
			if (!notValidated.includes(10)) {
				notValidated.push(10);
			}
		}else {
			vehicle_year.classList.remove('is-invalid');
			vehicle_year.classList.add('is-valid');
			removeA(notValidated, 10);
		}
	})

	const vehicle_class = document.getElementById('vehicle_class');
	vehicle_class.addEventListener('change', () => {
		if (vehicle_class.value === "" || vehicle_class.value == null) {
			vehicle_class.classList.add('is-invalid');
			if (!notValidated.includes(11)) {
				notValidated.push(11);
			}
		}else {
			vehicle_class.classList.remove('is-invalid');
			vehicle_class.classList.add('is-valid');
			removeA(notValidated, 11);
		}
	})

	const vehicle_color = document.getElementById('vehicle_color');
	vehicle_color.addEventListener('keyup', () => {
		if (numReg.test(vehicle_color.value) == true || vehicle_color.value === "" || vehicle_color.value == null) {
			vehicle_color.classList.add('is-invalid');
			if (!notValidated.includes(12)) {
				notValidated.push(12);
			}
		}else {
			vehicle_color.classList.remove('is-invalid');
			vehicle_color.classList.add('is-valid');
			removeA(notValidated, 12);
		}
	})

	const vehicle_use = document.getElementById('used_for');
	vehicle_use.addEventListener('change', () => {
		if(vehicle_use.value === '' || vehicle_use.value == null){
			vehicle_use.classList.add('is-invalid');
			if (!notValidated.includes(13)) {
				notValidated.push(13);
			}
		}else {
			vehicle_use.classList.remove('is-invalid');
			vehicle_use.classList.add('is-valid');
			removeA(notValidated, 13);
		}
	})

	const vehicle_bodywork_serial = document.getElementById('vehicle_bodywork_serial');
	vehicle_bodywork_serial.addEventListener('keyup', () => {
		if (vehicle_bodywork_serial.value === "" || vehicle_bodywork_serial.value == null) {
			vehicle_bodywork_serial.classList.add('is-invalid');
			if (!notValidated.includes(14)) {
				notValidated.push(14);
			}
		}else {
			vehicle_bodywork_serial.classList.remove('is-invalid');
			vehicle_bodywork_serial.classList.add('is-valid');
			removeA(notValidated, 14);
		}
	})

	const vehicle_motor_serial = document.getElementById('vehicle_motor_serial');
	vehicle_motor_serial.addEventListener('keyup', () => {
		if (vehicle_motor_serial.value === "" || vehicle_motor_serial.value == null) {
			vehicle_motor_serial.classList.add('is-invalid');
			if (!notValidated.includes(15)) {
				notValidated.push(15);
			}
		}else {
			vehicle_motor_serial.classList.remove('is-invalid');
			vehicle_motor_serial.classList.add('is-valid');
			removeA(notValidated, 15);
		}
	})

	const vehicle_certificate_number = document.getElementById('vehicle_certificate_number');
	vehicle_certificate_number.addEventListener('keyup', () => {
		if (vehicle_certificate_number.value === "" || vehicle_certificate_number.value == null) {
			vehicle_certificate_number.classList.add('is-invalid');
			if (!notValidated.includes(16)) {
				notValidated.push(16);
			}
		}else {
			vehicle_certificate_number.classList.remove('is-invalid');
			vehicle_certificate_number.classList.add('is-valid');
			removeA(notValidated, 16);
		}
	})

	const vehicle_weight = document.getElementById('vehicle_weight');
	vehicle_weight.addEventListener('keyup', () => {
		if (reg.test(vehicle_weight.value) == false || vehicle_weight.value === "" || vehicle_weight.value == null) {
			vehicle_weight.classList.add('is-invalid');
			if (!notValidated.includes(17)) {
				notValidated.push(17);
			}
		}else {
			vehicle_weight.classList.remove('is-invalid');
			vehicle_weight.classList.add('is-valid');
			removeA(notValidated, 17);
		}
	})

	const vehicle_registration = document.getElementById('vehicle_registration');
	vehicle_registration.addEventListener('keyup', () => {
		if (vehicle_registration.value === "" || vehicle_registration.value == null) {
			vehicle_registration.classList.add('is-invalid');
			if (!notValidated.includes(18)) {
				notValidated.push(18);
			}
		}else {
			vehicle_registration.classList.remove('is-invalid');
			vehicle_registration.classList.add('is-valid');
			removeA(notValidated, 18);
		}
	})

	const price = document.getElementById('price');
	price.addEventListener('change', () => {
		if(price.value === '' || price.value == null){
			price.classList.add('is-invalid');
			if (!notValidated.includes(19)) {
				notValidated.push(19);
			}
		}else {
			price.classList.remove('is-invalid');
			price.classList.add('is-valid');
			removeA(notValidated, 19);
		}
	})

	const estado = document.getElementById('estado');
	estado.addEventListener('change', () => {
		if (estado.value === '' || estado.value == null) {
			estado.classList.add('is-invalid')
			if (!notValidated.includes(20)) {
				notValidated.push(20)
			}
		}else {
			estado.classList.remove('is-invalid');
			estado.classList.add('is-valid');
			removeA(notValidated, 20)
		}
	})

	const municipio = document.getElementById('municipio');
	municipio.addEventListener('change', () => {
		if (municipio.value === '' || municipio.value == null) {
			municipio.classList.add('is-invalid')
			if (!notValidated.includes(21)) {
				notValidated.push(21)
			}
		}else {
			municipio.classList.remove('is-invalid');
			municipio.classList.add('is-valid');
			removeA(notValidated, 21)
		}
	})

	const parroquia = document.getElementById('parroquia');
	parroquia.addEventListener('change', () => {
		if (parroquia.value === '' || parroquia.value == null) {
			parroquia.classList.add('is-invalid')
			if (!notValidated.includes(22)) {
				notValidated.push(22)
			}
		}else {
			parroquia.classList.remove('is-invalid');
			parroquia.classList.add('is-valid');
			removeA(notValidated, 22)
		}
	})

	const client_name_contractor = document.getElementById('client_name_contractor');
	client_name_contractor.addEventListener('keyup', () => {
		if (client_name_contractor.value === '' || client_name_contractor.value == null) {
			client_name_contractor.classList.add('is-invalid');
			if (!notValidated.includes(23)) {
				notValidated.push(23);
			}
		} else {
			client_name_contractor.classList.remove('is-invalid');
			client_name_contractor.classList.add('is-valid');
			removeA(notValidated, 23);
		}

	});

	const client_lastname_contractor = document.getElementById('client_lastname_contractor');
	client_lastname_contractor.addEventListener('keyup', () => {
		if (client_lastname_contractor.value === "" || client_lastname_contractor.value == null) {
			client_lastname_contractor.classList.add('is-invalid');
			if (!notValidated.includes(24)) {
				notValidated.push(24);
			}
		} else {
			client_lastname_contractor.classList.remove('is-invalid');
			client_lastname_contractor.classList.add('is-valid');
			removeA(notValidated, 24);
		}

	});

	const id_type_contractor = document.getElementById('id_type_contractor');
	id_type_contractor.addEventListener('change', () => {
		if(id_type_contractor.value === ''){
			id_type_contractor.classList.add('is-invalid');
			if (!notValidated.includes(25)) {
				notValidated.push(25);
			}
		}else {
			id_type_contractor.classList.remove('is-invalid');
			id_type_contractor.classList.add('is-valid');
			removeA(notValidated, 25);
		}
	});

	const client_ci_contractor = document.getElementById('ci_contractor');
	client_ci_contractor.addEventListener('keyup', () => {
		if (reg.test(client_ci_contractor.value) == false || client_ci_contractor.value === '' || client_ci_contractor.value == null ||  client_ci_contractor.value.length > 9 || client_ci_contractor.value.length < 7) {
			client_ci_contractor.classList.add('is-invalid');
			if (!notValidated.includes(26)) {
				notValidated.push(26);
			}
		} else {
			client_ci_contractor.classList.remove('is-invalid');
			client_ci_contractor.classList.add('is-valid');
			removeA(notValidated, 26);
		}
	})


	form.addEventListener('submit', (e) => {
		if (notValidated.length > 0) {
			e.preventDefault();
		}
	})
</script> --}}
@endsection

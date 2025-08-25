@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Editar Póliza</h6>
		<a href="{{ url()->previous() }}" class="float-right btn btn-danger text-white">X</a>

	</div>
	<div class="card-body">
		<form action="/user/edit-solicitud/{{$id}}" method="POST" enctype="multipart/form-data"  id="form_policies">
			@csrf
			<input type="hidden" name="_method" value="PUT">


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

					<label for="vehicle_weight">Peso del vehiculo</label>
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



			<button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3">Actualizar Solicitud</button>
		</form>
	</div>
</div>

@endsection

@section('scripts')
<script>
    // Función para mostrar la miniatura
function showPreview(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            // Crear una imagen y asignarle la URL del archivo
            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.maxWidth = "100px"; // Tamaño de la miniatura
            img.style.marginTop = "10px";

            // Mostrar la imagen en el contenedor
            const previewContainer = document.getElementById(previewId);
            previewContainer.innerHTML = ""; // Limpiar el contenedor
            previewContainer.appendChild(img);
        };

        reader.readAsDataURL(input.files[0]); // Leer el archivo como URL
    }
}

// Asignar eventos a los inputs de archivo
document.getElementById("image").addEventListener("change", function () {
    showPreview(this, "preview-image");
});

document.getElementById("image1").addEventListener("change", function () {
    showPreview(this, "preview-image1");
});
</script>
<script src="{{asset('js/Form-Validations/Policies.js')}}"></script>
@endsection

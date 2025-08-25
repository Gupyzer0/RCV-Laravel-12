@extends('layouts.call-modules')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Cotización de Seguro de Responsabilidad Civil</h6>
		<a href="{{route('call.index.spolicies')}}" class="float-right btn btn-danger text-white">X</a>

	</div>
	<div class="card-body">

		<form action="{{ route('call.register.policy.submit')}}" method="POST" enctype="multipart/form-data" id="form_policies">
			@csrf
			<div class="form-row" style="padding-bottom: 10px;">
			    <Label>Vendedor</Label>
			    <select name="username" id="username" class="form-control @error('username') is-invalid @enderror">
			        <option value="">- Seleccionar Vendedor -</option>            
			        @foreach($users as $user)
			            <option 
			                value="{{ $user->id }}" {{ old('username') == $user->id ? 'selected' : '' }}>{{ $user->name.' '.$user->lastname }}</option>
			        @endforeach
			    </select>
			    @error('username')
			        <span class="invalid-feedback" role="alert">
			            <strong>{{ $message }}</strong>
			        </span>
			    @enderror
			</div>

			<h5 style="color:black;">Asegurado</h5>
			<div class="form-row border-bottom border-dark">

                <div class="form-group col-md-3">
					<label for="ci_contractor">Documento de identificación</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="id_type_contractor" class="form-control @error('id_type_contractor')is-invalid @enderror custom-select" id="id_type_contractor">
								<option value="" selected> - </option>
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

						<input required autocomplete="off" type="text" class="form-control @error('client_ci_contractor') is-invalid @enderror" name="client_ci_contractor" id="ci_contractor" placeholder="..." value="{{ old('client_ci_contractor') }}">


						@error('client_ci_contractor')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror

					</div>
				</div>

				<div class="form-group col-md-3">
					<label for="client_name_contractor">Nombre(s)</label>
					<input required autocomplete="off" type="text" maxlength="40" name="client_name_contractor" id="client_name_contractor" class="form-control @error('client_name_contractor') is-invalid @enderror" placeholder="..." value="{{ old('client_name_contractor') }}">

					@error('client_name_contractor')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname_contractor">Apellido(s)</label>
					<input autocomplete="off" type="text" name="client_lastname_contractor" id="client_lastname_contractor" class="form-control @error('client_lastname_contractor') is-invalid @enderror" placeholder="..." value="{{ old('client_lastname_contractor') }}">

					@error('client_lastname_contractor')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
                    <label for="email_t">Correo Electronico</label>
                    <input required autocomplete="off" type="email" maxlength="40" name="email_t" id="email_t" class="form-control @error('email_t') is-invalid @enderror" placeholder="..." value="{{ old('email_t') }}">

                    @error('email_t')
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

			<a class="btn btn-primary text-white"  id="copy" onclick="PasarValor()">Copiar Datos</a>

			{{-- FIN COPIAR DATOS --}}

			<div class="form-row border-bottom border-dark">
                <div class="form-group col-md-3">
					<label for="ci">Documento de identificación</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<select name="id_type" class="form-control @error('id_type')
							is-invalid @enderror custom-select" id="id_type">
								<option value="" selected> - </option>
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

						<input required autocomplete="off" type="text" class="form-control @error('client_ci') is-invalid @enderror" name="client_ci" id="ci" placeholder="..." value="{{ old('client_ci') }}">

						@error('client_ci')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
				</div>

				<div class="form-group col-md-3">
					<label for="client_name">Nombre(s)</label>
					<input required autocomplete="off" type="text" maxlength="40" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" placeholder="..." value="{{ old('client_name') }}">

					@error('client_name')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
					<label for="client_lastname">Apellido(s)</label>
					<input autocomplete="off" type="text" name="client_lastname" id="client_lastname" class="form-control @error('client_lastname') is-invalid @enderror" placeholder="..." value="{{ old('client_lastname') }}">

					@error('client_lastname')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

                <div class="form-group col-md-3">
                    <label for="client_email">Correo Electronico:</label>
                    <input required autocomplete="off" type="email" maxlength="40" name="client_email" id="client_email" class="form-control @error('client_email') is-invalid @enderror" placeholder="..." value="{{ old('client_email') }}">

                    @error('client_email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

				<div class="form-group col-md-3">
					<label for="fecha_n">Fecha de Nacimiento</label>
					<input required type="date" name="fecha_n" id="fecha_n" class="form-control @error('fecha_n') is-invalid @enderror" value="{{ old('fecha_n') }}">

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

				<div class="form-group col-md-3">
					<label for="client_phone" class="text-md-right">Número de teléfono</label>
					<div class="input-group">
						<div class="input-group-prepend">
						<select name="sp_prefix" class="form-control @error('sp_prefix') is-invalid @enderror custom-select" id="number_code">
								<option value="">-</option>

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

						<input required type="text" name="client_phone" id="client_phone" value="{{old('client_phone')}}"class="form-control @error('client_phone') is-invalid @enderror" placeholder="...">

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
						<option value="">- Seleccionar Estado -</option>
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
						<option value="">- Seleccionar Municipio -</option>
					</select>

					@error('municipio')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-3">
					<label for="parroquia">Parroquia</label>
					<select required name="parroquia" id="parroquia" class="form-control @error('parroquia') is-invalid @enderror custom-select">
						<option value="">- Seleccionar Parroquia -</option>
					</select>

					@error('parroquia')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group col-md-12">
					<label for="client_address">Dirección</label>
					<textarea name="client_address" id="client_address" class="form-control @error('client_address') is-invalid @enderror">No especificado</textarea>

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
					<input required autocomplete="off" type="text" name="vehicleBrand" onkeypress="return check(event)" id="brand" class="form-control @error('vehicleBrand') is-invalid @enderror" placeholder="..." value="{{ old('vehicleBrand') }}">

					@error('vehicleBrand')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="model">Modelo</label>
					<input required autocomplete="off" type="text" name="vehicleModel" maxlength="25" id="model" class="form-control @error('vehicleModel') is-invalid @enderror" placeholder="..." value="{{ old('vehicleModel') }}">


					@error('vehicleModel')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_type">Tipo</label>
					<input required autocomplete="off" type="text" name="vehicle_type" onkeypress="return check(event)" id="vehicle_type" class="form-control @error('vehicle_type') is-invalid @enderror" placeholder="..." value="{{ old('vehicle_type') }}">


					@error('vehicle_type')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror



					<label for="vehicle_year">Año</label>
					<input required autocomplete="off" type="number" name="vehicle_year" id="vehicle_year" class="form-control @error('vehicle_year') is-invalid @enderror" placeholder="..." value="{{ old('vehicle_year') }}">

					@error('vehicle_year')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_color">Color</label>
					<input required autocomplete="off" type="text" name="vehicle_color" id="vehicle_color" class="form-control @error('vehicle_color') is-invalid @enderror" placeholder="..." value="{{ old('vehicle_color') }}">

					@error('vehicle_color')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror


					<label for="used_for">Uso del vehiculo</label>
					<select id="used_for" required  name="used_for" class="form-control @error('used_for') is-invalid @enderror custom-select">
						<option value="">- Seleccionar -</option>
						@foreach($vehicle_type as $vehicle_type)						
						<option value="{{ $vehicle_type->type }}" {{ old('used_for') == $vehicle_type->type ? 'selected' : '' }}>{{$vehicle_type->type}}</option>
						@endforeach
					</select>

					@error('used_for')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

                   <label for="trailer">¿Posee Trailer?</label>
					<select id="trailer" name="trailer" class="form-control custom-select">
					    <option value="">No</option>
					    <option value="1">Sí</option>
					</select>



				</div>

				<div class="form-group col-md-6">

					<label for="vehicle_bodywork_serial">Serial de carroceria</label>
					<input required autocomplete="off" type="text" name="vehicle_bodywork_serial" id="vehicle_bodywork_serial" class="form-control @error('vehicle_bodywork_serial') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ old('vehicle_bodywork_serial') }}">

					@error('vehicle_bodywork_serial')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_motor_serial">Serial del motor</label>
					<input required autocomplete="off" type="text" name="vehicle_motor_serial" id="vehicle_motor_serial" class="form-control @error('vehicle_motor_serial') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ old('vehicle_motor_serial') }}">

					@error('vehicle_motor_serial')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror


					<label for="vehicle_certificate_number">Cantidad de Puestos</label>
					<input required autocomplete="off" type="number" name="vehicle_certificate_number" id="vehicle_certificate_number" class="form-control @error('vehicle_certificate_number') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ old('vehicle_certificate_number') }}">



					@error('vehicle_certificate_number')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="vehicle_weight">Peso del vehiculo</label>
					<div class="input-group">
						<input required autocomplete="off" type="number" name="vehicle_weight" id="vehicle_weight" class="form-control @error('vehicle_weight') is-invalid @enderror" placeholder="Kg" value="{{ old('vehicle_weight') }}">
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
					<input required autocomplete="off" type="text" name="vehicle_registration" id="vehicle_registration" class="form-control @error('vehicle_registration') is-invalid @enderror" style="text-transform:uppercase;" placeholder="..." value="{{ old('vehicle_registration') }}">

					@error('vehicle_registration')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

					<label for="image">Titulo de Propiedad</label>
					<input type="file" name="image" id="image" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf">
					@error('image')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
					
					<label for="image1">Cedula o Rif</label>
					<input type="file" name="image1" id="image1" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf">
					@error('image1')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror

				</div>

			</div>

			<h3 class="mt-4" style="color:black;">Poliza</h3>

			<div class="form-group">
				<label for="vehicle_class">Clase de vehículo</label>
				<select name="vehicle_class" id="vehicle_class" class="form-control @error('vehicle_class') is-invalid @enderror custom-select">
					<option value="">- Seleccionar clase de vehículo -</option>
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
				<select required  name="price" class="form-control @error('price') is-invalid @enderror custom-select" id="price" >
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
            <button id="submitButton" type="submit" class="btn btn-primary btn-block mt-3" onclick="disableButton(event)">Cotizar Póliza</button>
		</form>
	</div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="trailerModal" tabindex="-1" role="dialog" aria-labelledby="trailerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="trailerModalLabel">⚠️ Advertencia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Al seleccionar <strong>Trailer</strong>, se agregará un <strong>20% adicional</strong> al precio de la póliza. ¿Estás seguro?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No, cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmTrailer">Sí, aceptar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')


<!--FIN Script para que ejecute el modal al terminar de carga la pagina-->


	<script src="{{asset('js/Form-Validations/Policies.js')}}"></script>
<script>
	function PasarValor()
{
document.getElementById("client_name").value = document.getElementById("client_name_contractor").value;
document.getElementById("client_lastname").value = document.getElementById("client_lastname_contractor").value;
document.getElementById("id_type").value = document.getElementById("id_type_contractor").value;
document.getElementById("ci").value = document.getElementById("ci_contractor").value;
document.getElementById("client_email").value = document.getElementById("email_t").value;

}
</script>
<script>
    function check(e) {
    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8) {
        return true;
    }

    // Patrón de entrada, en este caso solo acepta numeros y letras
    patron = /[A-Za-z0-9 .]/;
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

</script>

<script>
    function disableButton(event) {
        event.preventDefault(); // Evita el envío automático del formulario

        // Obtener el formulario
        const form = document.getElementById('form_policies');

        // Validar si todos los campos requeridos están llenos
        if (form.checkValidity()) {
            const button = document.getElementById('submitButton');
            button.disabled = true; // Desactiva el botón
            button.innerText = 'Registrando...'; // Cambia el texto del botón
            form.submit(); // Envía el formulario después de validar
        } else {
            // Obtener todos los campos requeridos que no están llenos
            const invalidFields = Array.from(form.querySelectorAll(':invalid')).map(field => field.previousElementSibling.innerText);

            // Crear un mensaje de advertencia con los nombres de los campos faltantes
            const message = 'Por favor, completa todos los campos requeridos:\n' + invalidFields.join('\n');

            alert(message); // Mostrar el mensaje de advertencia
        }
    }
</script>
<script>
    function validateFile(fileInput) {
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        const maxFileSize = 4 * 1024 * 1024; // 4 MB en bytes
        const file = fileInput.files[0];

        if (file) {
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                alert('Solo se permiten archivos con las siguientes extensiones: .jpg, .jpeg, .png, .pdf');
                fileInput.value = ''; // Limpiar el campo de archivo
                return;
            }
            if (file.size > maxFileSize) {
                alert('El archivo ' + file.name + ' supera el tamaño máximo permitido de 4 MB.');
                fileInput.value = ''; // Limpiar el campo de archivo
                return;
            }
        }
    }

    document.getElementById('image').addEventListener('change', function() {
        validateFile(this);
    });

    document.getElementById('image1').addEventListener('change', function() {
        validateFile(this);
    });
</script>

<script>
$(document).ready(function() {
    const trailerSelect = $('#trailer');
    const trailerModal = $('#trailerModal');
    const confirmBtn = $('#confirmTrailer');

    // Cuando el valor del select cambia...
    trailerSelect.on('change', function() {
        if ($(this).val() === '1') { // Si selecciona "Sí"
            trailerModal.modal('show'); // Muestra el modal
        }
    });

    // Si el usuario confirma en el modal...
    confirmBtn.on('click', function() {
        trailerModal.modal('hide'); // Cierra el modal
        // Aquí puedes agregar lógica adicional si es necesario
    });

    // Si el usuario cierra el modal sin confirmar...
    trailerModal.on('hidden.bs.modal', function() {
        trailerSelect.val(''); // Resetea el select a "No"
    });
});
</script>
@endsection



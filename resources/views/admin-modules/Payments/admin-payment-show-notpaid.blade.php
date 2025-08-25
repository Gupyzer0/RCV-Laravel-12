@extends('layouts.admin-modules')

@section('module')
<a class="btn btn-warning shadow mb-2" href="{{ route('index.notpaid')}}">Regresar</a>
<div class="card shadow mb-2">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Pagos pendientes</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<div class="d-flex justify-content-end flex-row">
				<button class="pagar btn btn-primary pl-4 pr-4 mb-2 mr-2" data-toggle="modal" data-target="#modal-pagar-manualmente">Pagar manualmente</button>
				<button class="pagar btn btn-success pl-4 pr-4 mb-2" data-toggle="modal" data-target="#modal-pagar">Pagar</button>
			</div>
			<form id="formulario-polizas">
				@csrf
				<table class="table table-bordered text-center" id="" width="100%" cellspacing="0" style="font-size: 12px;">
					<thead>
						<tr>
							<th>N° Poliza</th>
							<th>Seleccionar</th>
							<th>Vendedor</th>
							<th>Tomador</th>
							<th>Vehículo</th>
							<th>Placa</th>
							<th>Fecha de Emisión</th>
							<th>Plan</th>
							<th>Total Prima Poliza</th>
							<th>Comisión a Pagar</th>
							<th>Eliminar</th>
						</tr>
					</thead>
					<tbody>
						@foreach($not_paid as $policy)
							<tr class="poliza">
								<td>{{$policy->id}}</td>
								@if(!$policy->statusu)
									<td><input type="checkbox" @if($policy->report)checked='checked'@endif class="settings" name="update_checkbox[]" value="{{$policy->id}}"></td>
								@else
									<td><p class="text-danger">ANULADA</p></td>
								@endif
								<td>{{$policy->user->name.' '.$policy->user->lastname}}</td>
								<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
								<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
								<td>{{$policy->vehicle_registration}}</td>
								<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
								<td>{{$policy->price->description}}</td>
								<td class="precio">{{number_format($policy->total_premium, 2, ',','.')}} $</td>
								<td class="comision" valor_comision="{{ number_format($policy->comision, 2) }}">{{ number_format($policy->comision, 2, ',','.') }} $</td>
								<td>
									@if ($loop->first) {{-- Por reglas de negocio no se puede eliminar la primera poliza --}}
										<span class="btn bg-transparent text-warning pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$policy->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span>
									@else
										<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal1-".$policy->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span>
									@endif
								</td>
							</tr>
							{{-- Modal que avisa que la primera poliza no puede eliminarse --}}
							<div class="modal fade" id="deleteModal-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">INFORMACIÓN</h5>
											<button class="close" type="button" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body"><span>La primera poliza creada no se puede Eliminar por aqui, para eliminarla debe ir a polizas</div>
										<div class="modal-footer">
											<form action="" method="POST">
												@csrf
												<a href="/admin/index-policy/{{$policy->id}}" class="btn btn-info float-right">Ver Poliza</a>
												<button class="btn btn-danger" type="button" data-dismiss="modal">Cancelar</button>
											</form>
										</div>
									</div>
								</div>
							</div>
							{{-- /Modal que avisa que la primera poliza no puede eliminarse --}}

							{{-- Modal borrar Poliza --}}
							<div class="modal fade" id="deleteModal1-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
											<form action="/admin/delete-policy-pay/{{$policy->id}}" method="POST">
												@csrf
												@method('DELETE')
												<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
												<button type="submit" class="btn btn-primary">Continuar</button>
											</form>
										</div>
									</div>
								</div>
							</div>
							{{-- /Modal borrar Poliza --}}
						@endforeach
					</tbody>
				</table>
				<button type="button" class="check-all btn btn-primary" style="float: right">Seleccionar Todos</button>
			    <div style="margin-top: 20px;">
					{{-- TODO: este js parece estar choto y no calcula los totales debidamente --}}
                    <strong>Total seleccionado:</strong>
					<div id="totales">
						<span class="total-selected-eur">0.00 €</span> /
						<span class="total-selected-usd">0.00 $</span> /
						<span class="total-selected-bs">0.00 Bs</span> 
					</div>
                    <span id="loading-spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                </div>

				{{-- Modal pagar manualmente --}}
				@include('admin-modules.Payments.modal-pagar-manualmente')
				{{-- /Modal pagar manualmente --}}

				{{-- Modal pago automatico --}}
				@include('admin-modules.Payments.modal-pagar')
				{{-- /Modal pago automatico --}}
			</form>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	$(function(){
		// obtener calculo de comisiones inicial
		obtener_calculo_comisiones()

		// listener settings -> si cambia un checkbox recalculamos
		$('.settings').on('change',function(){
			obtener_calculo_comisiones()
		})

		// Seleccionar todos
		var checked = false;
		$('.check-all').on('click',function(){
			if(checked == false) {
				$('.settings').prop('checked', true);
				checked = true;
			} else {
				$('.settings').prop('checked', false);
				checked = false;
			}
			obtener_calculo_comisiones()
		});

		// Listeners
		$('#submit-pago-automatico').on('click', function(e){
			e.preventDefault()
			resetear_errores()
			pagar_automaticamente()
		})

		$('#submit-pago-manual').on('click', function(e){
			e.preventDefault()
			resetear_errores()
			pagar_manualmente()
		})
	})

	// Consulta AJAX para obtener el calculo de las comisiones -- y evitar problemas con JS --
	function obtener_calculo_comisiones(){
		const $spinner = $('#loading-spinner')
		const $totales = $('#totales')
		const $total_euros = $('.total-selected-eur')
		const $total_dolares = $('.total-selected-usd')
		const $total_bolivares = $('.total-selected-bs')

		// Obteniendo el arreglo de las polizas marcadas
		var polizas = polizas_marcadas()
		
		// Creando form data para ingresarle la data
		const form_data_polizas = new FormData() 
		form_data_polizas.append("_token", "{{ csrf_token() }}")

		// Ingresando el arreglo de ids de polizas dentro del objeto FormData
		polizas.forEach(poliza => {
			form_data_polizas.append('polizas[]', poliza)
		})		

		// Cargando . . .
		$spinner.show()
		$totales.hide()
		
		$.ajax({
			url: "{{ route('index.show.notpaid.calcular-comision', $user->id) }}",
			type: 'POST',
			data: form_data_polizas,
			processData: false,
			contentType: false,
			success: function(response) {
				$total_euros.html(response.total_euros + ' €')
				$total_dolares.html(response.total_dolares + ' $')
				$total_bolivares.html(response.total_bolivares + ' Bs')
			},
			error: function(xhr, status, error) {
				console.log(error)
				$total_euros.html('N/A')
				$total_dolares.html('N/A')
				$total_bolivares.html('N/A')
			},
			complete: function() {
				$spinner.hide()
				$totales.show()
			}
		});
	}

	// Registra un pago con un soporte
	function pagar_manualmente(){
		const polizas = polizas_marcadas()
		const $loading_spinner = $('#loading-spinner-pago-manual')
		const $boton_submit = $('#submit-pago-manual')

		$loading_spinner.show()
		$boton_submit.prop('disabled',true)

		// Creando form data para ingresarle la data
		const form_data_pago_manual = new FormData() 
		form_data_pago_manual.append("_token", "{{ csrf_token() }}")

		// Ingresando el arreglo de ids de polizas dentro del objeto FormData
		polizas.forEach(poliza => {
			form_data_pago_manual.append('polizas[]', poliza)
		})

		var soporte_pago = $('#soporte_pago')[0];

		// verificamos que el input tenga archivos
		if (soporte_pago && soporte_pago.files && soporte_pago.files.length > 0) {
			form_data_pago_manual.append('soporte_pago',soporte_pago.files[0])
		}

		$.ajax({
			url: "{{ route('ajax-pago-manual', $user->id) }}",
			type: 'POST',
			data: form_data_pago_manual,
			processData: false,
            contentType: false,
			dataType: 'json',
			success: function(response) {
				window.location.href = "{{ route('index.show.payment',$user->id) }}"
			},
			error: function(xhr, status, error) {
				
				$boton_submit.prop('disabled',false)

				if (xhr.status === 422) { // Error de validacion
					var errores = xhr.responseJSON.errors;
					$.each(errores, function(key, errores) {
						$.each(errores, function(k, error){
							console.log(key)
							console.log(error)
							$('#' + key + '_error').html('<li>'+error+'</li>');
						})												
						$('#' + key + '_error').show();
					});
				} else if (xhr.status === 403) {
					alert('No se encuentra autorizado para realizar esta acción')
				} else if (xhr.status === 413) {
					$('#soporte_pago_error').html('<li>El soporte de de pago es inválido</li>')
					$('#soporte_pago_error').show()
				} else {
					// Otros errores
					console.error('AJAX error:', status, error);
					alert('Error inesperado. Contacte al administrador del sistema');
				}
			},
			complete: function() {
				$loading_spinner.hide()
			}
		});
	}

	// Registra un pago usando la API de un banco
	function pagar_automaticamente(){
		const polizas = polizas_marcadas()
		const $loading_spinner = $('#loading-spinner-pago-automatico')
		const $boton_submit = $('#submit-pago-automatico')

		$loading_spinner.show()
		$boton_submit.prop('disabled',true)

		// Creando form data para ingresarle la data
		const form_data_pago_automatico = new FormData() 
		form_data_pago_automatico.append("_token", "{{ csrf_token() }}")

		// Ingresando un UUID para la transacción para evitar duplicados
		form_data_pago_automatico.append("referencia_interna", "{{ Str::random(20) }}")

		// Ingresando el arreglo de ids de polizas dentro del objeto FormData
		polizas.forEach(poliza => {
			form_data_pago_automatico.append('polizas[]', poliza)
		})

		$.ajax({
			url: "{{ route('selected.pay.submit', $user->id) }}",
			type: 'POST',
			data: form_data_pago_automatico,
			processData: false,
            contentType: false,
			dataType: 'json',
			success: function(response) {
				window.location.href = response.redirect // redirigimos al pago
			},
			error: function(xhr, status, error) {
				$boton_submit.prop('disabled',false)

				if (xhr.status === 422) { // Error de validacion
					var errores = xhr.responseJSON.errors;
					$.each(errores, function(key, errores) {
						$.each(errores, function(k, error){
							console.log(key)
							console.log(error)
							$('#' + key + '_error').html('<li>'+error+'</li>');
						})												
						$('#' + key + '_error').show();
					});
				} else if (xhr.status === 403) {
					alert('No se encuentra autorizado para realizar esta acción')
				} else if (xhr.status === 503) {
					$('#pago-automatico_error').html('<li>'+ xhr.responseJSON.message +'</li>')
					$('#pago-automatico_error').show()
				} else {
					// Otros errores
					console.error('AJAX error:', status, error);
					alert('Error inesperado. Contacte al administrador del sistema');
				}
			},
			complete: function() {
				$loading_spinner.hide()
			}
		});

	}

	// Devuelve un arreglo con las polizas marcadas
	function polizas_marcadas() {
		return Array.from(new Set($("#formulario-polizas :input[name='update_checkbox[]']:checked").map(function(){ return $(this).val() }).get()))
	}

	// oculta todos los errores
	function resetear_errores() {
		$('.invalid-feedback').hide()
	}

</script>
@endsection

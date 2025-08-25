@extends('layouts.admin-modules')

@section('module')

{{-- TODO: mover a un componente de alertas ?? --}}
@if (session('success')) 
	<div class="alert alert-success alert-dismissible fade show" role="alert">
		{{ session('success') }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif
<a class="btn btn-light shadow mb-2" href="{{ route('index.payments')}}">Ver Consultas de pago</a>
<div class="card shadow mb-2">
	<div class="card-header py-2">
	<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Pagos realizados a {{$payments[0]->name}}</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
					    <th>id</th>
						<th>Vendedor</th>
						<th>Oficina</th>
						<th>Fecha del Pago</th>
						<th>Total Vendido</th>
						<th>Pago al Vendedor</th>
						<th>Monto a Recibir</th>
						<th>Comprobante</th>
					</tr>
				</thead>
				<tbody>
					@foreach($payments as $payment)
						<tr>
							<td>{{$payment->id}}</td>
							<td>{{$payment->user->nombre_completo}}</td>
							<td>{{$payment->office}}</td>
							<td>{{\Carbon\Carbon::parse($payment->created_at)->format('d-m-Y')}}</td>
							<td><span class="prices_ce">{{ number_format($payment->total, 2,',','.') }}</span> €</td>
							<td><span class="prices_ce">{{ number_format($payment->total_payment, 2,',','.') }}</span> €</td>
							<td><span class="prices_ce">{{ number_format($payment->total - $payment->total_payment, 2,',','.') }}</span> €</td>
							<td>
								<span class="btn btn-primary" id="openModal" data-toggle="modal"data-target="{{'#'."imgModal-".$payment->id}}">
									Comprobante
								</span>
								<a href="{{ route('index.show.paid', $payment->id) }}" class="btn btn-success text-white">Ver</a>
							</td>
						</tr>

						<div class="modal fade text-center" id="{{"imgModal-".$payment->id}}" tabindex="-1" role="dialog"
							aria-labelledby="imgModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="imgModalLabel">Comprobante</h5>
										<button class="close" type="button" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">
										{{-- pago manual --}}
										@if($payment->tipo_de_pago_id == "1") 
											{{-- el comprobante es una imagen --}}
											@if(Str::endswith($payment->comprobante,'png') || Str::endswith($payment->comprobante,'jpg') || Str::endswith($payment->comprobante,'jpeg'))
												<img src="{{Storage::url($payment->bill)}}" class="card-img-top" alt="...">
											@else
												{{-- el comprobante es un pdf u otro archivo valido --}}
												<a href="{{Storage::url($payment->bill)}}" class="btn btn-primary text-white" target="blank">Descargar comprobante</a>
											@endif
										@else {{-- pago atutomatico --}}
											<div style="text-align: justify">
												<div class="alert alert-success" role="alert">
													<h5 class="alert-heading">Mensaje de la transferencia</h5>
													<hr>
													<b> {{ $payment->comprobante->message }}</b>
												</div>

												<h4 class="d-flex flex-row align-items-center">
													<b class="mr-2">Referencia:</b>
													<div class="referencia-texto mr-2">{{ $payment->comprobante->value_decrypted->Reference }}</div>
													<i class="fas fa-copy referencia text-secondary oscurecer-on-hover icono-copiar" style="cursor: pointer" data-toggle="tooltip" data-placement="right" title="Copiar al portapapeles"></i>
												</h4>

												<h4 class="d-flex flex-row align-items-center">
													<b class="mr-2">Código de autorización::</b>
													<div class="referencia-texto mr-2">{{ $payment->comprobante->value_decrypted->AuthorizationCode }}</div>
													<i class="fas fa-copy referencia text-secondary oscurecer-on-hover icono-copiar" style="cursor: pointer" data-toggle="tooltip" data-placement="right" title="Copiar al portapapeles"></i>
												</h4>
											</div>
										@endif
									</div>
									<div class="modal-footer">
										<button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
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

@section('scripts')
<script>
	$(document).ready(function() {
		// Copiar al portapapeles el número de referencia
		$('.icono-copiar').on('click', function() { 
			const elemento = $(this)
			contenido = $(this).siblings('.referencia-texto:first').html().trim()
			// Quitando tooltip y rehaciendolo para cambiar el texto
			elemento.tooltip('dispose')
			elemento.attr('title','copiado')
			elemento.tooltip()
			elemento.tooltip('show')
			// Cambiando ícono
			elemento.removeClass('fa-copy')
			elemento.addClass('fa-check')
			// Devolviendo ícono y texto despues de 5 segundos
			setTimeout(function() {
				elemento.removeClass('fa-check').addClass('fa-copy');
				elemento.tooltip('dispose')
				elemento.attr('title','Copiar al portapapeles')
				elemento.tooltip()
			}, 5000);
			copiarTextoPortapapeles(contenido)
		})

		// Copiar al portapapeles el texto dado
		async function copiarTextoPortapapeles(texto) {
			try {
				await navigator.clipboard.writeText(texto);
			} catch (err) {
				console.error('Falla al copiar texto: ', err);
			}
		}
	});
</script>
@endsection

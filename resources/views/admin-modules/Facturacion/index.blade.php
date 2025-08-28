@extends('layouts.app')

@section('module')

@if (session('message'))
    <div class="alert alert-info alert-dismissible fade show mb-2">
        {{ session('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Facturación</h6>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- boton emitir facturas --> 
            <div class="col-auto">
                <button class="btn btn-outline-success my-2 my-sm-0" data-toggle="modal" data-target="#ModalEmitirFacturas"
                    @if(!$polizas_sin_facturar->count()) disabled @endif
                >
                    <div class="mb-2">Emitir facturas</div>
                    <hr class="my-3">
                    <i class="fas fa-file-invoice-dollar mb-2" style="font-size: 3rem"></i>
                </button>
            </div>
            <!-- /boton emitir facturas --> 

            <!-- info de polizas -->
            <div class="col-lg-4">
                <div class="alert alert-info">
                    @if($polizas_sin_facturar->count())
                    <ul>
                        <li>
                            Al momento hay <b>{{ $polizas_sin_facturar->count() }}</b> polizas sin facturar.
                        </li>
                        <li>
                            La poliza más vieja data del {{ \Carbon\Carbon::create($polizas_sin_facturar->first()->created_at)->format('d/m/Y') }}
                        </li>
                        <li>
                            La poliza más nueva data del {{ \Carbon\Carbon::create($polizas_sin_facturar->last()->created_at)->format('d/m/Y') }}
                        </li>
                    </ul>
                    @else
                        No hay polizas pendientes por facturar
                    @endif
                </div>
            </div>
            <!-- /info de polizas -->
        </div>
    </div>
</div>

<!-- modal emitir facturas -->
<div class="modal fade" id="ModalEmitirFacturas" tabindex="-1" role="dialog" aria-labelledby="ModalEmitirFacturas" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea emitir las facturas?</h5>
				<button class="close" type="button" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>

			<div class="modal-body">
				Se recomienda emitir las facturas al final del día para evitar problemas con polizas anuladas.
			</div>

			<div class="modal-footer">
				<form action="{{ route('facturacion.emitir') }}" method="POST">
					@csrf
					@method('POST')
					<button type="submit" class="btn btn-primary">Estoy seguro, emitir facturas</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /modal emitir facturas -->

@endsection
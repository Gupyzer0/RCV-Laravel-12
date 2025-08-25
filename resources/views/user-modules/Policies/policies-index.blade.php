@extends('layouts.app')

@section('module')
<a class="btn btn-success shadow" href="{{ route('user.index.spolicies')}}">Solicitud de Polizas</a>

<div class="card shadow mb-4">
	<div class="card-header py-2">

		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Polizas</h6>

	</div>
<div class="navbar navbar-light float-right">
    @if($counter == 0)
  <form class="form-inline" action="{{route('user.search.policies')}}" method="GET">
    <input class="form-control mr-sm-2" name="texto" type="search" placeholder="Cedula o Placa"  maxlength="10" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
  </form>
    <a class="btn btn-primary float-right mr-2 text-white" data-toggle="modal" data-target="#filterModal">Filtrar por Fecha</a>
    @endif

</div>


	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="" width="100%" cellspacing="0">
				<thead>
					<tr>
                        @if($counter == 0)
						<th>N. Poliza</th>
                        @else
                        <th>N. Cotización</th>
                        @endif
						<th>Asegurado</th>
						<th>C.I/RIF</th>
						<th>Vehiculo</th>
						<th>Placa</th>
						<th>Fecha de emisión</th>
                        @if($counter == 0)
						<th>Estatus</th>
                        <th>Recaudos</th>
                        @endif
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($policies as $policy)
					<tr>
                        <td style="text-align: center;">{{$policy->idp}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->client_ci}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
                        @if($counter == 0)
						@if(Carbon\Carbon::parse($policy->expiring_date) > $today)
						<td class="text-success">Vigente</td>
						@elseif(Carbon\Carbon::parse($policy->expiring_date) == $today)
						<td class="text-warning">Vence Hoy</td>
						@else
						<td class="text-danger">Vencido</td>
						@endif

           

@if(!$policy->image_tp)
    <td class="text-danger">
        Falta Título
    </td>
@elseif(!$policy->image_ci)
    <td class="text-danger">
        Falta Cédula
    </td>
@else
    <td class="text-success">
        Cargados
    </td>
@endif
                        @endif


                       <td class="d-flex justify-content-center align-items-center">
    <div class="dropdown mb-4">
        @if(Auth::user()->status == 0)
            <button class="btn btn-primary dropdown-toggle" type="button"
                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                Acciones
            </button>
            
            <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                @if(in_array(auth()->user()->type, [6, 2, 9,0]))
                    <a class="dropdown-item" href="/user/edit-policy/{{$policy->id}}">Editar</a>
                @endif
                
                @if($counter == 0)
                    {{-- Opciones cuando counter es 0 --}}
                    @if($policy->image_tp > 1 || $policy->image_ci > 1)
                        <a class="dropdown-item" href="#" data-toggle="modal" 
                           data-target="{{'#'}}uploadDocumentsModal{{$policy->id}}">
                           Cargar Documentos
                        </a>
                    @else
                        <a class="dropdown-item" href="#" data-toggle="modal" 
                           data-target="{{'#'}}uploadDocumentsModal{{$policy->id}}">
                           Cargar Documentos
                        </a>
                        <a class="dropdown-item" href="/user/download-tp/{{$policy->id}}">Descargar Titulo</a>
                        <a class="dropdown-item" href="/user/download-ci/{{$policy->id}}">Descargar Cedula</a>
                        
                                          @if($policy->image_tp && $policy->image_ci)
                                                <a class="dropdown-item" target="_blank" href="/user/pdf/{{$policy->id}}">Exportar</a>
                                                <a class="dropdown-item" target="_blank" href="/user/pdf-digital/{{$policy->id}}">Exportar Digital</a>
                                            @endif    
                        
                    @endif
                @else
                    {{-- Opciones cuando counter no es 0 --}}
                    <a class="dropdown-item" href="/user/index-policy/{{$policy->id}}">Procesar Cotización</a>
                    <a class="dropdown-item" target="_blank" href="/user/user-exportpdf/{{$policy->id}}">Exportar Cotización</a>
                @endif
                
                @if($policy->status == 1)
                    <a class="dropdown-item" href="/user/renew-policy/{{$policy->id}}">Renovar</a>
                @endif
            </div>
        @else
            <button class="btn btn-primary dropdown-toggle" type="button"
                id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false" disabled>
                Bloqueado
            </button>
        @endif
    </div>
</td>
                    </tr>

                                {{-- -------MODAL CARGAR DOCUMENTOS---------- --}}
                                <div class="modal fade" id="uploadDocumentsModal{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="uploadDocumentsModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadDocumentsModalLabel">Documentos del Vehiculo Placa: {{$policy->vehicle_registration}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="uploadDocumentsForm{{$policy->id}}" action="/user/upload-documents/{{$policy->id}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="image">Título de Propiedad</label>
                                                        <input type="file" name="image" id="image{{$policy->id}}" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="image1">Cédula o RIF</label>
                                                        <input type="file" name="image1" id="image1{{$policy->id}}" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Subir Documentos</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

            {{-- -------FIN MODAL CARGAR DOCUMENTOS---------- --}}
					@endforeach
				</tbody>
			</table>
			   {{ $policies-> links()}}

            {{-- MODAL FILTRAR POR FECHA --}}
            <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="filterModalLabel">Filtrar Pólizas por Fecha</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('user.filter.policies') }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="start_date">Fecha de Inicio</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                                <div class="form-group">
                                    <label for="end_date">Fecha de Fin</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            {{-- FIN MODAL FILTRAR POR FECHA --}}



		</div>
	</div>

</div>



@endsection
@section('scripts')
@foreach($policies as $policy)


<script>
    document.getElementById('uploadDocumentsForm{{$policy->id}}').addEventListener('submit', function(event) {
        var image = document.getElementById('image{{$policy->id}}').files[0];
        var image1 = document.getElementById('image1{{$policy->id}}').files[0];
        var maxSize = 2 * 1024 * 1024; // 2 MB

        if ((image && image.size > maxSize) || (image1 && image1.size > maxSize)) {
            alert('Uno o ambos archivos exceden el tamaño máximo de 2MB.');
            event.preventDefault(); // Evita que el formulario se envíe
        }
    });
    </script>
    @endforeach
<!--Script para que ejecute el modal al terminar de carga la pagina-->
<script>
    $( document ).ready(function() {
    $('#modal-pago').modal('toggle')
});

$( document ).ready(function() {
    $('#modal-pago2').modal('toggle')
});

<!--FIN Script para que ejecute el modal al terminar de carga la pagina-->
<!--Script para que ejecute el modal al terminar de carga la pagina-->

    // $(document).ready(function() {
    //     var status = {{ $status }};
    //     if (status == 1) {
    //         $('#modal-locked').modal('toggle');
    //     }
    // });

 // Script para mostrar/ocultar campos según el tipo de pago seleccionado
 </script>

<!--FIN Script para que ejecute el modal al terminar de carga la pagina-->
@endsection

@extends('layouts.app')
@section('module')
<a class="btn btn-primary shadow" href="{{ route('user.index.policies')}}">Polizas</a>
<div class="card shadow mb-4">
	<div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Solicitud de Polizas</h6>
          @if(Auth::user()->status == 0)
		<a class="btn btn-success float-right" href="{{ route('user.register.policy')}}">Nueva Solicitud</a>
		@endif
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
                        <th>N. Solicitud</th>
						<th>Asegurado</th>
						<th>C.I/RIF</th>
						<th>Vehiculo</th>
						<th>Placa</th>
						<th>Fecha de emisión</th>
                        <th>Recaudos</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($policies as $policy)
					<tr>
                        <td style="text-align: center;">{{$policy->id}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->client_ci}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>

                        @if(!$policy->image_tp || !$policy->image_ci)
                        <td class="text-danger">No posee</td>
                        @else
                        <td class="text-success">Cargados</td>
                        @endif



                        <td class="d-flex justify-content-center align-items-center">
                             

                                    <div class="dropdown mb-4">
                                         @if(Auth::user()->status == 0)
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Acciones
                                        </button>
                                       

                                        <div class="dropdown-menu animated--fade-in"
                                            aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="/user/edit-solicitud/{{$policy->id}}">Editar</a>
                                            @if($policy->image_tp > 1  || $policy->image_ci  > 1)
                                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="{{'#'."uploadDocumentsModal".$policy->id}}">Cargar Documentos</a>
                                            @else
                                            <a class="dropdown-item" href="/user/downloads-tp/{{$policy->id}}">Descargar Titulo</a>
                                            <a class="dropdown-item" href="/user/downloads-ci/{{$policy->id}}">Descargar Cedula</a>
                                            @endif

                                            <a class="dropdown-item" href="/user/index-policy/{{$policy->id}}">Procesar Solicitud</a>
                                            <a class="dropdown-item"  target="blank" href="/user/user-exportpdf/{{$policy->id}}">Exportar Solicitud</a>
                                           
                                        </div>
                                          @else
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
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
                                            <form id="uploadDocumentsForm{{$policy->id}}" action="/user/upload-documentss/{{$policy->id}}" method="POST" enctype="multipart/form-data">
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

				</tbody>
                @endforeach
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
        var maxSize = 2 * 5000 * 5000; // 2 MB

        if ((image && image.size > maxSize) || (image1 && image1.size > maxSize)) {
            alert('Uno o ambos archivos exceden el tamaño máximo de 2MB.');
            event.preventDefault();
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

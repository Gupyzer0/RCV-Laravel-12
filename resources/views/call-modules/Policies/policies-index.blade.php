@extends('layouts.call-modules')

@section('module')
<a class="btn btn-success shadow" href="{{ route('call.index.spolicies')}}">Solicitud de Polizas</a>

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Polizas</h6>
	</div>
<div class="navbar navbar-light float-right">
    @if($counter == 0)
  <form class="form-inline" action="{{route('mod.search.policies')}}" method="GET">
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
						<th>N°</th>
                        <th>Vendedor</th>                 
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
                        <td>{{$policy->user->name.' '.$policy->user->lastname}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->client_ci}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>                        
                        <td>@if(!$policy->image_tp)
                            <p class="text-danger">
                                Falta Título
                            </p>
                            @elseif(!$policy->image_ci)
                            <p class="text-danger">
                                Falta Cédula
                            </p>
                            @else
                            <p class="text-success">
                                Cargados
                            </p>
                            @endif  
                        </td>    
                       <td class="d-flex justify-content-center align-items-center">
                            @if(Auth::user()->status == 0)
                                <div class="dropdown mb-4">                                
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Acciones
                                        </button>

                                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                            @if(in_array(auth()->user()->type, [6, 2, 9]))
                                                <a class="dropdown-item" href="/user/edit-policy/{{$policy->id}}">Editar</a>
                                            @endif
                                            @if(!$policy->image_tp)
                                                <a class="dropdown-item" href="#" data-toggle="modal" 
                                                   data-target="{{'#'}}uploadTitleModal{{$policy->id}}">
                                                   Cargar Título
                                                </a>
                                            @else
                                            <a class="dropdown-item" href="/user/download-tp/{{$policy->id}}">Descargar Titulo</a>                                            
                                            @endif

                                            @if(!$policy->image_ci)
                                                <a class="dropdown-item" href="#" data-toggle="modal" 
                                                   data-target="{{'#'}}uploadCedulaModal{{$policy->id}}">
                                                   Cargar Cédula
                                                </a>
                                            @else
                                            <a class="dropdown-item" href="/user/download-ci/{{$policy->id}}">Descargar Cedula</a>
                                            @endif

                                            @if($policy->image_tp && $policy->image_ci)
                                                <a class="dropdown-item" target="_blank" href="/user/pdf/{{$policy->id}}">Exportar</a>
                                                <a class="dropdown-item" target="_blank" href="/user/pdf-digital/{{$policy->id}}">Exportar Digital</a>
                                            @endif                                                                                      
                                                
                                            @if($policy->status == 1)
                                                <a class="dropdown-item" href="/user/renew-policy/{{$policy->id}}">Renovar</a>
                                            @endif
                                        </div>
                                    
                                </div>
                            @else
                                <p>Bloqueado</p>
                            @endif
                        </td>
                    </tr>

                                {{-- -------MODAL CARGAR DOCUMENTOS---------- --}}
                                <div class="modal fade" id="uploadTitleModal{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="uploadTitleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadTitleModalLabel">Cargar Título de Propiedad - Placa: {{$policy->vehicle_registration}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="uploadTitleForm{{$policy->id}}" action="/user/upload-title/{{$policy->id}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="image">Título de Propiedad</label>
                                                        <input type="file" name="image" id="image{{$policy->id}}" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Subir Título</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="modal fade" id="uploadCedulaModal{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="uploadCedulaModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="uploadCedulaModalLabel">Cargar Cédula o RIF - Placa: {{$policy->vehicle_registration}}</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form id="uploadCedulaForm{{$policy->id}}" action="/user/upload-cedula/{{$policy->id}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="image">Cédula o RIF</label>
                                                        <input type="file" name="image" id="image{{$policy->id}}" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn btn-primary">Subir Cédula o RIF</button>
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

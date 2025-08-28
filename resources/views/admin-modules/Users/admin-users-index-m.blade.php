@extends('layouts.app')

@section('module')
<a class="btn btn-light shadow" href="{{ route('index.users.admins')}}">Ver Administradores</a>
<a class="btn btn-light shadow" href="{{ route('index.user.mod')}}">Ver Supervisores</a>
<a class="btn btn-light shadow" href="{{ route('index.users')}}">Ver usuarios</a>

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Usuarios</h6>
		<a class="btn btn-success float-right mb-2" href="{{ route('register.user')}}">Registrar Usuario</a>
			@if(Auth::user()->type == 0)
		<a class="btn btn-success float-right mb-2" href="{{ route('lock.region.user')}}">Bloquear</a>
		@endif
		<a class="float-right">&nbsp</a>
		  @if($users->count() > 0)
        <a class="btn btn-info float-right " href="{{ route('export.users')}}" target="_blank">Exportar Usuarios</a>
        @endif
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>

						<th scope="col">N°</th>
						<th scope="col">Vendedor</th>
						<th scope="col">Cedula</th>
						<th scope="col">Telefono</th>
						<th scope="col">Direccion</th>
						<th scope="col">Oficina</th>
                        <th scope="col">Contratos</th>                       
                        <th scope="col">Supervisor</th>
						<th scope="col">Fecha de Ingreso</th>
						<th scope="col">Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
					@if(!$user->deleted_at)
					<tr>
						{{-- <th scope="row">{{$counter = $counter + 1}}</th> --}}

						<td>{{$user->id}}</td>
						<td>{{$user->name.' '.$user->lastname}}</td>
						<td>{{$user->ci}}</td>
						<td>{{$user->phone_number}}</td>
						<td>{{$user->office->estado->estado. ', ' .$user->office->municipio->municipio. ', ' .$user->office->parroquia->parroquia}}</td>
						<td>{{$user->office->office_address}}</td>
                        <td>{{$user->ncontra}}</td>                       
                        @if ($user->mod_id)
                        <td>{{$user->moderator->name}}</td>
                        @else
                            <td></td>
                        @endif
						<td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y')}}</td>
						<td class="d-flex justify-content-center align-items-center">
    <div class="dropdown"> {{-- Eliminé la clase mb-4 si no la necesitas para el margen --}}
        <button class="btn btn-primary dropdown-toggle" type="button"
                id="dropdownMenuButton{{ $user->id }}" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            Acciones
        </button>
        {{-- Eliminé el "|" ya que no es parte estándar de un dropdown --}}
        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton{{ $user->id }}">
            {{-- Botón Ver --}}
            <a class="dropdown-item" href="/admin/index-user/{{$user->id}}">
                <i class="fas fa-eye fa-fw me-2" style="color: #f2a413;"></i> Ver
            </a>
            {{-- Botón Editar --}}
            <a class="dropdown-item" href="/admin/edit-user/{{$user->id}}">
                <i class="fas fa-edit fa-fw me-2 text-primary"></i> Editar
            </a>
            {{-- Botón Bloquear/Desbloquear (Condicional) --}}
            @if(!$user->status)
                <a class="dropdown-item text-danger" href="/admin/lock-user/{{$user->id}}">
                    <i class="fas fa-lock fa-fw me-2"></i> Bloquear
                </a>
            @else
                <a class="dropdown-item text-success" href="/admin/unlock-user/{{$user->id}}">
                    <i class="fas fa-lock-open fa-fw me-2"></i> Desbloquear
                </a>
            @endif
            {{-- Botón Descargar PDF --}}
            {{-- <a class="dropdown-item" href="/admin/pdf-user/{{$user->id}}" target="blank">
                <i class="fas fa-file-export fa-fw me-2" style="color: #5a5c69;"></i> Exportar PDF
            </a> --}}
            {{-- Botón Registro de Actividad --}}
            <a class="dropdown-item" href="/admin/activity-log/user/{{$user->id}}" target="blank">
                 <i class="fas fa-clipboard-list fa-fw me-2 text-success"></i> Registro Actividad
            </a>
            {{-- Botón Eliminar (Activa Modal) --}}
            {{-- Usamos un enlace <a> para que se vea como dropdown-item, pero con data-toggle --}}
            {{-- <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="{{'#'."deleteModal-".$user->id}}">
                <i class="fas fa-trash-alt fa-fw me-2"></i> Eliminar
            </a> --}}
            {{-- Botón Añadir Políticas (Activa Modal) --}}
             {{-- Usamos un enlace <a> para que se vea como dropdown-item, pero con data-toggle --}}
            <a class="dropdown-item text-success" href="#" data-toggle="modal" data-target="{{'#'."modaladdpolicies-".$user->id}}">
                <i class="fas fa-plus-circle fa-fw me-2"></i> Añadir Políticas
            </a>
        </div>
    </div>
</td>

					</tr>
					@endif
					<div class="modal fade" id="deleteModal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">eliminar</strong> este usuario?</h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">eliminar</span> este usuario</div>
								<div class="modal-footer">
									<form action="/admin/delete-user/{{$user->id}}" method="POST">
										@csrf
										@method('DELETE')
										<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
										<button type="submit" class="btn btn-primary">Continuar</button>
									</form>
								</div>
							</div>
						</div>
					</div>

                    <div class="modal fade" id="modaladdpolicies-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Introduzca cantidad de polizas a asignar</h5>
                              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                          </button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3" action="/admin/cant-contra/{{$user->id}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="GET">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <input autocomplete="off" type="number" placeholder="Cantidad de Polizas" class="form-control" name="numeroc1" id="numeroc1" >

                                        </div>
                                    </div>
                                 </div>
                              <div class="modal-footer">
                            <div class="col-auto">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Asignar</button>
                         </form>
                    </div>
					@endforeach
				</tbody>
			</table>

		</div>
	</div>
</div>

@endsection

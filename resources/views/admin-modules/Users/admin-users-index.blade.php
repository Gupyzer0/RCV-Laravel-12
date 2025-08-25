@extends('layouts.admin-modules')

@section('module')
<a class="btn btn-light shadow" href="{{ route('index.users.admins')}}">Ver Administradores</a>
<a class="btn btn-light shadow" href="{{ route('index.user.mod')}}">Ver Supervisores</a>
<a class="btn btn-light shadow" href="{{ route('index.users.m')}}">Ver Usuarios Matris</a>

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
						<td class="text-center">
							<a href="/admin/index-user/{{$user->id}}" class="btn bg-transparent pr-4" style="width: 5px; color: #f2a413;"><i class="fas fa-eye"></i></a>
							<a href="/admin/edit-user/{{$user->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;"><i class="fas fa-edit"></i></a>
                            @if(!$user->status)
                            <a href="/admin/lock-user/{{$user->id}}" class="btn bg-transparent text-danger pr-4" style="width: 5px;"><i class="fas fa-lock"></i></a>
                            @else
                            <a href="/admin/unlock-user/{{$user->id}}" class="btn bg-transparent text-success pr-4" style="width: 5px;"><i class="fas fa-lock-open"></i></a>
                            @endif

							<a href="/admin/pdf-user/{{$user->id}}" class="btn bg-transparent action-button pr-3 mt-1" target="blank"><i class="fas fa-file-export" style="width: 5px; color: #5a5c69;"></i></a>

							<a href="/admin/activity-log/user/{{$user->id}}" class="btn bg-transparent text-success pr-4" target="blank" style="width: 5px;"><i class="fas fa-clipboard-list"></i></a>
							<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$user->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span>
                            <span class="btn bg-transparent text-success pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."modaladdpolicies-".$user->id}}" style="width: 5px;"><i class="fas fa-plus-circle"></i></span>

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

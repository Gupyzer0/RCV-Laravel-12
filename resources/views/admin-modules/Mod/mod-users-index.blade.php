@extends('layouts.app')

@section('module')
<a class="btn btn-light shadow" href="{{ route('index.users.admins')}}">Ver Administradores</a>
<a class="btn btn-light shadow" href="{{ route('index.users')}}">Ver usuarios</a>
<a class="btn btn-light shadow" href="{{ route('index.users.deleted')}}">Ver Bloqueados</a>
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Supervisores</h6>
		<a class="btn btn-success float-right mb-2" href="{{ route('registers.mod')}}">Registrar Supervisor</a>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>

						<th scope="col">N°</th>
						<th scope="col">Nombre</th>
						<th scope="col">Cedula</th>
						<th scope="col">Telefono</th>
						<th scope="col">Direccion</th>
                        @if(Auth::user()->type == 4 || is_null(Auth::user()->type))
                        <th scope="col">Admin</th>
                        @endif
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
						<td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y')}}</td>
                        @if(Auth::user()->type == 4 || is_null(Auth::user()->type))
                        <td>
                            @if($user->type == 1)Eduardo @elseif($user->type == 2)Oriana @elseif($user->type == 3)Liliana @elseif($user->type == 4) Anais @elseif($user->type == 5) Lexaida @elseif($user->type == 6)
                                David
                            @endif
                        </td>
                        @endif
						<td class="text-center">

							<a href="/admin/edit-mod/{{$user->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;"><i class="fas fa-edit"></i></a>
                            <a href="/admin/activity-log/user/{{$user->id}}" class="btn bg-transparent text-success pr-4" style="width: 5px;"><i class="fas fa-clipboard-list"></i></a>
							<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$user->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span>
							<span class="btn bg-transparent text-warning pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."passmodal-".$user->id}}" style="width: 5px;"><i class="fas fa-key"></i></span>

						</td>
					</tr>
					@endif
					 {{-- MODAL CAMBIAR CLAVE --}}
                    <div class="modal fade" id="passmodal-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">Cambiar de contraseña</h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
                                <form action="/admin/edit-mod/password/{{$user->id}}" method="POST">
								<div class="modal-body">
                                    <div class="form-group col-md-12">
                                        <label for="password" class="col-form-label text-md-right">Nueva Contras2eña</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="off-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="password-confirm" class="col-form-label text-md-right">Confirmar Contraseña</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="off-password">
                                    </div>
                                </div>
								<div class="modal-footer">

										@csrf
										@method('PUT')
										<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
										<button type="submit" class="btn btn-primary">Cambiar</button>
									</form>
								</div>
							</div>
						</div>
					</div>
                    {{-- MODAL ELIMINAR --}}
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
									<form action="/admin/delete-mod/{{$user->id}}" method="POST">
										@csrf
										@method('DELETE')
										<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
										<button type="submit" class="btn btn-primary">Continuar</button>
									</form>
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

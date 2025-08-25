@extends('layouts.app')

@section('module')

<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Usuarios</h6>
		@if(in_array(auth()->user()->type, [2, 6, 9, 0]) || auth()->user()->id == 177)
            <a class="btn btn-success float-right mb-2" href="{{ route('register.user.mod') }}">Registrar Usuario</a>
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
                        <th scope="col">GoogleMaps</th>
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
                        @if($user->url)
                        <td>
                            <a class="btn btn-info" href="{{$user->url}}" target="blank">Ir</a>

                        </td>
                        @else
                        <td></td>
                        @endif
						<td>{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y')}}</td>
						<td class="text-center">
							<a href="/mod/pdf-user/{{$user->id}}" class="btn bg-transparent action-button pr-3 mt-1" target="blank"><i class="fas fa-file-export" style="width: 5px; color: #5a5c69;"></i></a>
							<a href="/admin/activity-log/user/{{$user->id}}" class="btn bg-transparent text-success pr-4" style="width: 5px;"><i class="fas fa-clipboard-list"></i></a>
							<a href="https://wa.me/58{{str_replace('-', '', $user->phone_number)}}" class="btn bg-transparent text-success pr-4" style="width: 5px;" target="blank"><i class="fab fa-whatsapp"></i></a>
                            <span class="btn bg-transparent text-success pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."modaladdpolicies-".$user->id}}" style="width: 5px;"><i class="fas fa-plus-circle"></i></span>
                            <span class="btn bg-transparent text-primary pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."modaleditcontra-".$user->id}}" style="width: 5px;"><i class="fas fa-edit"></i></i></span>
						</td>
					</tr>
					@endif



                    {{-- Asignar contratos --}}
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
                                  <form class="row g-3" action="/mod/cant-contram/{{$user->id}}" method="POST">
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
                              </div>
                          </div>
                        </div>
                    </div>

                    {{-- Editar cantidad de contratos --}}
                    <div class="modal fade" id="modaleditcontra-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Introduzca cantidad de polizas</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                  <form class="row g-3" action="/mod/edit-contram/{{$user->id}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="GET">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <input autocomplete="off" type="number" placeholder="Cantidad de Polizas" class="form-control" name="numeroc1" id="numeroc1" value="{{$user->ncontra }}" >
                                        </div>
                                    </div>
                                 </div>
                              <div class="modal-footer">
                                <div class="col-auto">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Editar</button>
                                    </form>
                                 </div>
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
<script>
    const google_maps_link = document.getElementById('google_maps_link');
    google_maps_link.addEventListener('keyup', () => {
            let urlPattern = /https?:\/\/(?:www\.)?google\.com\/maps(?:\/.*)?@[-?\d.]+,[-?\d.]+/;
            let inputValue = google_maps_link.value.trim();

            if (inputValue === "") {
                google_maps_link.classList.remove('is-valid');
                google_maps_link.classList.add('is-invalid');
            } else if (urlPattern.test(inputValue)) {
                google_maps_link.classList.remove('is-invalid');
                google_maps_link.classList.add('is-valid');
            } else {
                google_maps_link.classList.remove('is-valid');
                google_maps_link.classList.add('is-invalid');
            }
        });

</script>
@endsection

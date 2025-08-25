@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Oficinas</h6>
		<a class="btn btn-success float-right" href="{{ route('register.office.mod')}}">Registrar oficina</a>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th scope="col">N°</th>
						<th scope="col">Direccion</th>
						<th scope="col">Estado</th>
						<th scope="col">Municipio</th>
						<th scope="col">Parroquia</th>
						<th scope="col">Acciones</th>

					</tr>
				</thead>
				<tbody>
					@foreach($offices as $office)
					@if(!$office->deleted_at)

					<tr>

						<td>{{$office->id}}</td>
						<td>{{$office->office_address}}</td>
						<td>{{$office->estado->estado}}</td>
						<td>{{$office->municipio->municipio}}</td>
						<td>{{$office->parroquia->parroquia}}</td>
                        {{-- BOTONES DE ACCION--}}
						<td class="col-1">
							<a href="/mod/edit-office/{{$office->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;"><i class="fas fa-edit"></i></a>							

						</td>
					</tr>
                    @endif

                    
                    {{-- FIN MODAL --}}
                    @endforeach
        </div>
  </div>

				</tbody>

			</table>

</div>
@endsection

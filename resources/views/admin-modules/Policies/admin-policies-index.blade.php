@extends('layouts.app')

@section('module')
<a class="btn btn-light text-danger shadow mb-2" href="{{ route('index.vencida')}}">Polizas Vencidas</a>

<!-- card index polizas --> 
<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Pólizas</h6>
        @if(auth::user()->id == 999502)
			<a class="btn btn-success float-right ml-2" href="{{route('deleted.policys')}}" target="_bank">Eliminar</a>
        @endif
        <form class="form-inline float-right" action="{{route('search.policies')}}" method="GET">
            <input class="form-control mr-sm-2" name="texto" type="search" placeholder="Placa o C.I" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
	</div>

	<nav class="navbar navbar-light float-right"></nav>

	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
				<thead>
					<tr>
						<th>N. Poliza</th>
						<th>N° Cotización</th>
						<th>Vendedor</th>
						<th>Tomador</th>
						<th>C.I/RIF</th>
						<th>Vehiculo</th>
						<th>Placa</th>
						<th>Fecha de Emisión</th>
						<th>Estatus</th>
						<th>Recaudos</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($policies as $policy)
						<tr>
							<td>{{ $policy->id }}</td>
							<td>{{ $policy->idp }}</td>
							@if(isset($policy->admin_id))
								<td>Administrador</td>
							@else
								<td>{{$policy->user->name.' '.$policy->user->lastname}}</td>
							@endif
							<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
							<td>{{$policy->client_ci}}</td>
							<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
							<td>{{$policy->vehicle_registration}}</td>
							<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
							@if(Carbon\Carbon::parse($policy->expiring_date) > $today)
								<td class="text-success">Vigente</td>
							@elseif(Carbon\Carbon::parse($policy->expiring_date) == $today)
								<td class="text-warning">Vence hoy</td>
							@else
								<td class="text-danger">Vencida</td>
							@endif
							
							@if(!$policy->image_tp)
								<td class="text-danger">No posee</td>
							@else
								<td class="text-success">Cargados</td>
							@endif
							<td class="p-0">
								<a href="/admin/index-policy/{{$policy->id}}" class="btn bg-transparent action-button pr-3 mt-1" style="width: 5px; color: #f2a413;"><i class="fas fa-eye"></i></a>
								@if($policy->facturado)
									<a href="{{ route('facturacion.descargar-factura',$policy->id) }}" class="btn bg-transparent action-button pr-3 mt-1" style="width: 5px; color: #f2a413;" target="_blank"><i class="fas fa-file-invoice-dollar"></i></a>
								@endif
								@if(!$policy->damage_things)
									<a href="/admin/edit-policy/{{$policy->id}}" class="btn bg-transparent text-primary action-button pr-3 mt-1" style="width: 5px;"><i class="fas fa-edit"></i></a>
								@endif
								{{-- Button to open delete modal--}}
								<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$policy->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span> 
								@if(!$policy->status)
									@if($policy->statusu)
										<span class="btn bg-transparent text-success pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."restoreModal-".$policy->id}}" style="width: 5px;"><i class="fas fa-trash-restore"></i></span>
									@else
										<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."AnularModal-".$policy->id}}" style="width: 5px;"><i class="fas fa-bullhorn"></i></span>
									@endif
								@endif
							</td>
						</tr>

						<!-- modal eliminar -->
						<div class="modal fade" id="deleteModal-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">eliminar</strong> esta poliza?</h5>
										<button class="close" type="button" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">eliminar</span> esta poliza</div>
									<div class="modal-footer">
										<form action="/admin/delete-policy/{{$policy->id}}" method="POST">
											@csrf
											@method('DELETE')
											<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-primary">Continuar</button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- /modal eliminar -->

						<!-- modal desanular -->
						<div class="modal fade" id="restoreModal-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-info">revocar la anulación</strong> de esta poliza?</h5>
										<button class="close" type="button" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">Seleccione "continuar" si desea <span class="text-info">revocar la anulación</span> de esta poliza</div>
									<div class="modal-footer">
										<form action="/admin/restore-policy/{{$policy->id}}" method="POST">
											@csrf
											@method('POST')
											<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-primary">Continuar</button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- /modal desanular -->

						<!-- modal anular -->
						<div class="modal fade" id="AnularModal-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">anular</strong> esta poliza?</h5>
										<button class="close" type="button" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">anular</span> esta poliza</div>
									<div class="modal-footer">
										<form action="/admin/anular-policy/{{$policy->id}}" method="POST">
											@csrf
											@method('POST')
											<button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
											<button type="submit" class="btn btn-primary">Continuar</button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- /modal anular -->
					@endforeach
				</tbody>
			</table>
			{{ $policies-> links()}}
		</div>
	</div>
</div>
<!-- /card index polizas --> 

@endsection

@extends('layouts.app')

@section('module')

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Pólizas</h6>

	</div>
	<nav class="navbar navbar-light float-right">
  <form class="form-inline" action="{{route('mod.search.policies')}}" method="GET">
    <input class="form-control mr-sm-2" name="texto" type="search" placeholder="N° Poliza o C.I" aria-label="Search">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
  </form>
</nav>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="font-size: 13px;">
				<thead>
					<tr>
						<th>N. Poliza</th>
						<th>Vendedor</th>
						<th>Asegurado</th>
						<th>C.I/RIF</th>
						<th>Vehiculo</th>
						<th>Placa</th>
						<th>Tipo Plan</th>
						<th>Fecha de Emisión</th>
						<th>Estatus</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach($policies->sortBy('created_at')  as $policy)
					<tr>
                       
                      <td>{{$policy->id}}</td>                      
						@if(isset($policy->admin_id))
						<td>Administrador</td>
						@else
						<td>{{$policy->user->name.' '.$policy->user->lastname}}</td>
						@endif
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->client_ci}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
                        @if(!$policy->damage_things)
						<td>{{$policy->price->description.' '.$policy->price->total_premium}} </td>
                        @else
                        <td>Plan Viejo</td>
                        @endif
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
                        @if(!$policy->statusu)
						@if(Carbon\Carbon::parse($policy->expiring_date) > $today)
						<td class="text-success">Vigente</td>
						@elseif(Carbon\Carbon::parse($policy->expiring_date) == $today)
						<td class="text-warning">Vence hoy</td>
						@else
						<td class="text-danger">Vencida</td>
						@endif
                        @else
                        <td class="text-danger">Anulada</td>
                        @endif
					 <td class="d-flex justify-content-center align-items-center">  
                                    <div class="dropdown mb-4">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Acciones
                                        </button>

                                        <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">      
                                            <a class="dropdown-item" href="/mod/index-policy/{{$policy->id}}">Ver</a>
                                           
                                             @if(in_array(auth()->user()->type, [4, 6, 9, 1]))
                                            <a class="dropdown-item" href="/mod/edit-policyd/{{$policy->id}}">Editar</a>  
                                            @endif
                                     
                                            @if($policy->image_tp && $policy->image_ci)                                            
                                            <a class="dropdown-item"  target="_blank" href="/mod/pdf/{{$policy->id}}">Imprimir</a>
                                            <a class="dropdown-item"  target="_blank" href="/mod/pdf-digital/{{$policy->id}}">Imprimir Digital</a>
                                            @endif    
                                            @if($policy->statusu)
                                            <a class="dropdown-item" href="/mod/edit-policy/{{$policy->id}}">Editar</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="{{'#'."restoreModal-".$policy->id}}">Renovar</a>
                                            <a class="dropdown-item" data-toggle="modal" data-target="{{'#'."AnularModal-".$policy->id}}">Anual</a>                                            
							                @endif                                       
                                            
                                        </div>
                                    </div>                         
                        </td>
					</tr>


 {{-- MODAL DESANULAR --}}
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
                <form action="/mod/restored-policy/{{$policy->id}}" method="POST">
                    @csrf
                    @method('POST')
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Continuar</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ANULAR --}}

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
                <form action="/mod/anular-policy/{{$policy->id}}" method="POST">
                    @csrf
                    @method('POST')
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
			{{ $policies-> links()}}
		</div>
	</div>
</div>
@endsection

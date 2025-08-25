@extends('layouts.admin-modules')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Registro de Actividad</h6>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table text-center" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<td><strong>Descripción</strong></td>
					<td><strong>Acción</strong></td>
					<td><strong>Identificador</strong></td>
					<td><strong>Usuario</strong></td>
					<td><strong>Fecha</strong></td>
				</thead>
				<tbody>
					@foreach($activities1 as $activity)
					<tr>
						@if($activity->log_name <> 'Incio de sesion')
                        <td>{{$activity->log_name}}</td>
                        @if($activity->description === 'created')
						<td class="text-success">Crear</i></td>
						@elseif($activity->description === 'updated')
						<td class="text-primary">Actualizar</i></td>
						@elseif($activity->description === 'deleted')
						<td class="text-danger">Eliminar</td>
						@elseif($activity->description === 'Exitoso')
						<td class="text-info">Exitoso</td>
						@endif
                        <td>{{$activity->subject_id}}</td>
						<td>Admin: {{$activity->admin->name}}</td>
						<td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d-m-Y H:i:s')}}</td>
                        @endif
					</tr>
					@endforeach
					@foreach($activities2 as $activity)
					<tr>
					<td>{{$activity->log_name}}</td>
						@if($activity->description === 'created')
						<td class="text-success">Crear</i></td>
						@elseif($activity->description === 'updated')
						<td class="text-primary">Actualizar</i></td>
						@elseif($activity->description === 'deleted')
						<td class="text-danger">Eliminar</td>
						@elseif($activity->description === 'Exitoso')
						<td class="text-info">Exitoso</td>
						@endif
						<td>{{$activity->subject_id}}</td>
						<td>User: {{$activity->user->name.' '.$activity->user->lastname}}</td>
						<td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d-m-Y H:i:s')}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection

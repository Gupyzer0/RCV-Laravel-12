@extends('layouts.app')

@section('module')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Precios</h6>
		<a class="btn btn-success float-right" href="{{ route('register.price')}}">Nuevo Plan</a>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>ID</th>
						<th>Clase de vehículo</th>
						<th>Descripción del Plan</th>
						{{-- <th>Oficina</th> --}}
						<th>Precio €</th>
                        <th>Acciones</th>

					</tr>
				</thead>
				<tbody>
					@foreach($prices as $price)
					<tr>
						<td>{{$price->id}}</td>
						<td>{{$price->class->class}}</td>
						 <td>{{$price->description}}</td>
						  {{-- <td>{{$price->office->office_address}}</td> --}}
						<td><strong> (</strong>{{number_format($price->total_premium, 2)}} €<strong>)</strong> {{number_format($price->total_premium * $foreign_reference, 2)}} <strong> Bs </strong></td>
                        <td class="text-center col-2">
							{{-- <a href="/admin/index-price/{{$price->id}}" class="btn bg-transparent pr-4" style="width: 5px; color: #f2a413;"><i class="fas fa-eye"></i></a> --}}
							<a href="/admin/edit-price/{{$price->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;"><i class="fas fa-edit"></i></a>
                             <a href="/admin/export-price/{{$price->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;" target="blank"><i class="fas fa-file-export" style="width: 5px; color: #5a5c69;"></i></a> 
							<span class="btn bg-transparent text-danger pr-4" id="openModal" data-toggle="modal" data-target="{{'#'."deleteModal-".$price->id}}" style="width: 5px;"><i class="fas fa-trash-alt"></i></span>

						</td>

					</tr>
                    <div class="modal fade" id="deleteModal-{{$price->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea <strong class="text-danger">eliminar</strong> este plan?</h5>
									<button class="close" type="button" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="modal-body">Seleccione "continuar" si desea <span class="text-danger">eliminar</span> este plan</div>
								<div class="modal-footer">
									<form action="/admin/delete-price/{{$price->id}}" method="POST">
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

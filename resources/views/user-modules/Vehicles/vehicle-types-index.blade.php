@extends('layouts.app')

@section('module')
<a class="btn btn-light shadow" href="{{ route('user.index.vehicles') }}">Ver vehículos</a>
<div class="card shadow mb-4">
  <div class="card-header py-2">
    <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Tipos de Vehículo</h6>
    <a class="btn btn-success float-right" href="{{ route('user.register.type') }}">Registrar Tipo</a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Tipo</th>
            <th>Acciones</th>     
          </tr>
        </thead>
        <tbody>
          @foreach($vehicle_types as $type)
          @if(!$type->deleted_at)
          <tr>
            <td>{{$type->type}}</td>
            <td class="text-center">  
              <a href="/user/edit-type/{{$type->id}}" class="btn bg-transparent text-primary pr-4" style="width: 5px;"><i class="fas fa-edit"></i></a>
            </td>
          </tr>
          @endif
          @endforeach
        </tbody>

      </table>
    </div>
  </div>
</div>        
@endsection
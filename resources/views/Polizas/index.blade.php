@extends('layouts.app')

@section('module')

<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Pólizas</h6>
    </div>
    <nav class="navbar navbar-light float-right">
        <form class="form-inline" action="{{route('polizas.index')}}" method="GET">
            <input class="form-control mr-sm-2" name="filtro_poliza" type="search" placeholder="N° Poliza" aria-label="Search" value="{{ $filtro_poliza ?? '' }}">
            <input class="form-control mr-sm-2" name="filtro_cedula" type="search" placeholder="C.I" aria-label="Search" value="{{ $filtro_cedula ?? '' }}">
            <input class="form-control mr-sm-2" name="filtro_placa" type="search" placeholder="Placa" aria-label="Search" value="{{ $filtro_placa ?? '' }}">
            <select class="form-control mr-sm-2" name="filtro_estatus">
                <option value="">Estatus</option>
                <option value="vigente" {{ $filtro_estatus == 'vigente' ? 'selected':'' }} >Vigente</option>
                <option value="vence hoy" {{ $filtro_estatus == 'vence hoy' ? 'selected':'' }}>Vence hoy</option>
                <option value="vencida" {{ $filtro_estatus == 'vencida' ? 'selected':'' }}>Vencida</option>
                <option value="anulada" {{ $filtro_estatus == 'anulada' ? 'selected':'' }}>Anulada</option>
            </select>
            {{-- Por lógica solo los administradores y moderadores necesitan un filtro por vendedor --}}
            @hasanyrole('administrador|moderador')
            <select class="form-control mr-sm-2" name="filtro_vendedor">
                <option value="">Vendedor</option>
                @foreach($vendedores as $vendedor)
                    <option value="{{ $vendedor->id }}" {{ $filtro_vendedor == $vendedor->id ? 'selected':'' }} >{{ $vendedor->nombre_completo }}</option>
                @endforeach
            </select>
            @endhasanyrole
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Buscar</button>
        </form>
    </nav>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0" style="font-size: 13px;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>N. Poliza</th>
                        <th>Vendedor</th>
                        <th>Asegurado</th>
                        <th>C.I/RIF</th>
                        <th>Vehiculo</th>
                        <th>Placa</th>
                        <th>Tipo Plan</th>
                        <th>Fecha de Emisión</th>
                        <th>Estatus</th>
                        <th>Recaudos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($polizas as $poliza)
                        <tr>
                            <td>{{ $poliza->id }}</td>
                            <td>{{ $poliza->idp }}</td>
                            @if(isset($poliza->admin_id))
                                <td>Administrador</td>
                            @else
                                <td>{{$poliza->user->name.' '.$poliza->user->lastname}}</td>
                            @endif
                            <td>{{$poliza->client_name.' '.$poliza->client_lastname}}</td>
                            <td>{{$poliza->client_ci}}</td>
                            <td>{{$poliza->vehicle_brand. ' '.$poliza->vehicle_model}}</td>
                            <td>{{$poliza->vehicle_registration}}</td>
                            @if(!$poliza->damage_things)
                                <td>{{$poliza->price->description.' '.$poliza->price->total_premium}} </td>
                            @else
                                <td>Plan Viejo</td>
                            @endif
                            <td>{{ $poliza->created_at->format('d-m-Y') }}</td>
                            @if(!$poliza->statusu)
                                @if(Carbon\Carbon::parse($poliza->expiring_date) > $today)
                                    <td class="text-success">Vigente</td>
                                @elseif(Carbon\Carbon::parse($poliza->expiring_date) == $today)
                                    <td class="text-warning">Vence hoy</td>
                                @else
                                    <td class="text-danger">Vencida</td>
                                @endif
                            @else
                                <td class="text-danger">Anulada</td>
                            @endif

                            @if(!$poliza->image_tp)
                                <td class="text-danger">No posee</td>
                            @else
                                <td class="text-success">Cargados</td>
                            @endif

                            <td class="d-flex justify-content-center align-items-center">
                                <div class="dropdown mb-4">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>

                                    <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="{{ route('polizas.show', $poliza->id) }}">Ver</a>
                                        
                                        @can('update',$poliza)
                                            <a class="dropdown-item" href="{{ route('polizas.edit', $poliza->id) }}">Editar</a>
                                        @endcan

                                        @can('delete', $poliza)
                                            <a class="dropdown-item" data-toggle="modal" data-target="{{'#'."deleteModal-".$poliza->id}}" href="#">Eliminar</a>
                                            @if(!$poliza->status)
                                                @if($poliza->statusu)
                                                    <a class="dropdown-item" data-toggle="modal" data-target="{{'#'."restoreModal-".$poliza->id}}" href="#">Desanular</a>
                                                @else
                                                    <a class="dropdown-item" data-toggle="modal" data-target="{{'#'."AnularModal-".$poliza->id}}" href="#">Anular</a>
                                                @endif
                                            @endif
                                        @endcan

                                        @if($poliza->image_tp && $poliza->image_ci)
                                            <a class="dropdown-item" target="_blank" href="/mod/pdf/{{$poliza->id}}">Imprimir</a>
                                            <a class="dropdown-item" target="_blank" href="/mod/pdf-digital/{{$poliza->id}}">Imprimir Digital</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- modal eliminar -->
						@include('Polizas._modal_eliminar')
						<!-- /modal eliminar -->

                        <!-- modal anular -->
						@include('Polizas._modal_anular')
						<!-- /modal anular -->

						<!-- modal desanular -->
						@include('Polizas._modal_desanular')
						<!-- /modal desanular -->
                    @endforeach
                </tbody>
            </table>
            {{ $polizas->links() }}
        </div>
    </div>
</div>
@endsection
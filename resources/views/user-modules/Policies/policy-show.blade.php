@extends('layouts.app')

@section('module')
    <div class="card">
    <div class="card-header">
        <a href="{{route('user.index.spolicies')}}" class="nav-link bg-danger float-right active ml-2 text-white">Volver</a>
        <ul class="nav nav-pills card-header-pills">
        <li class="nav-item">
            <a class="nav-link bg-dark active" href="/user/user-exportpdf/{{$policy->id}}" target="blank">Exportar Cotización</a>
        </li>

        <li class="nav-item">
        <a class="nav-link ml-2 bg-success text-white active" href="/user/edit-solicitud/{{$policy->id}}">Editar Poliza</a>
        </li>
        </ul>
    </div>

    <div class="card-body">
    <h3 class="card-title text-center">Datos de afiliacion</h3>
    <div class="row border-bottom border-dark mb-4">
        <div class="col-4 text-center">
            @if($policy->idp)
            <h6><span class="font-weight-bold mr-2">Número de Afiliación: </span>{{$policy->idp}}</h6>
            @else
            <h6><span class="font-weight-bold mr-2">Número de Afiliación: </span>{{$policy->id}}</h6>
            @endif

        </div>
        <div class="col-4 text-center">
        <h6><span class="font-weight-bold mr-2">Emisión: </span>{{\Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</h6>
        </div>
        <div class="col-4 text-center">
        <h6><span class="font-weight-bold mr-2">Vencimiento: </span>
            @if($expiring_date > $today)
            <span class="text-success">{{\Carbon\Carbon::parse($policy->expiring_date)->format('d-m-Y')}}</h6>
            </span>
            @elseif($expiring_date == $today)
            <span class="text-warning">{{\Carbon\Carbon::parse($policy->expiring_date)->format('d-m-Y')}}</h6>
            </span>
            @elseif($expiring_date < $today)
            <span class="text-danger">{{\Carbon\Carbon::parse($policy->expiring_date)->format('d-m-Y')}}</h6>
            </span>
            @endif
        </div>
        </div>

        <h3 class="card-title text-center">Datos del Asegurado</h3>
        <div class="row border-bottom border-dark mb-4">
        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2">Nombres y Apellidos: </span>{{$policy->client_name_contractor. " " .$policy->client_lastname_contractor}}</h6>
        </div>
        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2">Rif/Cedula: </span>{{$policy->client_ci_contractor}}</h6>
        </div>
        </div>

        <h3 class="card-title text-center">Datos del Tomador</h3>
        <div class="row border-bottom border-dark mb-4">
        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2">Nombres y Apellidos: </span>{{$policy->client_name. " " .$policy->client_lastname}}</h6>
        </div>
        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2">Rif/Cédula: </span>{{$policy->client_ci}}</h6>
        </div>
        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2">Teléfono: </span>{{'0'.$policy->client_phone}}</h6>
        </div>
        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2" style="font-size: 14px;">email: </span>{{$policy->client_email}}</h6>
        </div>


        <div class="col-4 text-center">
            <h6><span class="font-weight-bold mr-2">Dirección: </span>{{$policy->estado->estado.', '.$policy->municipio->municipio.', '.$policy->parroquia->parroquia.'.'}}</h6>
        </div>

        <div class="col-4 text-center">
            <h6>{{$policy->client_address}}</h6>
        </div>

    </div>
    <h3 class="card-title text-center">Datos del Vehiculo</h3>
        <div class="row border-bottom border-dark mb-4">
        <div class="col-6 text-center border-right border-dark">
            <h6><span class="font-weight-bold mr-2">Marca: </span>{{$policy->vehicle_brand}}</h6>
            <h6><span class="font-weight-bold mr-2">Modelo: </span>{{$policy->vehicle_model}}</h6>
            <h6><span class="font-weight-bold mr-2">Tipo: </span>{{$policy->vehicle_type}}</h6>
            <h6><span class="font-weight-bold mr-2">Año: </span>{{$policy->vehicle_year}}</h6>
            <h6><span class="font-weight-bold mr-2">Color: </span>{{$policy->vehicle_color}}</h6>
            <h6><span class="font-weight-bold mr-2">Peso: </span>{{$policy->vehicle_weight}}</h6>
        </div>
        <div class="col-6 text-center">
            <h6><span class="font-weight-bold mr-2">Número de puestos: </span>{{$policy->vehicle_certificate_number}}</h6>
            <h6><span class="font-weight-bold mr-2">Placa: </span>{{$policy->vehicle_registration}}</h6>
            <h6><span class="font-weight-bold mr-2">Serial motor: </span>{{$policy->vehicle_motor_serial}}</h6>
            <h6><span class="font-weight-bold mr-2">Serial de carroceria: </span>{{$policy->vehicle_bodywork_serial}}</h6>
            <h6><span class="font-weight-bold mr-2">Uso: </span>{{$policy->used_for}}</h6>
            <h6><span class="font-weight-bold mr-2">Clase de vehículo: </span>{{$policy->type}}</h6>
        </div>
        </div>

        <h3 class="card-title text-center">Descripción Póliza</h3>
        <h5 class="card-subtitle text-center mt-2 mb-3">{{$policy->price->description}}</h5>
        <div class="row border-bottom border-dark mb-4">
            <div class="col-6 text-center border-right border-dark">
                @if($policy->price->campoc ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo ?? ''}}  </span>{{$policy->price->campoc * $foreign_reference}} Bs.S</h6>
                @endif

                @if($policy->price->campoc1 ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo1 ?? ''}} </span>{{$policy->price->campoc1 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($policy->price->campoc2 ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo2 ?? ''}} </span>{{$policy->price->campoc2 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($policy->price->campoc3 ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo3 ?? ''}} </span>{{$policy->price->campoc3 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($policy->price->campoc4 ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo4 ?? ''}} </span>{{$policy->price->campoc4 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($policy->price->campoc5 ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo5 ?? ''}} </span>{{$policy->price->campoc5 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($policy->price->campoc6 ?? false)
                    <h6><span class="font-weight-bold mr-2">{{$policy->price->campo6 ?? ''}} </span>{{$policy->price->campoc6 * $foreign_reference}} Bs.S</h6>
                @endif

            </div>
            
            <div class="col-6 text-center">
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop * $foreign_reference, 2)}} Bs.S</h6>
                @if($policy->price->campoc1 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop1 * $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($policy->price->campoc2 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop2 * $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($policy->price->campoc3 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop3 * $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($policy->price->campoc4 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop4 * $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($policy->price->campoc5 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop5 * $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($policy->price->campoc6 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($policy->price->campop6 * $foreign_reference, 2)}} Bs.S</h6>
                @endif
                <h6 class="mt-5"><span class="font-weight-bold mr-2">Total Prima: </span> {{ number_format($policy->total_premium * $foreign_reference, 2)}} Bs.S</h6>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12">
                @if(auth()->user()->type == 3)
                    <a class="nav-link btn btn-block btn-success text-white" 
                        data-toggle="modal" 
                        data-target="#payModal{{ $policy->id }}">
                        Procesar la Póliza
                    </a>
                @else
                    <a class="nav-link btn btn-block btn-success text-white" href="/user/proce-p/{{$policy->id}}">
                        Procesar la Póliza
                    </a>
                @endif
            </div>
        </div>
        @include('user-modules.partials.modal-show-policies')
        </div>
    </div>
    {{-- fin modal reporte de pago  --}}

@endsection

@section('scripts')
    <script src="{{asset('js/simple-mask-money.js')}}"></script>
    <!--Script para que ejecute el modal al terminar de carga la pagina-->
    <script>
        $( document ).ready(function() {
            $('#modal-poliza').modal('toggle')
        });
    </script>
@endsection

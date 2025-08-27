@extends('layouts.app')

@section('module')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-pills card-header-pills">

            <li class="nav-item">
                <a class="nav-link bg-dark active" href="{{ route('polizas.pdf', $poliza) }}" target="blank">
                    Exportar PDF
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link ml-2 bg-succes active" href="{{ route('polizas.pdf-digital', $poliza) }}" target="blank">
                    Exportar PDF-DIGITAL
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('facturacion.descargar-factura',$poliza) }}" class="nav-link ml-2 bg-succes active" target="_blank">
                    Mostrar Factura
                </a>
            </li>

            @can('update', $poliza)
                <li class="nav-item">
                    <a class="nav-link ml-2 bg-warning text-dark active" href="{{ route('polizas.edit', $poliza) }}">
                        Editar Poliza
                    </a>
                </li>
            @endcan

            @can('delete', $poliza)
                <li class="nav-item">
                    <form action="{{ route('polizas.delete', $poliza) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="nav-link ml-2 btn btn-danger">Eliminar</button>
                    </form>
                </li>
            @endcan

            <li class="nav-item ml-auto">
                <a href="javascript:history.back()" class="nav-link bg-danger active">X</a>
            </li>
            
        </ul>
    </div>

    <div class="card-body">
        <h3 class="card-title text-center">Datos de Afiliación</h3>
        <div class="row border-bottom border-dark mb-4">
            <div class="col-4 text-center">
                @if($poliza->idp) {{-- TODO wtf --}}
                    <h6><span class="font-weight-bold mr-2">Número de Afiliación: </span>{{$poliza->idp}}</h6>
                @else
                    <h6><span class="font-weight-bold mr-2">Número de Afiliación: </span>{{$poliza->id}}</h6>
                @endif

            </div>
            <div class="col-4 text-center">
                <h6><span class="font-weight-bold mr-2">Emisión:
                    </span>{{\Carbon\Carbon::parse($poliza->created_at)->format('d-m-Y')}}</h6>
            </div>
            <div class="col-4 text-center">
                <h6><span class="font-weight-bold mr-2">Vencimiento: </span>
                    @if($poliza->expiring_date > $today)
                    <span class="text-success">{{\Carbon\Carbon::parse($poliza->expiring_date)->format('d-m-Y')}}
                </h6>
                </span>
                @elseif($expiring_date == $today)
                <span class="text-warning">{{\Carbon\Carbon::parse($poliza->expiring_date)->format('d-m-Y')}}</h6>
                </span>
                @elseif($expiring_date < $today) <span class="text-danger">
                    {{\Carbon\Carbon::parse($poliza->expiring_date)->format('d-m-Y')}}</h6>
                    </span>
                    @endif
            </div>
        </div>

        <h3 class="card-title text-center">Datos del Vendedor</h3>
        <div class="row border-bottom border-dark mb-4">
            <div class="col-4 text-center">
                @if(isset($poliza->user_id))
                <h6><span class="font-weight-bold mr-2">Contratante: </span>{{$poliza->user->name . " " .
                    $poliza->user->lastname}}</h6>
                @else
                <h6><span class="font-weight-bold mr-2">Contratante: </span>Administrador</h6>
                @endif
            </div>
            <div class="col-4 text-center">
                @if(isset($poliza->user_id))
                <h6><span class="font-weight-bold mr-2">Rif/Cedula: </span>{{$poliza->user->ci}}</h6>
                @else
                <h6><span class="font-weight-bold mr-2">Rif/Cedula: </span>Administrador</h6>
                @endif
            </div>
            <div class="col-4 text-center">
                @if(isset($poliza->user_id))
                <h6><span class="font-weight-bold mr-2">Teléfono: </span>{{$poliza->user->phone_number}}</h6>
                @else
                <h6><span class="font-weight-bold mr-2">Teléfono: </span>Administrador</h6>
                @endif
            </div>
        </div>

        <h3 class="card-title text-center">Datos del Cliente</h3>
        <div class="row">
            <div class="col-3 text-center">
                <h6><span class="font-weight-bold mr-2">Benificiario: </span>{{$poliza->client_name. " "
                    .$poliza->client_lastname}}</h6>
            </div>
            <div class="col-3 text-center">
                <h6><span class="font-weight-bold mr-2">Rif/Cédula: </span>{{$poliza->client_ci}}</h6>
            </div>
            <div class="col-3 text-center">
                <h6><span class="font-weight-bold mr-2">Teléfono: </span>{{'0'.$poliza->client_phone}}</h6>
            </div>
            <div class="col-3 text-center">
                <h6><span class="font-weight-bold mr-2" style="font-size: 14px;">Email: </span>{{$poliza->client_email}}
                </h6>
            </div>
        </div>


        <div class="row">
            <div class="col-6 text-center">
                <h6><span class="font-weight-bold mr-2">Dirección: </span>{{$poliza->estado->estado.',
                    '.$poliza->municipio->municipio.', '.$poliza->parroquia->parroquia.'.'}}</h6>
            </div>

            <div class="col-6 text-center">
                <h6>{{$poliza->client_address}}</h6>
            </div>
        </div>

        <div class="row border-bottom border-dark mb-4">
            <div class="col-6 text-center">
                <h6><span class="font-weight-bold mr-2">Contratante: </span>{{$poliza->client_name_contractor. " "
                    .$poliza->client_lastname_contractor}}</h6>
            </div>
            <div class="col-6 text-center">
                <h6><span class="font-weight-bold mr-2">Rif/Cédula: </span>{{$poliza->client_ci_contractor}}</h6>
            </div>
        </div>

        <h3 class="card-title text-center">Datos del Vehiculo</h3>
        <div class="row border-bottom border-dark mb-4">
            <div class="col-6 text-center border-right border-dark">
                <h6><span class="font-weight-bold mr-2">Marca: </span>{{$poliza->vehicle_brand}}</h6>
                <h6><span class="font-weight-bold mr-2">Modelo: </span>{{$poliza->vehicle_model}}</h6>
                <h6><span class="font-weight-bold mr-2">Tipo: </span>{{$poliza->vehicle_type}}</h6>
                <h6><span class="font-weight-bold mr-2">Año: </span>{{$poliza->vehicle_year}}</h6>
                <h6><span class="font-weight-bold mr-2">Color: </span>{{$poliza->vehicle_color}}</h6>
                <h6><span class="font-weight-bold mr-2">Peso: </span>{{$poliza->vehicle_weight}}</h6>
            </div>
            <div class="col-6 text-center">
                <h6><span class="font-weight-bold mr-2">Número de certificado:
                    </span>{{$poliza->vehicle_certificate_number}}</h6>
                <h6><span class="font-weight-bold mr-2">Placa: </span>{{$poliza->vehicle_registration}}</h6>
                <h6><span class="font-weight-bold mr-2">Serial motor: </span>{{$poliza->vehicle_motor_serial}}</h6>
                <h6><span class="font-weight-bold mr-2">Serial de carroceria:
                    </span>{{$poliza->vehicle_bodywork_serial}}</h6>
                <h6><span class="font-weight-bold mr-2">Uso: </span>{{$poliza->used_for}}</h6>
                <h6><span class="font-weight-bold mr-2">Clase de vehiculo: </span>{{$poliza->class->class}}</h6>
            </div>
        </div>

        <h3 class="card-title text-center">Descripción Póliza</h3>
        <h5 class="card-subtitle text-center mt-2 mb-3">{{$poliza->price->description ?? ''}}</h5>
        <div class="row border-bottom border-dark mb-4">
            <div class="col-6 text-center border-right border-dark">
                @if($poliza->price->campoc ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo ?? ''}} </span>{{$poliza->price->campoc
                    * $foreign_reference}} Bs.S</h6>
                @endif

                @if($poliza->price->campoc1 ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo1 ?? ''}}
                    </span>{{$poliza->price->campoc1 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($poliza->price->campoc2 ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo2 ?? ''}}
                    </span>{{$poliza->price->campoc2 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($poliza->price->campoc3 ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo3 ?? ''}}
                    </span>{{$poliza->price->campoc3 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($poliza->price->campoc4 ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo4 ?? ''}}
                    </span>{{$poliza->price->campoc4 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($poliza->price->campoc5 ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo5 ?? ''}}
                    </span>{{$poliza->price->campoc5 * $foreign_reference}} Bs.S</h6>
                @endif

                @if($poliza->price->campoc6 ?? false)
                <h6><span class="font-weight-bold mr-2">{{$poliza->price->campo6 ?? ''}}
                    </span>{{$poliza->price->campoc6 * $foreign_reference}} Bs.S</h6>
                @endif


                {{-- <h6 class="mt-4"><span class="font-weight-bold mr-2">Total Cobertura:
                    </span>{{number_format($poliza->total_all * $foreign_reference, 2)}} Bs.S</h6> --}}
            </div>
            <div class="col-6 text-center">
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop *
                    $foreign_reference, 2)}} Bs.S</h6>
                @if($poliza->price->campoc1 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop1 *
                    $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($poliza->price->campoc2 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop2 *
                    $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($poliza->price->campoc3 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop3 *
                    $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($poliza->price->campoc4 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop4 *
                    $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($poliza->price->campoc5 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop5 *
                    $foreign_reference, 2)}} Bs.S</h6>
                @endif
                @if($poliza->price->campoc6 ?? false)
                <h6><span class="font-weight-bold mr-2">Prima: </span>{{number_format($poliza->price->campop6 *
                    $foreign_reference, 2)}} Bs.S</h6>
                @endif
                <h6 class="mt-5"><span class="font-weight-bold mr-2">Total Prima: </span> {{
                    number_format($poliza->total_premium * $foreign_reference, 2)}} Bs.S</h6>
            </div>
        </div>
        <div class="row mt-2">
            {{-- <div class="col-12">
                <span class="btn btn-block btn-success" id="openModal" data-toggle="modal" data-target="{{'#'."
                    modal-price".$poliza->id}}">Renovar Precio</span>
            </div> --}}
        </div>
    </div>
</div>
@endsection

    @section('scripts')
    <script>
        let objects = document.getElementsByClassName('prices_se');

        $(document).ready(function () {
            for (object of objects) {
                console.log(object.innerText);
                object.innerText = number_format(object.innerText);
            }
        });

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    </script>
    @endsection
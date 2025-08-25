@extends('layouts.admin-modules')

@section('module')
<div class="card">
  <div class="card-header">
    <ul class="nav nav-pills card-header-pills">
      <li class="nav-item">
        <a class="nav-link bg-dark active" href="/admin/admin-exportpdf/{{$policy->id}}" target="blank">Exportar PDF</a>
      </li>
      <li class="nav-item">
        <a class="nav-link ml-2 bg-succes active" href="/admin/admin-exportpdf-digital/{{$policy->id}}" target="blank">Exportar PDF-DIGITAL</a>
      </li>

      <li class="nav-item">
        <a href="{{ route('facturacion.descargar-factura',$policy->id) }}" class="nav-link ml-2 bg-succes active" target="_blank">Mostrar Factura</a>
      </li>

      <li class="nav-item">
        <a class="nav-link ml-2 bg-warning text-dark active" href="/admin/edit-policy/{{$policy->id}}">Editar Poliza</a>
      </li>

      <li class="nav-item">
        <form action="/admin/delete-policy/{{$policy->id}}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="nav-link ml-2 btn btn-danger">Eliminar</button>
        </form>
      </li>
      <li class="nav-item ml-auto">
        <a href="{{route('index.policies')}}" class="nav-link bg-danger active">X</a>
      </li>
    </ul>
  </div>

  <div class="card-body">
   <h3 class="card-title text-center">Datos de Afiliación</h3>
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

    <h3 class="card-title text-center">Datos del Vendedor</h3>
    <div class="row border-bottom border-dark mb-4">
      <div class="col-4 text-center">
        @if(isset($policy->user_id))
        <h6><span class="font-weight-bold mr-2">Contratante: </span>{{$policy->user->name . " " . $policy->user->lastname}}</h6>
        @else
        <h6><span class="font-weight-bold mr-2">Contratante: </span>Administrador</h6>
        @endif
      </div>
      <div class="col-4 text-center">
        @if(isset($policy->user_id))
        <h6><span class="font-weight-bold mr-2">Rif/Cedula: </span>{{$policy->user->ci}}</h6>
        @else
        <h6><span class="font-weight-bold mr-2">Rif/Cedula: </span>Administrador</h6>
        @endif
      </div>
      <div class="col-4 text-center">
        @if(isset($policy->user_id))
        <h6><span class="font-weight-bold mr-2">Teléfono: </span>{{$policy->user->phone_number}}</h6>
        @else
        <h6><span class="font-weight-bold mr-2">Teléfono: </span>Administrador</h6>
        @endif
      </div>
    </div>

    <h3 class="card-title text-center">Datos del Cliente</h3>
    <div class="row">
      <div class="col-3 text-center">
        <h6><span class="font-weight-bold mr-2">Benificiario: </span>{{$policy->client_name. " " .$policy->client_lastname}}</h6>
      </div>
      <div class="col-3 text-center">
        <h6><span class="font-weight-bold mr-2">Rif/Cédula: </span>{{$policy->client_ci}}</h6>
      </div>
      <div class="col-3 text-center">
        <h6><span class="font-weight-bold mr-2">Teléfono: </span>{{'0'.$policy->client_phone}}</h6>
      </div>
      <div class="col-3 text-center">
        <h6><span class="font-weight-bold mr-2" style="font-size: 14px;">Email: </span>{{$policy->client_email}}</h6>
      </div>
    </div>


    <div class="row">
      <div class="col-6 text-center">
        <h6><span class="font-weight-bold mr-2">Dirección: </span>{{$policy->estado->estado.', '.$policy->municipio->municipio.', '.$policy->parroquia->parroquia.'.'}}</h6>
      </div>

      <div class="col-6 text-center">
        <h6>{{$policy->client_address}}</h6>
      </div>
    </div>

    <div class="row border-bottom border-dark mb-4">
      <div class="col-6 text-center">
        <h6><span class="font-weight-bold mr-2">Contratante: </span>{{$policy->client_name_contractor. " " .$policy->client_lastname_contractor}}</h6>
      </div>
      <div class="col-6 text-center">
        <h6><span class="font-weight-bold mr-2">Rif/Cédula: </span>{{$policy->client_ci_contractor}}</h6>
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
        <h6><span class="font-weight-bold mr-2">Número de certificado: </span>{{$policy->vehicle_certificate_number}}</h6>
        <h6><span class="font-weight-bold mr-2">Placa: </span>{{$policy->vehicle_registration}}</h6>
        <h6><span class="font-weight-bold mr-2">Serial motor: </span>{{$policy->vehicle_motor_serial}}</h6>
        <h6><span class="font-weight-bold mr-2">Serial de carroceria: </span>{{$policy->vehicle_bodywork_serial}}</h6>
        <h6><span class="font-weight-bold mr-2">Uso: </span>{{$policy->used_for}}</h6>
        <h6><span class="font-weight-bold mr-2">Clase de vehiculo: </span>{{$policy->class->class}}</h6>
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


        {{-- <h6 class="mt-4"><span class="font-weight-bold mr-2">Total Cobertura: </span>{{number_format($policy->total_all * $foreign_reference, 2)}} Bs.S</h6> --}}
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

    <div class="row">
      {{-- <div class="col-12">
          <a href="/admin/renew-policy/{{$policy->id}}" class="btn btn-warning" style="width: 100%">Renovar Poliza</a>
      </div> --}}
    </div>
     <div class="row mt-2">
      {{-- <div class="col-12">
        <span class="btn btn-block btn-success" id="openModal" data-toggle="modal" data-target="{{'#'."modal-price".$policy->id}}">Renovar Precio</span>
      </div> --}}
    </div>
</div>
</div>

<div class="modal fade" id="modal-{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-renew-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea renovar la fecha de vencimiento de esta póliza?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Seleccione "continuar" si desea renovar esta poliza</div>
      <div class="modal-footer">
        <form action="/admin/renew-policy/{{$policy->id}}" method="POST">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-price{{$policy->id}}" tabindex="-1" role="dialog" aria-labelledby="modal-price-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">¿Seguro que desea renovar el precio de esta póliza?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Seleccione "continuar" si desea renovar el precio de esta poliza</div>
      <div class="modal-footer">
        <form action="/admin/renew-policy-price/{{$policy->id}}" method="POST">
          @csrf
          <input type="hidden" name="_method" value="PUT">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script>
  let objects = document.getElementsByClassName('prices_se');

  $(document).ready(function() {
    for(object of objects){
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
  toFixedFix = function(n, prec) {
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

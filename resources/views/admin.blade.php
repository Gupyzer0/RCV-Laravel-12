@extends('layouts.admin-modules')

@section('module')

<div class="d-sm-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
</div>

<div class="row">

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-primary shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pólizas vendidas</div>
            {{-- Usamos la variable del controlador --}}
            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $policiesSoldAll }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-success shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pólizas vendidas este mes</div>
             {{-- Usamos la variable del controlador --}}
            <div class="h5 mb-0 font-weight-bol\d text-gray-800">{{ $policiesSoldMonth }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-warning shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pólizas Por Vencer en un rango de 7 días</div>
            <div class="row no-gutters align-items-center">
              <div class="col-auto">
                 {{-- Usamos la variable del controlador --}}
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $policiesAnovalid }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
        <div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-info shadow h-100 py-2"> {{-- Color cambiado a info para diferenciarla de "vencidas" --}}
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Polizas por vencer HOY</div>
            <div class="row no-gutters align-items-center">
              <div class="col-auto">
                 {{-- Usamos la variable del controlador --}}
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $policiesHoynovalid }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-danger shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Cantidad de Pólizas Vencidas</div>
            <div class="row no-gutters align-items-center">
              <div class="col-auto">
                 {{-- Usamos la variable del controlador --}}
                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $policiesNovalid }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Ganancia Aproximada este Corte</div>
            {{-- Usamos la variable del controlador --}}
            <div class="h5 mb-0 font-weight-bold text-gray-800" id="price_number">{{ $policiesMoneyMonth }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>


    <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-info shadow h-100 py-2">
      <div class="card-body">
        <div class="row no-gutters align-items-center">
          <div class="col mr-2">
            <div class="text-xs font-weight-bold text-gray text-uppercase mb-1">Vendedor del Mes</div>
             {{-- Usamos la variable del controlador --}}
            <div class="h7 mb-0 font-weight-bold text-gray-800" id="best_seller_name"><strong class="text-dark">{{ $bestSellerMonthName }}</strong></div> {{-- ID cambiado por claridad --}}
          </div>
        </div>
      </div>
      </div>
    </div>

</div>

<div class="row">

  <div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
       <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"> {{-- Añadido el header faltante --}}
         <h6 class="m-0 font-weight-bold text-primary">Pólizas vendidas al mes</h6>
       </div>
      <div class="card-body">
        <div class="chart-area">
          <canvas id="myAreaChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- Sección renombrada para claridad --}}
  <div class="col-xl-4 col-lg-5">
        <div class="card shadow">
      <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Precio del Euro</h6>
      </div>
      <div class="card-body text-center">
          <div>
             {{-- Usamos la variable $foreign --}}
            @if(isset($foreign[0])) {{-- Añadimos chequeo por si no existe el primer registro --}}
                <strong>Precio actual: {{number_format($foreign[0]->foreign_reference, 2) }} Bs.S</strong>
            @else
                 <strong>Precio actual: N/A</strong>
            @endif

            {{-- Los IDs para el chequeo de usuario están hardcodeados. Considera usar roles/permisos --}}
            @if(in_array(auth()->user()->id, [ 999512,999502 ]))
            <form action="/admin/update-foreign/{{$foreign[0]->id ?? ''}}" method="POST"> {{-- Añadido null coalescing para evitar error si $foreign[0] no existe --}}
              @csrf
              <input type="hidden" name="_method" value="PUT">

              <div class="form-row mb-3">
                <div class="input-group">
                  <input type="text" name="foreign_reference" class="mt-2 form-control" id="foreign_reference_euro"> {{-- ID cambiado por claridad --}}
                  <div class="input-group-append">
                    <span class="input-group-text">Bs.s</span>
                  </div>
                </div>
              </div>

    <button type="submit" class="btn btn-success d-block m-auto">Cambiar</button>
@endif

            </form>
          </div>
      </div>
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Precio del Dolar</h6>
        </div>
      <div class="card-body text-center">
          <div>
             {{-- Usamos la variable $foreign --}}
            @if(isset($foreign[1])) {{-- Añadimos chequeo por si no existe el segundo registro --}}
                <strong>Precio actual: {{number_format($foreign[1]->foreign_reference, 2) }} Bs.S</strong>
            @else
                 <strong>Precio actual: N/A</strong>
            @endif

                      @if(in_array(auth()->user()->id, [ 999512,999502 ]))
            <form action="/admin/update-foreign/{{$foreign[1]->id ?? ''}}" method="POST"> {{-- Añadido null coalescing --}}
              @csrf
              <input type="hidden" name="_method" value="PUT">

              <div class="form-row mb-3">
                <div class="input-group">
                  <input type="text" name="foreign_reference" class="mt-2 form-control" id="foreign_reference_dolar"> {{-- ID cambiado por claridad --}}
                  <div class="input-group-append">
                    <span class="input-group-text">Bs.s</span>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-success d-block m-auto ">Cambiar</button>
              @endif
            </form>
          </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script>
  let number = document.getElementById('price_number');
  // No necesitamos seleccionar revenue_year aquí, solo price_number

  $(document).ready(function() {
    // Aplicar number_format solo al elemento con ID 'price_number' (Ganancia Aproximada este mes)
    if (number) { // Verificar si el elemento existe
       let corrected = number_format(number.innerText);
       number.innerText = `${corrected} $`; // O el símbolo de moneda que corresponda a la "Ganancia Aproximada"
    }

    // Si quieres formatear el precio del dólar/euro, deberías hacerlo en el Blade directamente
    // o seleccionar esos elementos por su ID y aplicarles el formato aquí también.
    // Por ahora, solo formateo price_number.
  });

  // Tu función number_format... (sin cambios)
  function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
    // Asumo que Ganancia Aproximada puede tener decimales, mantenemos prec
    prec = !isFinite(+decimals) ? 2 : Math.abs(decimals), // Cambiado a 2 decimales por defecto
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
<script>
  // Set new default font family and font color to mimic Bootstrap's default styling
  Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
  Chart.defaults.global.defaultFontColor = '#858796';

// Area Chart Example
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["En", "Febr", "Mzo", "Abr", "My", "Jun", "Jul", "Agt", "Sept", "Oct", "Nov", "Dic"],
    datasets: [{
      label: "Ventas",
      lineTension: 0.3,
      backgroundColor: "rgba(78, 115, 223, 0.05)",
      borderColor: "rgba(78, 115, 223, 1)",
      pointRadius: 3,
      pointBackgroundColor: "rgba(78, 115, 223, 1)",
      pointBorderColor: "rgba(78, 115, 223, 1)",
      pointHoverRadius: 3,
      pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
      pointHoverBorderColor: "rgba(78, 115, 223, 1)",
      pointHitRadius: 10,
      pointBorderWidth: 2,
      // ¡Usamos la variable JSON del controlador para los datos del gráfico!
      data: {{ $policiesMonthJson }},
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
        left: 10,
        right: 25,
        top: 25,
        bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          unit: 'date'
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          maxTicksLimit: 12 // Mostrar ticks para todos los meses
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5, // Ajusta si necesitas más ticks
          padding: 10,
           callback: function(value, index, values) {
             if (Number.isInteger(value)) { // Mostrar solo enteros si son conteos
                 return value;
             }
             return value; // O formatear como prefieras
           }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: false
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
       callbacks: {
         label: function(tooltipItem, chart) {
           var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
           // Ajusta el formato del tooltip si es necesario
           return datasetLabel + ': ' + tooltipItem.yLabel;
         }
       }
    }
  }
});

</script>
<script src="{{asset('js/simple-mask-money.js')}}"></script>
<script type="text/javascript">
   // IDs de los inputs de Foreign Reference cambiados para mayor claridad
  let foreign_reference_euro = SimpleMaskMoney.setMask('#foreign_reference_euro', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: ',',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
      }
  });
   // IDs de los inputs de Foreign Reference cambiados para mayor claridad
  let foreign_reference_dolar = SimpleMaskMoney.setMask('#foreign_reference_dolar', {
        prefix: '',
        suffix: '',
        fixed: true,
        fractionDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: ',',
        emptyOrInvalid: () => {
            return this.SimpleMaskMoney.args.fixed
            ? `0${this.SimpleMaskMoney.args.decimalSeparator}00`
            : `_${this.SimpleMaskMoney.args.decimalSeparator}__`;
      }
  });
</script>
@endsection

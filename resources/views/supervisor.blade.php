@extends('layouts.mod-modules') 

@section('module')

<div class="d-sm-flex align-items-center justify-content-between mb-3">
  <h1 class="h3 mb-0 text-gray-800">Inicio Supervisor</h1>
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
    <div class="card border-left-info shadow h-100 py-2">
     <div class="card-body">
       <div class="row no-gutters align-items-center">
         <div class="col mr-2">
           <div class="text-xs font-weight-bold text-gray text-uppercase mb-1">Cantidad de Supervisados</div>
           {{-- Usamos la variable del controlador para la CANTIDAD --}}
           <div class="h7 mb-0 font-weight-bold text-gray-800"><strong class="text-dark">{{ $supervisedUsersCount }}</strong></div>
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
             {{-- Usamos la variable del controlador para el NOMBRE --}}
            <div class="h7 mb-0 font-weight-bold text-gray-800"><strong class="text-dark">{{ $bestSellerMonthName }}</strong></div>
          </div>
        </div>
      </div>
      </div>
    </div>

    {{-- Las tarjetas de Polizas Por Vencer, Vencidas Hoy y Vencidas no están en esta vista --}}
    {{-- La tarjeta de Ganancia Aproximada este mes tampoco está en esta vista --}}
    {{-- No hay sección de Foreign Units en esta vista --}}


</div>

<div class="row">

  <div class="col-xl-8 col-lg-7">
    <div class="card shadow mb-4">
       <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"> {{-- Añadido el header --}}
         <h6 class="m-0 font-weight-bold text-primary">Pólizas vendidas al mes</h6> {{-- Título ajustado --}}
       </div>
      <div class="card-body">
        <div class="chart-area">
          <canvas id="myAreaChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- La sección Pie Chart/Foreign Units no está en esta vista --}}

</div>


@endsection

@section('scripts')
<script>
  // No hay elementos con ID 'price_number' en esta vista para formatear
  // El script de number_format se puede eliminar o mantener si se usa en otro lugar
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
{{-- Script simple-mask-money y su inicialización no se usan en esta vista, pueden eliminarse --}}
{{-- <script src="{{asset('js/simple-mask-money.js')}}"></script> --}}
{{-- <script type="text/javascript"> ... </script> --}}
@endsection
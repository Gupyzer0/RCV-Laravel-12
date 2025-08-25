@extends('layouts.app')

@section('module')

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Inicio</h1>
</div>

<div class="row">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pólizas vendidas
                        </div>
                        {{-- ¡Ahora usamos la variable del controlador! --}}
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $policiesSoldUser }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pólizas vigentes</div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                {{-- ¡Ahora usamos la variable del controlador! --}}
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $policiesValidUser }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Polizas por pagar</div>
                        {{-- ¡Ahora usamos la variable del controlador! --}}
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $policiesPorPagar }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pagar</div>
                        {{-- ¡Ahora usamos la variable del controlador! --}}
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="price_number">{{ $totalPagar }}</div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">

    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Pólizas vendidas al mes</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Tus ganancias esta Quincena</h6>
            </div>
            <div class="card-body">
                {{-- ¡Ahora usamos la variable del controlador! --}}
                <div id="revenue_year" class="text-dark">{{ $gananciaQuincenal }}</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let number = document.getElementById('price_number');
    let number2 = document.getElementById('revenue_year');

    $(document).ready(function () {
        let corrected = number_format(number.innerText);
        number.innerText = `${corrected} $`; // Espacio antes del $ si lo deseas
        let revenueCorrected = number_format(number2.innerText);
        number2.innerText = `${revenueCorrected} $`; // Espacio antes del $ si lo deseas
    });

    // Tu función number_format... (sin cambios)
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 2 : Math.abs(decimals),
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

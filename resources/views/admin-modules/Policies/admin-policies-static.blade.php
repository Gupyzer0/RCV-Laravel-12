@extends('layouts.admin-modules')
@section('module')

<div class="card shadow mb-4">
	<div class="card-header py-2">
		<h6 class="m-0 font-weight-bold text-primary d-inline-block py-2">Estadisticas</h6>

	</div>
    <div id="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 col-lg-8 ">
                    <!-- Area Chart -->
                    <div class="card shadow mb-7">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Por Mes</h6>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered" id="dataTab2le" width="100%" cellspacing="0" style="font-size: 13px;">
                                <thead style="text-align: center;">
                                    <tr>
                                        <th style="padding: 1px;" colspan="2"></th>
                                        <th style="padding: 1px;background-color: #818181;color:#ddd;" colspan="4">Tipo de Documento</th>
                                        {{-- <th style="padding: 1px; background-color: #4e4e4e; color:#ddd;" colspan="2">Sexo</th> --}}
                                    </tr>
                                    <tr>
                                        <th>Año</th>
                                        <th>Mes</th>
                                        <th >V</th>
                                        <th >E</th>
                                        <th >J</th>
                                        <th >G</th>
                                        {{-- <th>M</th>
                                        <th>F</th> --}}
                                        <th>Total</th>
                                        <th>Total Vendido €</th>
                                        <th>Total Vendido Bs</th>
                                        <th>Total Recibido €</th>
                                        <th>Total Recibido Bs</th>
                                    </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                    @php
                                        $sum = 0; $sumd = 0; $v = 0; $e= 0;$j= 0;$g= 0; $bs=0;

                                    @endphp
                                    @foreach($counter as $registro)

                                    <tr>
                                        <td>{{ $registro->year }}</td>
                                        <td>{{ $meses[$registro->month].'-'.$registro->period}}</td>
                                        <td>{{ $registro->vene }}</td>
                                        <td>{{ ($registro->extra) }}</td>
                                        <td>{{ ($registro->juri) }}</td>
                                        <td>{{ ($registro->gobi) }}</td>
                                        <td>{{ $registro->total_registros }}</td>
                                        <td>{{ number_format($registro->total_premiun_sum, 2, ',','.') }} €</td> <!-- Mostrar la suma de total_premiun -->
                                        <td>{{ number_format($registro->total_premiun_foreign_sum,2, ',','.') }} Bs</td> <!-- Mostrar la suma de total_premiun * foreign -->
                                        <td>{{ number_format($registro->total_premiun_sumr, 2, ',','.') }} €</td> <!-- Mostrar la suma de total_premiun -->
                                        <td>{{ number_format($registro->total_premiun_foreign_sumr,2, ',','.') }} Bs</td> <!-- Mostrar la suma de total_premiun * foreign -->

                                        @php
                                            $v = $v + $registro->vene;
                                            $e = $e + $registro->extra;
                                            $j = $j + $registro->juri;
                                            $g = $g + $registro->gobi;

                                            $sum = $sum + $registro->total_registros;
                                            $sumd = $sumd + $registro->total_premiun_sum;
                                            $bs = $bs + $registro->total_premiun_foreign_sum;
                                        @endphp
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="2"><strong> Total</strong></td>
                                        <td><strong> {{number_format($v)}}</strong></td>
                                        <td><strong> {{number_format($e)}}</strong></td>
                                        <td><strong> {{number_format($j)}}</strong></td>
                                        <td><strong> {{number_format($g)}}</strong></td>
                                        <td><strong> {{number_format($sum,2, ',','.')}}</strong></td>
                                        <td><strong> {{number_format($sumd, 2, ',','.')}} €</strong></td>
                                        <td><strong> {{number_format($bs, 2, ',','.')}} Bs</strong></td>

                                    </tr>
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
                <!-- Donut Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-8">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Por Estado</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class=" pt-1">
                                <table class="tablest"  style="font-size: 13px;">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th>Estados</th>
                                            <th>V</th>
                                            <th>E</th>
                                            <th>J</th>
                                            <th>G</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @foreach($cant as $estado)
                                        <tr style="text-align: center;">
                                            <td>{{ $estado->estado }}</td>
                                            <td>{{ $estado->vene }}</td>
                                            <td>{{ $estado->extra }}</td>
                                            <td>{{ $estado->juri }}</td>
                                            <td>{{$estado->gobi }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-xl-8 col-lg-7">
            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <table class="table table-bordered" id="dataTa2ble" width="100%" cellspacing="0" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Mes</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($counter as $registro)
                            <tr>
                                <td>{{ $registro->year }}</td>
                                <td>{{ $meses[$registro->month] }}</td>
                                <td>{{ $registro->total_registros }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-body">
                    <table class="table table-bordered" id="dataTab2le" width="100%" cellspacing="0" style="font-size: 13px;">
                        <thead>
                            <tr>
                                <th>Estados</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cant as $estado)
                            <tr>
                                <td>{{ $estado->estado }}</td>
                                <td>{{ $estado->policies_count }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

@endsection

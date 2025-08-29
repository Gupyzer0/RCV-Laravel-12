<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Correlativo</title>
</head>
<style>
    * {
        font-family: arial, "sans-serif";
    }

    .table,
    td {
        border: 1px solid gray;
        border-collapse: collapse;
        text-align: center;

    }

    .t {
        border: 1px solid black;
        background-color: lightgrey;
    }

    .dtable {
        margin-top: 110px;

    }

    .total {
        border: hidden;
        border-top: solid 0px gray;
        border-collapse: collapse;
    }

    .total-last {
        border-right: solid 1px black;
    }

    .logo {

        height: 80px;
        width: 280px;
    }

    .hea {
        float: left;
    }

    .datos {
        margin-top: 15px;
        font-size: 12px;
        float: right;
    }

    .dtd {
        border: hidden;
    }
</style>

<body>

    <div class='datos'>
        <table style="border:hidden;" cellspacing="0">
            <tr>
                <td class='dtd'><strong>Vendedor:</strong> </td>
                <td class='dtd'> {{ $user->nombre_completo }} </td>
            </tr>
            <tr>
                <td class='dtd'><strong>Supervisor:</strong> </td>
                <td class='dtd'> {{ $user->moderator->nombre_completo }} </td>
            </tr>
            <?php $hoy = date('d-m-Y'); ?>

            <tr>
                <td class='dtd'><strong>Fecha de Emisión:</strong> </td>
                <td class='dtd'> {{$hoy}} </td>
            </tr>
        </table>
    </div>

    <div class='dtable'>
        <h3 style="text-align: center">Correlativo Polizas No Pagadas </h3>
        <?php $count= 0; $suma=0; $sumabs=0; $suma1=0; $resta=0; $sumab = 0; $restat= 0; $sumar =0;?>

        @csrf
        <table width="100%" cellspacing="0" style="font-size: 12px;">
            <thead>
                <tr>
                    <th class='t'>N°</th>
                    <th class='t'>Tomador</th>
                    <th class='t'>Vehículo</th>
                    <th class='t'>Placa</th>
                    <th class='t'>Fecha de Emisión</th>
                    <th class='t'>Precio </th>
                    <th class='t'>Precio Bs</th>
                    <th class='t'>Tasa </th>
                    <th class='t'>Telefono</th>
                </tr>
            </thead>
            <tbody>
                @foreach($polizas as $poliza)
                <tr>
                    <td>{{$poliza->id}}</td>
                    <td>{{$poliza->client_name.' '.$poliza->client_lastname}}</td>
                    <td>{{$poliza->vehicle_model}}</td>
                    <td>{{$poliza->vehicle_registration}}</td>
                    <td>{{ \Carbon\Carbon::parse($poliza->created_at)->format('d-m-Y')}}</td>
                    <td>{{number_format($poliza->total_premium, 2)}} €</td>
                    @php
                    $boli = $poliza->total_premium * $poliza->foreign;
                    @endphp
                    <td>{{number_format( $boli, 2) }} Bs</td>
                    <td>{{number_format($poliza->foreign, 2)}} Bs</td>

                    @if(!$poliza->statusu)
                    <td>{{$poliza->client_phone}}</td>
                    @else
                    <?php $resta= $resta + $poliza->total_premium; $restat += $poliza->total_premium * $poliza->foreign ?>
                    <td><strong style="color:red;">NULA</strong></td>
                    @endif

                </tr>
                <?php
                $count= $count + 1;
                $comision = $poliza->user->profit_percentage / 100;
                $suma1 += $poliza->total_premium;
                $sumar += $poliza->total_premium * $poliza->foreign;
                $sumab = $sumar - $restat;
                $suma = $suma1 - $resta;
                $total = $suma - ($suma * $comision);
                $sumabs = $sumabs + ($poliza->total_premium * $poliza->foreign); ?>
                @endforeach
                <tr>
                    <td class='total'></td>
                    <td class='total'></td>
                    <td class='total'></td>
                    <td class='total total-last'></td>
                    <td><strong>Total: </strong></td>
                    <td><strong>{{number_format($suma, 2)}} </strong></td>
                    <td><strong>{{number_format($sumab, 2)}} Bs</strong></td>
                    <td
                        style="border: hidden; border-top: solid 1px gray; border-left: solid 1px gray; border-collapse: collapse;">
                    </td>
                    <td style="border: hidden; border-top: solid 1px gray; border-collapse: collapse;"></td>

                </tr>
                <tr>
                    <td class='total'></td>
                    <td class='total'></td>
                    <td class='total'></td>
                    <td class='total total-last'></td>
                    <td><strong>Comisión: </strong></td>
                    <td><strong>{{number_format($suma * $comision, 2)}} </strong></td>
                    <td><strong>{{number_format($sumab * $comision, 2)}} Bs</strong></td>
                    <td style="border: hidden;  border-left: solid 1px gray; border-collapse: collapse;"></td>
                    <td style="border: hidden; border-collapse: collapse;"></td>

                </tr>
                <tr>
                    <td class='total'></td>
                    <td class='total'></td>
                    <td class='total'></td>
                    <td class='total total-last'></td>
                    <td><strong>Total a Entregar: </strong></td>
                    <td><strong>{{number_format($suma - ($suma * $comision), 2)}} </strong></td>
                    <td><strong>{{number_format($sumab - ($sumab * $comision), 2)}} Bs</strong></td>
                    <td style="border: hidden; border-left: solid 1px gray; border-collapse: collapse;"></td>
                    <td style="border: hidden; border-collapse: collapse;"></td>
                </tr>
            </tbody>
        </table>
        <p>Cantidad de Polizas Vendidas: {{$count}}</p>
    </div>
</body>

</html>
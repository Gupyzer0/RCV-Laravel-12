<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Acta de Compromiso</title>
</head>

<style>
    @page {
                margin: 0cm 0cm;
            }

  *{
    font-family: arial, "sans-serif";

  }
      body {
                margin-top:    0.6cm;
                margin-bottom: 2cm;
                margin-left:   3cm;
                margin-right:  3cm;
                font-size: 10;

            }
    .logo{
        width:    9cm;
        height:   2.6cm;
        opacity: 0.4;

    }

    #sello{
        position: fixed;
        top: 890px;
        left: 430px;
        width:    6cm;
        height:   2cm;
    }
    .pr{
        text-align: justify;
        text-indent:4em;
        line-height: 18px;
        margin-top: 2px;
    }

    .pr strong{
        text-transform: uppercase;
    }
    .table table{
        border: 1px solid black;
        border-collapse: collapse;
        width: 100%;
    }
    .table td{
        border: 1px solid black;
    }



</style>
<body>

    <div class="logo"> <img src="{{asset('images/lgoo2.png')}}"  height="100%" width="100%" /></div>

    <h3 style="text-align: center; margin-top:2px;"> ACTA DE COMPROMISO</h3>
<div>
    @php
        $c = $user->ci;
        $clave = intval(preg_replace('/[^0-9]+/', '', $c), 10);
    @endphp
    <p class="pr">
        Por medio de la presente nos permitimos saludarlos en nombre de <strong>LIDER SEGUROS PARA VEHÍCULOS, R.L J-311050906, INSCRITA EN LA SUPERINTENDECIA BAJO EL NUMERO 15 PROVIDENCIA SAA-D-L-2-5-0109 EN FECHA DE MAYO 2018.</strong>
        Es oportuno darle una cordial bienvenida desde el <strong>{{\Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}</strong>, como Aliado a: <strong>{{ $user->office->office_address }}</strong>  teniendo como Represéntate legal:  a <strong>{{ $user->name.' '.$user->lastname }}</strong> titular de la cedula de identidad enmarcado con el numero <strong>{{ $user->ci }},</strong> domiciliado en el
        Municipio <strong>{{ $user->office->municipio->municipio }}</strong>, Parroquia <strong>{{ $user->office->parroquia->parroquia }}</strong>, Estado <strong>{{ $user->office->estado->estado }}</strong>, quien a partir de este momento forma parte de nuestro equipo de trabajo en la realización de la actividad comercial concerniente a la venta de contrato de Responsabilidad Civil, bajo la supervision de: @if($user->mod_id) <strong>{{$user->moderator->names}}</strong> @endif .
        Se realiza la presente declaración de estar recibiendo por parte de empresa a entera satisfacción de <strong>LIDER SEGUROS PARA VEHÍCULOS R.L</strong> , el Usuario: <strong>{{ $user->username }}</strong> y clave: <strong> {{ $clave }}  </strong> expedido por el sistema referido por el link: <strong>https://app.lidersegurosvzla.com para su uso en la creación de contratos por el sistema web.</strong>
    </p>
</div>

<div>
    <h4 style="margin-top: 5px; text-indent:3em;">Responsabilidad:</h4>
    <ul style="line-height: 18px">
        <li>Tener siempre el saldo disponible para la cancelación de sus ventas en quincenas e últimos de cada mes para evitar ser bloqueado automáticamente por el sistema y caer en mora con la empresa.</li>
        <li>Asesorar a los prospectos o clientes.</li>
        <li>Generar contratos o pólizas mediante su sistema web https://app.lidersegurosvzla.com </li>
        @if( Auth::user()->type == 1 )
        <li>Promover las ventas mediante asesorías al cliente en línea ofreciendo pólizas online en coordinación con área de soporte técnico.04144884333</li>
        @elseif(Auth::user()->type == 4 )
        <li>Promover las ventas mediante asesorías al cliente en línea ofreciendo pólizas online en coordinación con área de soporte técnico.04123610088. /04241390867</li>
        @endif
        <li>Representar la imagen de la empresa, resguardo y responsabilizándose de los bienes de la empresa (publicidades).</li>
        <li>Preservar y cuidar los recursos y equipos suministrado por la empresa de ser así el caso.</li>
        <li>Planificar su estrategia de venta solicitando material físico, así como digital.</li>
        <li>Reportar a la empresa o a su supervisor los fallos de sistema y afines. </li>
    </ul>

    <p>Se dejará constancia por este medio todos los equipos y material asignados para la efectiva elaboración de ventas de Seguros.</p>
</div>

<div class="table">
    <table>
        <tr>
            <td colspan="4" style="height: 40px">EQUIPOS:</td>

        </tr>
        <tr>
            <td rowspan="2" style="width: 20%">PUBLICIDAD:</td>
            <td style="width: 20%; height: 30px;">Banner Colgente</td>
            <td style="width: 30%; height: 30px;">Dimesiones:</td>
            <td></td>
        </tr>

        <tr>
            <td style="width: 20%; height: 30px;">Tipo A de Banner</td>
            <td style="width: 30%; height: 30px;"> Dimensiones:</td>
            <td></td>
        </tr>
    </table>
</div>
<div class="firma" style="margin-top: 40px; ">
    <div class="">
        <img id="sello" src="{{asset('images/fima.png')}}"  height="100%" width="100%">
    </div>

    <table style="width: 100%; text-align: center;">
        <tr>
            <td><strong>FIRMA DE CONFORMIDAD</strong></td>
            <td><strong>EMPRESA</strong></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>

        <tr>
            <td>______________________________</td>
            <td>______________________________</td>
        </tr>
    </table>
</div>
</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Poliza - {{$poliza->client_name. ' '.$poliza->client_lastname}}</title>
</head>

<style>
    @page {
        margin: 0cm 0cm;
    }

    /**
            * Define los márgenes reales del contenido de tu PDF
            * Aquí arreglarás los márgenes del encabezado y pie de página
            * De tu imagen de fondo.
            **/
    body {

        margin-bottom: 1cm;
        margin-left: 1cm;
        margin-right: 1cm;
    }



    * {
        font-family: arial, "sans-serif";
        font-size: 11px;
    }



    table,
    th,
    td {
        padding: 2px;
        /* border-bottom: 1px solid black; */
        border-collapse: collapse;

        text-align: left;
        text-transform: uppercase;
    }

    .borde {
        border-style: solid;
        border-color: black;
        border-top-width: 1px;
        border-right-width: 1px;
        border-bottom-width: 1px;
        border-left-width: 1px;
    }

    .carnet {
        margin-top: 20px;

        float: left;
    }

    /* carnet 1 */
    .tablecarc2,
    .tablecarc2 td {
        position: absolute;
        top: 860px;
        left: 40px;
        width: 40%;
        font-size: 8px;
        padding: 1px;
    }

    .tableve {
        position: absolute;
        top: 24cm;
        left: 110px;
        width: 18%;
    }

    .tableve2 {
        position: absolute;
        top: 720px;
        left: 460px;
        width: 18%;
    }

    .tableved,
    .tableved td {
        position: absolute;
        top: 930px;
        left: 40px;
        width: 40%;
        font-size: 8px;
        padding: 1px;

    }

    .tableved2,
    .tableved2 td {
        position: absolute;
        top: 745px;
        left: 385px;
        width: 40%;
        font-size: 8px;
        padding: 1px;
    }

    /* carnet 2 */

    .tablev {
        position: absolute;
        top: 990px;
        left: 26px;
        width: 40%;

    }

    .tablev2 {
        position: absolute;
        top: 804px;
        left: 375px;
        width: 40%;
    }

    .tablecarc3,
    .tablecarc3 td {
        position: absolute;
        top: 710px;
        left: 385px;
        width: 40%;
        font-size: 8px;
        padding: 1px;
    }


    .linea {
        border-top: 1px solid black;
        height: 2px;
        max-width: 200px;
        padding: 0;
        margin: 35px auto 0 auto;
    }

    .page_break {
        page-break-before: always;
    }

    .npo {
        position: fixed;
        z-index: 100;
        margin-left: 75%;
        margin-top: 767px;
    }

    .npo2 {
        position: fixed;
        z-index: 100;
        margin-left: 32%;
        margin-top: 767px;
    }

    .dq {
        position: absolute;
        top: 22.6cm;
        left: 14.8cm;
    }

    .numeros {
        position: absolute;
        top: 84px;
        left: 8.3cm;
        font-size: 6px;
    }

    .lineat {
        text-align: center;
        border: 1px solid black;

        padding: 3.5px 0;
        text-transform: uppercase;
        color: black;

        font-size: 12.2px;
    }
</style>

<body>
    <div class="dq">
        <img class="qr" width="100px" src="data:image/png;base64, {!! $qrCode !!}" alt="QR Code">
    </div>
    <div class="cuerpo">
        <div class="numeros">
            {{-- <p style="font-size: 9px;">
                <span style="margin-right: 20px;">414-1234567</span>
                <span>414-7654321</span>
            </p> --}}
        </div>




        <div>
            <h3 style="margin-top: 3.8cm; font-size: 12px; text-align: center; padding: 0;">CUADRO POLIZA - RECIBO</h3>
        </div>


        <table width="100%" border="0" align="center" style="padding: 0;" cellspacing="0" bordercolor="#000000"
            class="borde">

            <tr>
                <th colspan="5" class="lineat">DATOS GENERALES:</th>
            </tr>
            <tr>
                <td><strong>Poliza N°: </strong><i>{{$poliza->id}}</i></td>
                <td><strong>Vigencia: </strong><i>1 Año</i></td>
                <td><strong>Desde: </strong><i>{{\Carbon\Carbon::parse($poliza->created_at)->format('d/m/Y')}}</i></td>
                <td><strong>Hasta: </strong><i>{{\Carbon\Carbon::parse($poliza->expiring_date)->format('d/m/Y')}}</i>
                </td>
                <td><strong>Hora: </strong><i>{{\Carbon\Carbon::parse($poliza->created_at)->format('H:m:s')}}</i></td>
            </tr>

            <tr>
                <td colspan="2"><strong>Localidad: </strong><i>{{$poliza->user->office->municipio->municipio.',
                        '.$poliza->user->office->estado->estado.' '.$poliza->user->office->id}}</i></td>
                <td colspan="" align="center">
                    @if($poliza->type == 2)
                    <strong>Asesor: </strong><i>Oriana Silva</i>
                    @elseif($poliza->type == 1)
                    <strong>Asesor: </strong><i>Eduardo Ortega </i>
                    @elseif($poliza->type == 3)
                    <strong>Asesor: </strong><i>Jesus Silva</i>
                    @elseif($poliza->type == 4)
                    <strong>Asesor: </strong><i>Petter Diaz</i>
                    @elseif($poliza->type >= 4)
                    <strong>Asesor: </strong><i>Asociacion Cooperativa Lider Seguros para Vehiculo RL</i>
                    @endif
                </td>
                <td><strong>Moneda: </strong><i>Dolar</i> </td>


                <td><strong>Frecuencia de Pago:</strong> ANUAL</td>

            </tr>
        </table>

        <table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000" class="borde">

            <tr>
                <th colspan="5" class="lineat">DATOS personales: </th>
            </tr>
            <tr align="center">
                <td colspan="3" width="30%"><b>Asegurado: </b></td>
                <td colspan="2" width="10%"><b>Rif/C.I: </b></td>
            </tr>

            <tr align="center">
                <td colspan="3">{{$poliza->client_name_contractor. " " .$poliza->client_lastname_contractor}}</td>
                <td colspan="2">{{$poliza->client_ci_contractor}}</td>

            </tr>

            <tr align="center">
                <td width=35%><b>Tomador: </b></td>
                <td width=7%><b>Rif/C.I: </b></td>
                <td width=8%><b>Teléfono: </b></td>
                <td width=18%><b>Correo: </b></td>
                <td width=32%><b>Dirección: </b></td>
            </tr>


            <tr align="center">
                <td><i>{{$poliza->client_name. " " .$poliza->client_lastname}}</i></td>
                <td><i>{{$poliza->client_ci}}</i></td>
                <td><i>{{$poliza->client_phone}}</i></td>
                <td><i>{{$poliza->client_email}}</i></td>
                <td><i>{{$poliza->municipio->municipio.', '.$poliza->estado->estado}}</i></td>
            </tr>
        </table>

        <table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000" class="borde">

            <tr>
                <th colspan="4" class="lineat">DATOS DEL VEHÍCULO: </th>
            </tr>
            <tr>
                <td><strong>Marca: </strong><i>{{$poliza->vehicle_brand}}</i></td>
                <td><strong>Color: </strong><i>{{$poliza->vehicle_color}}</i></td>
                <td><strong>Tipo: </strong><i>{{$poliza->vehicle_type}}</i></td>
                <td><strong>S/Carroceria: </strong><i>{{$poliza->vehicle_bodywork_serial}}</i></td>

            </tr>

            <tr>
                <td><strong>Modelo: </strong><i>{{$poliza->vehicle_model}}</i></td>
                <td><strong>Placa: </strong><i>{{$poliza->vehicle_registration}}</i></td>
                <td><strong>Clase: </strong><i>{{$poliza->class->class}}</i></td>
                <td><strong>S/Motor: </strong><i>{{$poliza->vehicle_motor_serial}}</i></td>
            </tr>

            <tr>
                <td><strong>Año: </strong><i>{{$poliza->vehicle_year}}</i></td>
                <td><strong>Uso: </strong><i>{{$poliza->used_for}}</i></td>
                <td><strong>Cap. de Carga: </strong><i>{{$poliza->vehicle_weight}}</i></td>
                <td><strong>N° de Puestos: </strong>{{$poliza->vehicle_certificate_number}}</td>
                </td>

            </tr>

        </table>

        @include('Polizas.reportes.table-price')
        <br>

        <table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000">
            <tr>
                <td style="text-align: justify; font-size: 9px;text-transform: none;">
                    <strong>COOPERATIVA LÍDER DE SEGUROS PARA VEHÍCULO, R.L. Inscrita en la Superintendencia de Seguros
                        bajo el Nº ACS-000005</strong>

                    <br>
                    Yo, el Contratante, declaro que los fondos utilizados para el pago de la prima de la póliza
                    provienen de fuentes lícitas, obtenidos en cumplimiento de la Legislación Nacional, y que no guardan
                    relación alguna con delitos de legitimación de capitales previstos en la ley.
                    <br>
                    Autorizo la verificación de la información proporcionada, así como el suministro de datos a terceros
                    para fines de evaluación de riesgo.
                    <br>
                    Con la entrega de este Cuadro Póliza - Recibo, el Tomador/Asegurado declara haber recibido y, en
                    consecuencia, conocer en su totalidad los documentos que rigen este contrato, incluyendo las
                    Condiciones Generales, Condiciones Particulares, Endosos y Cláusulas, que están disponibles
                    digitalmente a través del código QR ubicado en la parte superior derecha del contrato en los cuales
                    se establecen los términos de aceptación de los riesgos por parte de la Cooperativa Líder de Seguros
                    para Vehículo, R.L.

                </td>
            </tr>

        </table>

        <table class="firmas" width="100%" style="margin-top: 25px" border="0" align="center" cellspacing="0"
            bordercolor="#000000">
            <tr>
                <td width="50%">
                    <div class="linea"></div>
                </td>
                <td width="50%">
                    <div class="linea"></div>
                </td>
            </tr>
            <tr>
                <td width="50%" style="font-size: 12px;  text-align: center;">Lider Seguros para Vehiculos R.L</td>
                <td width="50%" style="font-size: 12px;  text-align: center;">{{$poliza->client_name.'
                    '.$poliza->client_lastname}}</td>
            </tr>
        </table>
    </div>

    <div class="carnet">
        <table class="tablecarc" style="position: absolute; top: 830px; left: 110px; width: 30%;" cellspacing="0">
            <tr>
                <td width="70%" style="font-size: 10px;  font-weight: 100;">Certificado de RCV</td>
                <td width="30%" style="font-size: 10px;  font-weight: 100;">N° {{$poliza->id}}</td>
            </tr>
        </table>


        <table class="tablecarc2">
            <tr>
                <td><strong style="font-size:8px;">Asegurado:</strong></td>
                <td><strong style="font-size:8px;">Tomador:</strong></td>
            </tr>
            <tr>
                <td>{{ $poliza->client_name_contractor.' '.$poliza->client_lastname_contractor}}</td>
                <td>{{ $poliza->client_name.' '.$poliza->client_lastname}}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">Cedula:</strong></td>
                <td><strong style="font-size:8px;">Cedula:</strong> </td>
            </tr>
            <tr>
                <td>{{ $poliza->client_ci_contractor }}</td>
                <td>{{ $poliza->client_ci }}</td>

            </tr>
        </table>

        <table class="tableve" cellspacing="0">

            <tr>
                <td colspan="2" style="font-size: 10px;  font-weight: 100;">DATOS DEL VEHÍCULO</td>
            </tr>
        </table>

        <table class="tableved">
            <tr>
                <td><strong style="font-size:8px;">Clase: </strong>{{ $poliza->class->class }}</td>
                <td><strong style="font-size:8px;">Uso: </strong>{{ $poliza->used_for }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">marca: </strong>{{ $poliza->vehicle_brand }}</td>
                <td><strong style="font-size:8px;">placa: </strong>{{ $poliza->vehicle_registration }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">modelo: </strong>{{ $poliza->vehicle_model }}</td>
                <td><strong style="font-size:8px;">tipo: </strong>{{ $poliza->vehicle_type }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">año: </strong>{{ $poliza->vehicle_year }}</td>
                <td><strong style="font-size:8px;">s/m: </strong>{{ $poliza->vehicle_motor_serial }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">color: </strong>{{ $poliza->vehicle_color }}</td>
                <td><strong style="font-size:8px;">s/c: </strong>{{ $poliza->vehicle_bodywork_serial }}</td>
            </tr>
        </table>
        <table class="tablev">
            <tr>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Emisión:
                    </strong>{{\Carbon\Carbon::parse($poliza->created_at)->format('d/m/Y')}}</td>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Vigencia </strong>1 Año</td>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Vence:
                    </strong>{{\Carbon\Carbon::parse($poliza->expiring_date)->format('d/m/Y')}}</td>
            </tr>
        </table>
        {{-- carnet 2 --}}
        <table class="tablecarnet2" style="position: absolute; top: 860px; left: 400px; width: 100%;" cellspacing="0">

            <tr>
                <td style="font-size: 8px;  font-weight: 100;">Contactos:</td>
            </tr>
            <tr>
                <td style="font-size: 8px; font-weight: 100;">
                    <img src="{{ asset('images/icons/llamada.png') }}" alt="Ícono de llamada"
                        style="width: 10px; vertical-align: middle;" /> &nbsp;
                    @if($poliza->type == 1)
                    0412-1723470
                    @elseif($poliza->type == 2)
                    0414-4861041
                    @elseif($poliza->type == 3)
                    0424-3736640
                    @elseif($poliza->type == 4)
                    0412-9393602
                    @elseif($poliza->type == 5)
                    0424-2984188
                    @elseif($poliza->type == 6)
                    0424-2153632
                    @elseif($poliza->type == 7)
                    416-8176295
                    @elseif($poliza->type == 8)
                    414-9032762
                    @elseif($poliza->type == 9)
                    412-3053539
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size: 8px; font-weight: 100;">
                    <img src="{{ asset('images/icons/whatsapp.png') }}" alt="Ícono de wp"
                        style="width: 10px; vertical-align: middle;" /> &nbsp;
                    @if($poliza->type == 1)
                    0424-3492963
                    @elseif($poliza->type == 2)
                    0414-4861042
                    @elseif($poliza->type == 3)
                    0424-4436226
                    @elseif($poliza->type == 4)
                    0412-9393602
                    @elseif($poliza->type == 5)
                    0424-2984188
                    @elseif($poliza->type == 6)
                    0424-2153632
                    @elseif($poliza->type == 7)
                    416-8176295
                    @elseif($poliza->type == 8)
                    414-9032762
                    @elseif($poliza->type == 9)
                    412-3053539
                    @endif
                </td>
            </tr>
            <tr>
                <td style="font-size: 8px; font-weight: 100;">
                    <img src="{{ asset('images/icons/ig.png') }}" alt="Ícono de ig"
                        style="width: 10px; vertical-align: middle;" /> &nbsp;
                    @cooperativaliderseguros
                </td>
            </tr>
            <tr>
                <td style="font-size: 8px; font-weight: 100;">
                    <img src="{{ asset('images/icons/correo.png') }}" alt="Ícono de correo"
                        style="width: 10px; vertical-align: middle;" /> &nbsp;
                    info@liderdeseguros.com
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
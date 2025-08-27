<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PDF - Poliza</title>
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
        margin-top: 3.5cm;
        margin-bottom: 1cm;
        margin-left: 1cm;
        margin-right: 1cm;
    }



    * {
        font-family: arial, "sans-serif";
        font-size: 11px;
    }

    #watermark {
        position: fixed;
        bottom: 0px;
        left: 0px;
        /** El ancho y la altura pueden cambiar
                    según las dimensiones de su membrete
                **/
        width: 21.6cm;
        height: 27.94cm;

        /** Tu marca de agua debe estar detrás de cada contenido **/
        z-index: -1000;
    }

    table,
    th,
    td {
        padding: 3px;
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
        top: 670px;
        left: 30px;
        width: 40%;
        font-size: 8px;
        padding: 1px;
    }

    .tableve {
        position: absolute;
        top: 720px;
        left: 105px;
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
        top: 745px;
        left: 30px;
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
        top: 804px;
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
        top: 670px;
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
</style>

<body>

    <div id="watermark">
        <img src="{{asset('images/hoja2.jpg')}}" height="100%" width="100%" />
    </div>



    <table width="100%" border="0" align="center" style="margin-top: 40px; border:none;" cellspacing="0"
        bordercolor="#000000" class="borde">

        <tr>
            <th colspan="5" style="font-size: 12px; text-align: center;">DATOS GENERALES:</th>
        </tr>
        <tr>

            <td><strong>Vigencia: </strong><i>1 Año</i></td>
            <td><strong>Desde: </strong><i>{{\Carbon\Carbon::parse($policy->created_at)->format('d/m/Y')}}</i></td>
            <td><strong>Hasta: </strong><i>{{\Carbon\Carbon::parse($policy->expiring_date)->format('d/m/Y')}}</i></td>
            <td><strong>Poliza N°: </strong><i>{{$policy->id}}</i></td>
        </tr>

        <tr>
            @if($policy->type == 2)
            <td colspan="1" align="center"><strong>Asesor: </strong><i>Oriana Silva</i></td>
            <td><strong>Teléfono: </strong><i>0414-4861042</i></td>
            @elseif($policy->type == 1)
            <td colspan="2" align="center"><strong>Asesor: </strong><i>Eduardo Ortega </i></td>
            <td><strong>Teléfono: </strong><i>0414-4889333</i></td>
            @else
            <td colspan="3" align="center"><strong>Asesor: </strong><i>Liliana Tovar</i></td>
            <td><strong>Teléfono: </strong><i>0424-4436226</i></td>

            @endif
            <td colspan="2"><strong>Sucursal: </strong><i>{{$policy->user->office->municipio->municipio.',
                    '.$policy->user->office->estado->estado.' '.$policy->user->office->id}}</i></td>
        </tr>
    </table>

    <table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000" class="borde">

        <tr>
            <th colspan="4" style="font-size: 12px; text-align: center;">DATOS DEL ASEGURADO: </th>
        </tr>
        <tr align="center">
            <td colspan="2" width="50%"><b>Contratante: </b></td>
            <td colspan="2"><b>Rif/C.I: </b></td>
        </tr>

        <tr align="center">
            <td colspan="2"><i>{{$policy->client_name_contractor. " " .$policy->client_lastname_contractor}}<i></td>
            <td colspan="2"><i>{{$policy->client_ci_contractor}}</i></td>
        </tr>

        <tr align="center">
            <td><b>Beneficiario: </b></td>
            <td><b>Rif/C.I: </b></td>
            <td><b>Teléfono: </b></td>
            <td><b>Dirección: </b></td>
        </tr>

        <tr align="center">
            <td><i>{{$policy->client_name. " " .$policy->client_lastname}}</i></td>
            <td><i>{{$policy->client_ci}}</i></td>
            <td><i>{{$policy->client_phone}}</i></td>
            <td width="35%"><i>{{$policy->municipio->municipio.', '.$policy->estado->estado}}</i></td>
        </tr>
    </table>

    <table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000" class="borde">

        <tr>
            <th colspan="4" style="font-size: 12px; text-align: center;">DATOS DEL VEHÍCULO: </th>
        </tr>
        <tr>
            <td><strong>Marca: </strong><i>{{$policy->vehicle_brand}}</i></td>
            <td><strong>Color: </strong><i>{{$policy->vehicle_color}}</i></td>
            <td><strong>Tipo: </strong><i>{{$policy->vehicle_type}}</i></td>
            <td width="35%"><strong>S/Carroceria: </strong><i>{{$policy->vehicle_bodywork_serial}}</i></td>

        </tr>

        <tr>
            <td><strong>Modelo: </strong><i>{{$policy->vehicle_model}}</i></td>
            <td><strong>Placa: </strong><i>{{$policy->vehicle_registration}}</i></td>
            <td><strong>Clase: </strong><i>{{$policy->class->class}}</i></td>
            <td><strong>S/Motor: </strong><i>{{$policy->vehicle_motor_serial}}</i></td>
        </tr>

        <tr>
            <td><strong>Año: </strong><i>{{$policy->vehicle_year}}</i></td>
            <td><strong>Uso: </strong><i>{{$policy->used_for}}</i></td>
            <td><strong>Peso: </strong><i>{{$policy->vehicle_weight}}</i></td>
            <td><strong>N° de certificado: </strong>{{$policy->vehicle_certificate_number}}</td>
            </td>
        </tr>

    </table>

    <table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000" class="borde">

        <tr>
            <th colspan="3" style="font-size: 12px; text-align: center;">{{$policy->price->description}} </th>
        </tr>
        <tr style="font: italic;" style="font-weight: bold,;" align="center">
            <td>Cobertura:</td>

            <td>Suma Asegurada:</td>
            <td>Prima:</td>
        </tr>

        <tr style="font: italic;">
            <td>Daños a cosas: </td>
            <td align="center">{{number_format($policy->damage_things, 2)}} $</td>
            <td align="center">{{number_format($policy->premium1, 2)}} $</td>
        </tr>

        <tr style="font: italic;">
            <td>Daños a personas: </td>
            <td align="center">{{number_format($policy->damage_people, 2)}} $</td>
            <td align="center">{{number_format($policy->premium2, 2)}} $</td>
        </tr>

        <tr style="font: italic;">
            <td>Asistencia jurídica: </td>
            <td align="center">{{number_format($policy->legal_assistance, 2)}} $</td>
            <td align="center">{{number_format($policy->premium3, 2)}} $</td>
        </tr>

        <tr style="font: italic;">
            <td>Muerte: </td>
            <td align="center">{{number_format($policy->death, 2)}} $</td>
            <td align="center">{{number_format($policy->premium4, 2)}} $</td>
        </tr>

        <tr style="font: italic;">
            <td>Invalidez: </td>
            <td align="center">{{number_format($policy->disability, 2)}} $</td>
            <td align="center">{{number_format($policy->premium5, 2)}} $</td>
        </tr>


        <tr style="font: italic;">
            <td>Gastos médicos: </td>
            <td align="center">{{number_format($policy->medical_expenses, 2)}} $</td>
            <td align="center">{{number_format($policy->premium6, 2)}} $</td>
        </tr>

        <tr style="font: italic;">
            @if($policy->damage_passengers == 0)
            <td></td>
            <td></td>
            <td></td>

            @else
            <td>Daño a pasajeros: </td>
            <td align="center">{{number_format($policy->damage_passengers, 2)}} $</td>
            <td align="center">{{number_format($policy->premium7, 2)}} $</td>
            @endif
        </tr>
        <tr style="font: italic;">
            @if($policy->crane == 0)
            <td></td>
            <td></td>
            <td></td>
            @else
            <td>Servicio de Grua: </td>
            <td align="center" align="center">{{number_format($policy->crane, 2)}} $</td>
            <td align="center" align="center"></td>
            @endif
        </tr>

        <tr style="font: italic;">
            @if($policy->limited == 0)
            <td></td>
            <td></td>
            <td></td>
            @else
            <td>Responsabilidad Civil Complementaria: </td>
            <td align="center" align="center">{{number_format($policy->limited, 2)}} $</td>
            <td align="center" align="center"></td>
            @endif
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>

        <tr style="font: italic;">
            <td align="center"><strong>Total: </strong></td>
            <td align="center">{{number_format($policy->total_all, 2)}} $</td>
            <td align="center">{{number_format($policy->total_premium, 2)}} $</td>
        </tr>
    </table>
    <br>

    <table class="firmas" width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000">
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
            <td width="50%" style="font-size: 12px;  text-align: center;">{{$policy->client_name_contractor.'
                '.$policy->client_lastname_contractor}}</td>
        </tr>

    </table>
    <div class="carnet">
        <table class="tablecarc" style="position: absolute; top: 645px; left: 95px; width: 18%;" cellspacing="0">

            <tr>
                <td colspan="2" style="font-size: 10px;  font-weight: 100;">Certificado de RCV</td>
            </tr>
        </table>


        <table class="tablecarc2">
            <tr>
                <td><strong style="font-size:8px;">Beneficiario:</strong></td>
                <td><strong style="font-size:8px;">Contratante:</strong></td>
            </tr>
            <tr>
                <td>{{ $policy->client_name.' '.$policy->client_lastname}}</td>
                <td>{{ $policy->client_name_contractor.' '.$policy->client_lastname_contractor}}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">Cedula:</strong></td>
                <td><strong style="font-size:8px;">Cedula:</strong> </td>
            </tr>
            <tr>
                <td>{{ $policy->client_ci }}</td>
                <td>{{ $policy->client_ci_contractor }}</td>
            </tr>
        </table>

        <table class="tableve" cellspacing="0">

            <tr>
                <td colspan="2" style="font-size: 10px;  font-weight: 100;">DATOS DEL VEHÍCULO</td>
            </tr>
        </table>

        <table class="tableved">
            <tr>
                <td><strong style="font-size:8px;">Clase: </strong>{{ $policy->class->class }}</td>
                <td><strong style="font-size:8px;">Uso: </strong>{{ $policy->used_for }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">marca: </strong>{{ $policy->vehicle_brand }}</td>
                <td><strong style="font-size:8px;">placa: </strong>{{ $policy->vehicle_registration }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">modelo: </strong>{{ $policy->vehicle_model }}</td>
                <td><strong style="font-size:8px;">tipo: </strong>{{ $policy->vehicle_type }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">año: </strong>{{ $policy->vehicle_year }}</td>
                <td><strong style="font-size:8px;">s/m: </strong>{{ $policy->vehicle_motor_serial }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">color: </strong>{{ $policy->vehicle_color }}</td>
                <td><strong style="font-size:8px;">s/c: </strong>{{ $policy->vehicle_bodywork_serial }}</td>
            </tr>
        </table>
        <table class="tablev">
            <tr>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Emisión:
                    </strong>{{\Carbon\Carbon::parse($policy->created_at)->format('d/m/Y')}}</td>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Vigencia </strong>1 Año</td>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Vence:
                    </strong>{{\Carbon\Carbon::parse($policy->expiring_date)->format('d/m/Y')}}</td>
            </tr>
        </table>
        {{-- carnet 2 --}}
        <table class="tablecarnet2" style="position: absolute; top: 645px; left: 450px; width: 18%;" cellspacing="0">

            <tr>
                <td colspan="2" style="font-size: 10px;  font-weight: 100;">Certificado de RCV</td>
            </tr>
        </table>


        <table class="tablecarc3">
            <tr>
                <td><strong style="font-size:8px;">Beneficiario:</strong></td>
                <td><strong style="font-size:8px;">Contratante:</strong></td>
            </tr>
            <tr>
                <td>{{ $policy->client_name.' '.$policy->client_lastname}}</td>
                <td>{{ $policy->client_name_contractor.' '.$policy->client_lastname_contractor}}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">Cedula:</strong></td>
                <td><strong style="font-size:8px;">Cedula:</strong> </td>
            </tr>
            <tr>
                <td>{{ $policy->client_ci }}</td>
                <td>{{ $policy->client_ci_contractor }}</td>
            </tr>
        </table>

        <table class="tableve2" cellspacing="0">

            <tr>
                <td colspan="2" style="font-size: 10px;  font-weight: 100;">DATOS DEL VEHÍCULO</td>
            </tr>
        </table>

        <table class="tableved2">
            <tr>
                <td><strong style="font-size:8px;">Clase: </strong>{{ $policy->class->class }}</td>
                <td><strong style="font-size:8px;">Uso: </strong>{{ $policy->used_for }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">marca: </strong>{{ $policy->vehicle_brand }}</td>
                <td><strong style="font-size:8px;">placa: </strong>{{ $policy->vehicle_registration }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">modelo: </strong>{{ $policy->vehicle_model }}</td>
                <td><strong style="font-size:8px;">tipo: </strong>{{ $policy->vehicle_type }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">año: </strong>{{ $policy->vehicle_year }}</td>
                <td><strong style="font-size:8px;">s/m: </strong>{{ $policy->vehicle_motor_serial }}</td>
            </tr>
            <tr>
                <td><strong style="font-size:8px;">color: </strong>{{ $policy->vehicle_color }}</td>
                <td><strong style="font-size:8px;">s/c: </strong>{{ $policy->vehicle_bodywork_serial }}</td>
            </tr>
        </table>
        <table class="tablev2">
            <tr>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Emisión:
                    </strong>{{\Carbon\Carbon::parse($policy->created_at)->format('d/m/Y')}}</td>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Vigencia </strong>1 Año</td>
                <td style="font-size:8px;"> <strong style="font-size:8px;">Vence:
                    </strong>{{\Carbon\Carbon::parse($policy->expiring_date)->format('d/m/Y')}}</td>
            </tr>
        </table>
    </div>


</body>

</html>
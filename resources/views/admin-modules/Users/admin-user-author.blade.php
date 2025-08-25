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
        top: 700px;
        left: 300px;
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
    .cont{
        text-align: justify;
    }



</style>
<body>

    <div class="logo"> <img src="{{asset('images/lgoo2-min.png')}}"  height="100%" width="100%" /></div>

    <h3 style="text-align: center; margin-top:25px;"> AUTORIZACIÓN</h3>

    @php
        $c = $user->ci;
        $clave = intval(preg_replace('/[^0-9]+/', '', $c), 10);
    @endphp
<div class="cont">
    <p class="pr">
        <strong>LIDER SEGUROS PARA VEHÍCULOS, R.L </strong>J-311050906, Inscrita en la Superintendencia de la Actividad Aseguradora bajo el N° 15, Providencia SAA-D-L-2-5-0109, desde el mes de Mayo del año 2018. <br>
        <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Autoriza al Ciudadano(a) <strong>{{ $user->name.' '.$user->lastname }}</strong> titular de la cedula de identidad enmarcado con el numero <strong>{{ $user->ci }},</strong> domiciliado en el
        Municipio <strong>{{ $user->office->municipio->municipio }}</strong>, Parroquia <strong>{{ $user->office->parroquia->parroquia }}</strong>, Estado <strong>{{ $user->office->estado->estado }}</strong>, a vender pólizas de Responsabilidad Civil (RCV) en represanción de nuestra empresa. <br>
        <br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Esta autorización se otorga con carácter general, para que el vendedor pueda ofrecer y vender pólizas de RCV a cualquier persona natural o jurídica que lo solicite.
    </p>
    <p>
        El vendedor deberá cumplir con los siguientes requisitos para poder vender pólizas de RCV: <br>
        <ul>
            <li>Estar debidamente capacitad para ofrecer y vender pólizas de seguros.</li>
            <li>Tener conocimiento de las condiciones y coberturas de las pólizas de RCV que ofrece nuestra empresa.</li>
            <li>Cumplir con las normas y procedimientos establecidos por la Superintendencia de la Actividad Aseguradora.</li>
        </ul>
    </p>
    <p>
        En caso de que el vendedor incumpla con alguno de los requisitos establecidos, o cometa cualquier acto que atente contra los intereses de nuestra empresa, la autorización podrá ser revocada en cualquier momento.
        <br><br>
        En prueba de conformidad, se firma dos ejemplares de la presente autorización en la fecha y lugar indicados al inicio del mismo.
    </p>



    <p>Se dejará constancia por este medio todos los equipos y material asignados para la efectiva elaboración de ventas de Seguros.</p>
</div>


    <div class="firma" style="margin-top: 74px; ">
    <div class="">
        <img id="sello" src="{{asset('images/fima.png')}}"  height="100%" width="100%">
    </div>

    <table style="width: 100%; text-align: center;">
        <tr>
            <td>___________________________________</td>
        </tr>
        <tr>
            <td><strong> GERENTE GENERAL REGIONAL </strong></td>

        </tr>
        <tr>

            <td><strong>COOPERATIVA LIDER DE SEGUROS PARA VEHICULO, R.L.</strong></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>


    </table>
</div>
</body>
</html>

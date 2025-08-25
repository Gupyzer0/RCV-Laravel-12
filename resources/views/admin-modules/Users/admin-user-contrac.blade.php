<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Honorarios Profesionales</title>
</head>

<style>
    @page {

                margin: 0cm 0cm;
            }

  *{
    font-family: "Times New Roman", "sans-serif";

  }
      body {
                margin-top:    4cm;
                margin-bottom: 3cm;
                margin-left:   4cm;
                margin-right:  3cm;
                font-size: 13;

            }
            .header {
    width: 100%;
    height: 2.4cm; /* Altura del encabezado */
    position: fixed; /* Asegura que el encabezado esté fijo en cada página */
    top: 1.5cm; /* Posición en la parte superior */
    left: 1.3cm; /* Posición en la parte izquierda */
    right: 0; /* Posición en la parte derecha */
    z-index: 1000; /* Asegura que esté por encima del contenido */
}

.logo {
    width: 13cm;
    height: 2.4cm;
    opacity: 0.4;
    margin-left: 3cm;


}


    .pr{

        text-align: justify;
        /* text-indent:3em; */
        line-height: 30px;

    }

</style>

<body>
    <div class="header">
        <div class="logo">
            <img src="{{ asset('images/logo.jpg') }}" height="100%" width="100%" />
        </div>
    </div>

    <div>

        <p class="pr">
            Entre la <strong>COOPERATIVA LÍDER SEGUROS PARA VEHICULOS R.L.,</strong> <br> debidamente inscrita por ante la Oficina Subalterna de
            Registro Público del Municipio Chacao, Estado Miranda (ahora Estado Bolivariano de Miranda), en fecha 04 de febrero 2024, bajo el
            Nº. 18, Tomo 5, protocolo Primero, modificados sus estatutos según Acta de Asamblea  Extraordinaria de Asociados,  inscrita ante la
            citada Oficina Subalterna en fecha 05 de agosto 2024, bajo el Nº. 03, Tomo 18, Protocolo de transcripción del año 2024, debidamente
            inscrita ante la Superintendencia Nacional de Cooperativas, bajo el Expediente Nº. 95198, de fecha 23 de noviembre 2005 y ante la
            Superintendencia de la Actividad Aseguradora bajo el Nº 15el 14 de septiembre del 2018 e identificada con el número de Registro de
            Información Fiscal RIF. J311050906, representada en este acto por el ciudadano  <strong>EDUARDO ANTONIO ORTEGA MONTOYA</strong>,
            venezolano, titular de la cédula de identidad <strong>Nº.V-26.328.603</strong>, mayor de edad, mayor de edad, actuando en su
            carácter de <strong>Tesorero</strong>, carácter  que consta en el Acta de Asamblea Extraordinaria, antes identificada, quien en lo sucesivo y a
            los efectos del presente contrato se denominará <strong>LA COOPERATIVA</strong> por una parte y por la otra,

{{-- datos vendedor --}}
@php
    // Extraer solo la parte numérica de la cédula
    $ci_number = str_replace('V-', '', $user->ci); // O 'E-' si también manejas otro tipo de cédula
@endphp
<strong style="text-transform: uppercase;">{{$user->name. ' '.$user->lastname}}</strong> venezolano, titular de la cédula de identidad <strong>Nº. V-{{ number_format($ci_number, 0, '', '.') }},</strong> de profesión comerciante,
quien en lo sucesivo y a los efectos del presente contrato se denominará <strong>EL CONTRATADO</strong>, quienes a los mismos efectos podrán nombrarse como
<strong>LAS PARTES</strong>. Hemos convenido en celebrar el presente <strong>CONTRATO POR PRESTACION DE SERVICIOS</strong>, el cual se encuentra contenido en las siguientes
cláusulas: <strong>PRIMERA</strong>: <strong>EL CONTRATADO</strong> se obliga a prestar sus servicios como promotor de ventas de Seguros de Responsabilidad Civil para Vehículos,
para la <strong>ASOCIACIÒN COOPERATIVA LIDER DE SEGUROS PARA VEHICULOS R.L.</strong> <strong>SEGUNDA</strong>: <strong>LA COOPERATIVA</strong> se obliga para con <strong>EL CONTRATADO</strong>, a facilitar el
material para realizar los trabajos y demás actividades propias de su oficio y <strong>EL CONTRATADO</strong> a poner al servicio de <strong>LA COOPERATIVA</strong> toda su
capacidad, experticia, habilidad y destreza de su oficio para ejercer las funciones para lo cual es contratado, así como aquellas relacionadas
o conexas con éstas. <strong>TERCERA: EL CONTRATADO</strong> se obliga a guardar la más estricta confidencialidad respecto a la información y documentación que
hubiese conocido en general o en razón y con ocasión de sus actividades para la cual es contratado. <strong>CUARTA</strong>: Las partes contratantes de mutuo acuerdo,
han consentido que la duración del presente contrato será por seis (6) meses a partir de la fecha de la firma del mismo. <strong>QUINTA</strong>: <strong>LA COOPERATIVA</strong>, no
está obligada a dar continuidad o renovar el presente contrato bajo ninguna circunstancia, excepto que ambas partes contratantes estén de acuerdo.
En el supuesto caso que <strong>EL CONTRATADO</strong> no desee continuar prestando sus servicios, deberá notificarlo por escrito a <strong>LA COOPERATIVA</strong>, en un lapso de
tiempo no mayor de treinta (30) días calendarios continuos de anticipación; de igual modo, <strong>LA COOPERATIVA</strong> notificará a <strong>EL CONTRATADO</strong> en un lapso
de tiempo no mayor de treinta (30) días calendarios continuos de anticipación, si renovará o no el presente contrato. En caso que las partes
contratantes resuelvan continuar la relación contractual, se procederá a elaborar otro contrato en las condiciones que acuerden las partes.
El presente contrato podrá darse por terminado por mutuo acuerdo entre <strong>LAS PARTES</strong>, sin necesidad que haya expirado la vigencia del mismo o
en forma unilateral por el incumplimiento de cualquiera de las obligaciones derivadas del presente contrato <strong>SEXTA</strong>: <strong>EL CONTRATADO</strong> se obliga: a)
Instalar y promover Publicidad con especificación expresa del producto Seguros de Responsabilidad Civil para Vehículos; b)Informar a los potenciales
tomadores, asegurados, beneficiarios, contratantes, usuarios y afiliados que la responsabilidad por los productos suscrito corresponde a la aseguradora
<strong>COOPERATIVA LIDER DE SEGUROS PARA VEHICULOS, R.L.</strong>; c)Suministrar al tomador de pólizas de Seguros de Responsabilidad Civil para Vehículos, los precios
de nuestras pólizas e informar que los precios actuales son establecidos por la Superintendencia de la Actividad Aseguradora, según <strong>Providencia
Administrativa Nº.SAA-01-0512-204</strong> de fecha veintinueve (29) de agosto 2024. d) Notificar al tomador de pólizas de Seguros de Responsabilidad Civil
para Vehículos, que las tarifa a cobrar por la póliza emitida, debe ser calculada a la moneda de mayor valor según el Tipo de Cambio de Referencia
(<strong>TCR</strong>) para el día en que se venda la póliza, tal como lo contempla el artículo 4 de la Reforma del Decreto con rango, valor y fuerza de ley de la
Actividad Aseguradora de fecha 29 de noviembre de 2023. e) Acepta que está prohibidos los recargos y las rebajas en la prima de las pólizas de
Seguros de Responsabilidad Civil para Vehículos, así como condicionar la venta de un producto simplificado. f) Indicar expresamente al tomador de
la póliza que los pagos de primas o cuotas realizadas, deben ser efectuados directamente a la Asociación Cooperativa en la cuenta jurídica a nombre
de <strong>LA COOPERATIVA</strong> Líder de Seguros Para Vehículos R.L. g) Solicitar al tomador los siguientes documentos: <strong>Rif</strong>, Cédula de Identidad, Título de
Propiedad del Vehículo, Licencia de Conducir, Certificado Médico Vial y otros que sea exigidos, los cuales deben ser enviado por vía Correo Electrónico
y WhatsApp a <strong>LA COOPERATIVA</strong> Líder de Seguros para Vehículos; h) Cargar en la planilla que será entregada oportunamente, los datos del tomador y
beneficiario, del certificado del vehículo a asegurar y los que sean requeridos; i) Enviar a <strong>LA COOPERATIVA</strong> Líder de Seguros Para Vehículos,
por vía Correo Electrónico y WhatsApp la póliza a cotizar y del soporte de pago para que la misma sea verificada de manera inmediata.
(Este envió lo efectuara el cliente directamente); j) Cargar en la planilla la forma de pago: dólares <strong>USD</strong>, euros <strong>€</strong> y número de referencia
del banco receptor; k) Notificar semanalmente a <strong>LA COOPERATIVA</strong> Líder de Seguros Para Vehículo, la cantidad semanal debidamente enumerada y
calculada de los tomadores y el número de pólizas adquiridas, para realizar el procedimiento de pago de la comisión por venta; l) Garantizar
que la información suministrada por los tomadores, aseguradores, beneficiarios, contratantes, usuarios y afiliados sea confidencial y no
susceptible a divulgación a terceros no autorizados por <strong>LA COOPERATIVA</strong> Líder de Seguros Para Vehículos. m) Remitir copia de la relación de
actividades realizadas correspondiente y los documentos o informes que le sean requeridos. <strong>SEPTIMA</strong>: <strong>LAS PARTES</strong> contratantes de mutuo acuerdo
establecen que el pago por los servicios prestados será formalizado en los siguientes términos: <strong>LA COOPERATIVA</strong> se compromete a pagar al
contratado, comisiones del 10 % de las ventas y otorgará una bonificación equivalente al 10% adicional por concepto de pronto pago.
El porcentaje será pagado en un periodo no mayor de tres (3) días consecutivo posterior al corte de venta.




            <br><br><br><br><br>
            <strong style="">
                <strong>EL CONTRATADO</strong>
                <b>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <div style="text-align: center;">
                        <!-- Inserta la imagen encima del nombre de la cooperativa -->
                        <img src="{{asset('images/firmaE.png')}}" alt="Firma" style="max-width: 100px; display: block; margin: 0 auto;">
                        <br>
                        LA ASOCIACIÓN COOPERATIVA DE SEGUROS PARA VEHÍCULOS R.L.
                    </div>
                </b>
            </strong>

        </p>
    </div>

</body>



</html>

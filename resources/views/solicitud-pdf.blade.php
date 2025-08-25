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

                margin-bottom: 1cm;
                margin-left:   1cm;
                margin-right:  1cm;
            }



  *{
    font-family: arial, "sans-serif";
    font-size: 8px;
  }
  table {
      border-collapse: collapse; /* Une bordes de celdas */
      margin: 0; /* Sin márgenes */
      padding: 0; /* Sin relleno */
      width: 100%; /* Opcional: ajusta el ancho */

    }
  .union{
    margin: 0;
    padding: 0;

  }


  td, th{
    border: 1px solid black;
    text-align: left;
  }
  .linear{
    font-size: 12px;
    text-align: center;
    background: rgb(185, 0, 0);
    color: white;
  }



  .logo{
    position: absolute;
    top: 40px;

    height: 58px;
    width: 250px;
  }
  .watermark {
    position: absolute;
    top: 35%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 130px;
    color: rgba(0, 0, 0, 0.4);
    z-index: -1;
    white-space: nowrap;
}
</style>

<body>
    <div class="watermark">NO VALIDO </div>
    <div style="margin-bottom: 20px;">
        <img src="{{ asset('images/logob.jpg') }}" alt="Logo" class="logo" >
    </div>
    <div class="union">
        <table class="borde" style="margin-top: 120px" >
            <tr>
              <th colspan="8" class="linear"> Solicitud de Seguro de Responsabilidad Civil de Vehículos <br>
                Datos del Tomador</th>
            </tr>

            <tr>
                <th width= 22%>Apellido(s) y Nombre(s) / Razón Social:</th>
                <td colspan="2" width= 25%>{{$policy->client_name_contractor. " " .$policy->client_lastname_contractor}}</td>
                <th width= 13%>CI/Pasaporte: </th>
                <td width= 10%>{{$policy->client_ci_contrator}}</td>
                <th width= 7%>Correo</th>
                <td colspan="2" width= 23%>{{$policy->client_email_contrator}}</td>
            </tr>

            <tr>
                <th colspan="8" class="linear">Datos del Representante del Tomador</th>
            </tr>

            <tr>
                <th>Apellido(s) y Nombre(s) / Razón Social:</th>
                <td colspan="2">{{$policy->client_name.' '.$policy->client_lastname}}</td>
                <th>CI/Pasaporte: </th>
                <td>{{$policy->client_ci}}</td>
                <th>Correo:</th>
                <td colspan="2">{{$policy->client_email}}</td>
            </tr>

            <tr>
                <th>Fecha de Nacimiento:</th>
                <td>{{\Carbon\Carbon::parse($policy->fecha_n)->format('d/m/Y')}}</td>

                <th>Edad:</th>
                <td>{{$edad}}</td>
                <th>Estado Civil:</th>
                <td>{{$policy->estadocivil}}</td>
                <th>Genero:</th>
                <td>{{$policy->genero}}</td>
            </tr>
            <tr>
                <th>Telefono</th>
                <td>{{$policy->client_phone}}</td>
                <th>Estado:</th>
                <td>{{$policy->estado->estado}}</td>
                <th>Municipio:</th>
                <td>{{$policy->municipio->municipio}}</td>
                <th>Parroquia:</th>
                <td>{{$policy->parroquia->parroquia}}</td>
            </tr>
            <tr>
                <th colspan="">Direccion Especifica:</th>
                <td colspan="7">{{$policy->client_address}}</td>
            </tr>
        </table>






        <table>
            <tr> <th class="linear" colspan="8">Datos del Vehículo</th></tr>

            <tr>
                <th width=8%>No. Placa:</th>
                <td>{{$policy->vehicle_registration}}</td>
                <th width=12%>Marca:</th>
                <td>{{$policy->	vehicle_brand}}</td>
                <th width=12%>Modelo:</th>
                <td>{{$policy->vehicle_model}}</td>
                <th width=14%>Año:</th>
                <td>{{$policy->vehicle_year}}</td>
            </tr>

            <tr>
                <th>Clase:</th>
                <td>{{$policy->class->class}}</td>
                <th>N° de Puestos:</th>
                <td>{{$policy->vehicle_certificate_number}}</td>
                <th>Peso Kg o Cap. TM:</th>
                <td>{{$policy->vehicle_weight}}</td>
                <th>Color:</th>
                <td>{{$policy->vehicle_color}}</td>
            </tr>
            <tr>
                <th>Kms:</th>
                <td></td>
                <th>Serial de Carroceria:</th>
                <td>{{$policy->vehicle_bodywork_serial}}</td>
                <th>Serial de Motor:</th>
                <td>{{$policy->vehicle_motor_serial}}</td>
                <th>N° Certificado de Origen:</th>
                <td>{{$policy->vehicle_certificate_number}}</td>
            </tr>
        </table>

        <table >
            <tr> <th colspan="3" class="linear" colspan="8">Coberturas Solicitadas</th></tr>
            @php
                $pdolar = $euro/$dolar;
            @endphp
            <tr>
                <th>Plan: {{$policy->price->description}}</th>
                <th style="text-align: center;">Cobertura</th>
                <th style="text-align: center;">Prima</th>
            </tr>

            <tr>
                <td>{{$policy->price->campo}} </td>
                <td align="center">{{number_format($policy->price->campoc * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop * $pdolar, 2)}} $</td>
              </tr>

              <tr>
                <td>{{$policy->price->campo1}} </td>
                <td align="center">{{number_format($policy->price->campoc1 * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop1 * $pdolar, 2)}} $</td>
              </tr>

              @if($policy->price->campo2)
              <tr>
                <td>{{$policy->price->campo2}} </td>
                <td align="center">{{number_format($policy->price->campoc2 * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop2 * $pdolar, 2)}} $</td>
              </tr>
              @else
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              @endif

              @if($policy->price->campo3)
              <tr>
                <td>{{$policy->price->campo3}} </td>
                <td align="center">{{number_format($policy->price->campoc3 * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop3 * $pdolar, 2)}} $</td>
              </tr>
              @else
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              @endif
              @if($policy->price->campo4)
              <tr>
                <td>{{$policy->price->campo4}}</td>
                <td align="center">{{number_format($policy->price->campoc4 * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop4 * $pdolar, 2)}} $</td>
              </tr>
              @else
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              @endif

              @if($policy->price->campo5)
              <tr>
                <td>{{$policy->price->campo5}} </td>
                <td align="center">{{number_format($policy->price->campoc5 * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop5 * $pdolar, 2)}} $</td>
              </tr>
              @else
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              @endif
              @if($policy->price->campo6)
              <tr>
                <td>{{$policy->price->campo6}} </td>
                <td align="center">{{number_format($policy->price->campoc6 * $pdolar, 2)}} $</td>
                <td align="center">{{number_format($policy->price->campop6 * $pdolar, 2)}} $</td>
              </tr>
              @else
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              @endif

              @if($policy->trailer)
              <tr>
                <td>Extension trailer</td>
                <td style="font-size: 9px">Queda entendido y convenido que se excluye el traslado de materiales de construcción y transporte de químicos y explosivos</td>
                <td>20%</td>
              </tr>
              @endif
              @if($policy->price_id == 36)
              @php
                  $add = (5 * $euro)/$dolar;
                  $vehicle_weight = preg_replace('/\D/', '', $policy->vehicle_weight);
                  $tone = ceil($vehicle_weight / 1000)  - 12;
                  $ttone = $tone * $add;
              @endphp
              <tr>
                <td>Tonelada Adicional </td>
                <td>{{$tone }}</td>
                <td>{{number_format($ttone, 2)}}$</td>
              </tr>
              @endif

              <tr>
                <td align="center"><strong>Total: </strong></td>
                <td align="center">&nbsp;</td>
                <td align="center">{{number_format($policy->total_premium * $pdolar, 2)}} $</td>
              </tr>
        </table>

    </table>

    <table>
        <tr> <th colspan="2" class="linear">Declaración de Fe</th></tr>

        <tr>
            <td colspan="2" style="text-align: justify; border-bottom: none;"><strong>Declaro que todos los datos arriba indicados son ciertos y deberán servir de base para la emisión de la póliza, ya que la validez de dicho seguro depende
                esencialmente de la exactitud de los datos e informes precedentes.
                Yo, el Tomador, doy fe que el dinero utilizado para el pago de la prima, proviene de una fuente lícita y por lo tanto, no tiene relación alguna con dinero, capitales,
                bienes, haberes, valores o títulos producto de las actividades o acciones derivadas de operaciones ilícitas previstas en las Normas sobre Prevención, Control y
                Fiscalización de los Delitos de Legitimación de Capitales, Financiamiento al Terrorismo y Financiamiento para la Proliferación de Armas de Destrucción Masiva, en la
                Actividad Aseguradora.</strong>
            </td>
        </tr>
        <tr>
            <td style=" padding-top: 10px; border-top: none; border-bottom: none;border-right: none;"><strong>Lugar:</strong> {{$user->office->municipio->municipio. ', ' .$user->office->estado->estado}}</td>
            <td style="border-top: none; border-bottom: none;border-left:none;"><strong>Fecha:</strong> {{\Carbon\Carbon::parse($hoy)->format('d/m/Y')}}</td>
        </tr>

        <tr>
            <td style="padding-top: 50px; border-top: none; border-right: none;padding-left: 20px;">
                <div style="border-bottom: 1px solid black; width: 200px; height: 40px; margin-top: 20px;"></div>
                <div style="margin-top: 5px; padding-bottom:80px;"><strong>Firma y Huella del Tomador</strong></div>
            </td>
            <td style="padding-top: 50px; border-top: none; border-left: none;padding-left: 20px;">
                <div style="border-bottom: 1px solid black; width: 200px; height: 40px; margin-top: 20px;"></div>
                <div style="margin-top: 5px; padding-bottom:80px;"><strong>Firma y Huella del Asegurado Titular</strong></div>
            </td>
        </tr>


    </table>




    </div>
</body>
</html>

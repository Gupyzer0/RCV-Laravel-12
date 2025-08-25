<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PDF - Poliza</title>
</head>

<style>
  *{
    font-family: arial, "sans-serif";
    font-size: 11px;
  }

  table, th, td{
    padding: 3px;
    /* border-bottom: 1px solid black; */
    border-collapse: collapse;
    
    
    text-align: left;
    text-transform: uppercase;
  }
  table.vehicle{
     border-collapse: collapse;
    border: 1px solid black;
  }

  td.vehicle{
    border-collapse: collapse;
    border: 1px solid black;
  }

  .caption{
    border-collapse: collapse;
    border: 1px solid black;
  }

 

</style>

<body>

  <table style="width:100%;">
    <caption style="display:block;margin-top:100px;"><h1 style="font-size: 14px; margin-bottom: none;">DATOS DE AFILIACIÓN:</h1></caption>
    <tr style="text-align:center;">
      <td style="width: 33%;"><strong>Número de afilición: </strong>{{$policy->id}}</td>
      <td style="width: 33%;"><strong>Emision: </strong>{{\Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
      <td style="width: 33%;"><strong>Vigencia: </strong>1 Año</td>
      <td style="width: 33%;"><strong>Vencimiento: </strong>{{\Carbon\Carbon::parse($policy->expiring_date)->format('d-m-Y')}}</td>
    </tr>
  </table> 

  <table style="width:100%; border-bottom: none; text-align: center; margin-top: 20px;" class="dp-table">
    <caption><h1 style="font-size: 14px; margin-bottom: none; margin-top: none;">DATOS PERSONALES:</h1></caption>
    <tr>
      <td class="rid-bb" style="width: 50%;"><strong>Contratante: </strong>{{$policy->client_name_contractor. " " .$policy->client_lastname_contractor}}</td>
      <td class="rid-bb" style="width: 50%;"><strong>Rif/Cédula: </strong>{{$policy->client_ci_contractor}}</td>
    </tr>
  </table>

  <table style="width:100%;" class="dp-table">
    <tr>
      <td><strong>Beneficiario: </strong>{{$policy->client_name. " " .$policy->client_lastname}}</td>
      <td><strong>Rif/Cédula: </strong>{{$policy->client_ci}}</td>
      <td><strong>Dirección: </strong>{{$policy->estado->estado.' '.$policy->municipio->municipio}}</td>
      <td><strong>Teléfono: </strong>{{$policy->client_phone}}</td>
      <td><strong>Email: </strong>{{$policy->client_email}}</td>
    </tr>
  </table>

  <table style="width:100%; margin-top: 20px;" class="vehicle">
    <caption><h1 style="font-size: 14px; margin-bottom: none; margin-top: none;">DATOS DEL VEHÍCULO:</h1></caption>

    <tr style="text-align:center;">
      <td class="vehicle"><strong>Marca: </strong>{{$policy->vehicle_brand}}</td>
      <td class="vehicle"><strong>Modelo: </strong>{{$policy->vehicle_model}}</td>
      <td class="vehicle"><strong>Año: </strong>{{$policy->vehicle_year}}</td>
      
      
    </tr>
    <tr style="text-align:center;">
      
      <td class="vehicle"><strong>Placa: </strong>{{$policy->vehicle_registration}}</td>
      <td class="vehicle"><strong>Tipo: </strong>{{$policy->vehicle_type}}</td>
      <td class="vehicle"><strong>Color: </strong>{{$policy->vehicle_color}}</td>
      
    </tr>   
  
    <tr style="text-align:center;">
      <td class="vehicle"><strong>Uso: </strong>{{$policy->used_for}}</td>
      <td class="vehicle"><strong>Clase: </strong>{{$policy->class->class}}</td>     
      <td class="vehicle"><strong>Peso: </strong>{{$policy->vehicle_weight}}</td>
     
      
    </tr>
    <tr style="text-align:center;">
       <td class="vehicle" style="width: 50%"><strong>Serial de carroceria: </strong>{{$policy->vehicle_bodywork_serial}}</td>
       <td class="vehicle"><strong>Serial motor:  </strong>{{$policy->vehicle_motor_serial}}</td>
       <td class="vehicle"><strong>N. de certificado: </strong>{{$policy->vehicle_certificate_number}}</td>
      
    </tr>

  </table>

  <table style="width:100%; margin-top: 20px;" class="vp-table">
    <caption><h1 style="font-size: 14px; margin-bottom: none; margin-top: none;">DESCRIPCION DE POLIZA</h1></caption>
    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Daños a cosas: </strong>{{number_format($policy->damage_things * $foreign_reference, 2)}} bs.S</td>
      <td class="rid-bb"><strong>Prima: </strong>{{number_format($policy->premium1 * $foreign_reference, 2)}} Bs.S</td>
    </tr>
    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Daños a personas: </strong>{{number_format($policy->damage_people * $foreign_reference, 2)}} bs.S</td>
      <td class="rid-bb"><strong>Prima: </strong>{{number_format($policy->premium2 * $foreign_reference, 2)}} Bs.S</td>
    </tr>   
    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Asistencia jurídica: </strong>{{number_format($policy->legal_assistance * $foreign_reference, 2)}} bs.S</td>
      <td class="rid-bb"><strong>Prima: </strong>{{number_format($policy->premium3 * $foreign_reference, 2)}} Bs.S</td>
    </tr>
    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Muerte: </strong>{{number_format($policy->death * $foreign_reference, 2)}} bs.S</td>
      <td class="rid-bb" style="width: 50%"><strong>Prima: </strong>{{number_format($policy->premium4 * $foreign_reference, 2)}} Bs.S</td>
    </tr>
    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Invalidez: </strong>{{number_format($policy->disability * $foreign_reference, 2)}} bs.S</td>
      <td class="rid-bb"><strong>Prima: </strong>{{number_format($policy->premium5 * $foreign_reference, 2)}} Bs.S</td>
    </tr>
    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Gastos médicos: </strong>{{number_format($policy->medical_expenses * $foreign_reference, 2)}} bs.S</td>
      <td class="rid-bb"><strong>Prima: </strong>{{number_format($policy->premium6 * $foreign_reference, 2)}} Bs.S</td>
    </tr>
    <tr style="text-align:center;">
      @if($policy->damage_passengers == 0)
      <td class="right-bd rid-bb"><strong>Daño a pasajeros: </strong>No aplica</td>
      <td class="rid-bb"><strong>Prima: </strong>No aplica</td>
      @else
      <td class="right-bd rid-bb"><strong>Daño a pasajeros: </strong>{{number_format($policy->damage_passengers * $foreign_reference, 2)}} Bs.S</td>
      <td class="rid-bb"><strong>Prima: </strong>{{number_format($policy->premium7 * $foreign_reference, 2)}} Bs.S</td>
      @endif
    </tr>
    <tr style="text-align:center;">
      @if($policy->crane == 0)
      <td class="right-bd rid-bb"><strong>Servicio de Grua: </strong>No aplica</td>
      @else
      <td class="right-bd rid-bb"><strong>Servicio de Grua: </strong>{{number_format($policy->crane * $foreign_reference, 2)}} Bs.S</td>
      @endif
    </tr>
    
    <tr style="text-align:center;">
      <td class="right-bd rid-bb">&nbsp;</td>
      <td class="rid-bb"></td>
    </tr>

    <tr style="text-align:center;">
      <td class="right-bd rid-bb"><strong>Total Cobertura: </strong>{{number_format($policy->total_all * $foreign_reference, 2)}} Bs.S</td>
      <td class="rid-bb"><strong>Total Prima: </strong>{{number_format($policy->total_premium * $foreign_reference, 2)}} Bs.S</td>
    </tr>
  </table>
  

  <table style="width:100%; text-align: center; border-bottom: none;" class="sign-table">
    <tr>
      <td style="width: 50%; border-bottom: 1px solid black;">&nbsp;</td>
      <td class="rid-bb" style="width: 50%">&nbsp;</td>
      <td style="width: 50%; border-bottom: 1px solid black;">&nbsp;</td>
    </tr>
    <tr>
      <td class="rid-bb">FIRMA SAFEGROUP</td>
      <td class="rid-bb">&nbsp;</td>
      <td class="rid-bb" >FIRMA {{$policy->client_name_contractor.' '.$policy->client_lastname_contractor}}</td>
    </tr>

  </table>

  <table style="width:100%; margin-top: 50px;" class="vp-table">

    <tr>
      <td ></td>
      <td class="rid-bb">{{$policy->client_name.' '.$policy->client_lastname}}</td>
    </tr>

    <tr>
      <td class="right-bd rid-bb"><strong>Rif/Cédula: </strong>{{$policy->client_ci_contractor}}</td>
      <td class="rid-bb">{{$policy->client_ci}} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{$policy->vehicle_brand}} </td> 
    </tr>

    <tr>
      <td class="rid-bb"><strong>S/C: </strong>{{$policy->vehicle_bodywork_serial}}</td>
      <td class="right-bd rid-bb">{{$policy->vehicle_model}}</td>
    </tr>

    <tr>
      <td class="right-bd rid-bb"><strong>Modelo: </strong>{{$policy->vehicle_model}}</td>
      <td class="rid-bb">{{$policy->class->class}} &nbsp; &nbsp; &nbsp; {{$policy->used_for}} </td>  
    </tr>

    <tr>
      <td class="right-bd rid-bb"><strong>Año: </strong>{{$policy->vehicle_year}}</td>
      <td class="right-bd rid-bb">{{$policy->vehicle_type}}</td>
      
    </tr>

    <tr>
      <td class="right-bd rid-bb"><strong>Color: </strong>{{$policy->vehicle_color}}</td>
      <td class="rid-bb"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; {{$policy->vehicle_bodywork_serial}}</td>
    </tr>

    <tr>
      <td class="right-bd rid-bb"><strong>Tipo: </strong>{{$policy->vehicle_type}}</td>
      <td class="rid-bb"><strong>Vence: </strong>{{$policy->expiring_date}}</td>
    </tr>

  </table>

 
 
</body>
</html>
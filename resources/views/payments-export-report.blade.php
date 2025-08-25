<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Correlativo</title>
</head>
<style>
  *{
    font-family: arial, "sans-serif";

  }
  .table, td{
    border: 1px solid gray;
    border-collapse: collapse;
    text-align: center;

  }

  .t{
    border: 1px solid black;
    background-color: lightgrey;


  }
  .dtable{
    margin-top: 110px;

  }

  #total{
    border: hidden;
    border-top: solid 0px gray;
    border-collapse: collapse;
  }

  .logo{

    height: 80px;
    width: 280px;
  }
  .hea{
    float: left;
  }
  .datos{
    margin-top:15px;
    font-size: 12px;
    float: right;
 }
 .dtd{
    border: hidden;
 }




</style>

<body>

<div class='datos'>
@foreach($policies as $policy)

   @endforeach
   <table style="border:hidden;" cellspacing="0">
    <tr>
        <td class='dtd'><strong>Oficina:</strong> </td>
        <td class='dtd'> {{$policy->user->office->office_address}} </td>
    </tr>
    <tr>
        <td class='dtd'><strong>Vendedor:</strong> </td>
        <td class='dtd'> {{$policy->user->name.' '.$policy->user->lastname}} </td>
    </tr>
        @if($policy->user->mod_id)
    <tr>
        <td class='dtd'><strong>Supervisor:</strong> </td>
        <td class='dtd'> {{$policy->user->moderator->name}} </td>
    </tr>
    @endif
    <tr>
        <td class='dtd'><strong>Forma de Pago:</strong> </td>
        <td class='dtd'>
            @if ($payment->type_payment == 'cash')
            Efectivo
            @elseif ($payment->type_payment == 'transfer')
           Transferencia (Bs)
            @elseif ($payment->type_payment == 'pm')
            Pago Movil (Bs)
            @endif
        </td>
    </tr>

    <tr>
        <td class='dtd'><strong>Moneda:</strong> </td>
        <td class='dtd'>@if($payment->currency == 'dolar') Dolar @else Bolivar @endif </td>
    </tr>
    <?php $hoy = date('d-m-Y'); ?>

    <tr>
        <td class='dtd'><strong>Fecha de Emisión:</strong> </td>
        <td class='dtd'> {{$hoy}} </td>
    </tr>
   </table>


</div>


<div class='dtable'>
    <h3 style="text-align: center">Polizas Reportadas (@if($payment->currency == 'dolar') Dolares @else Bolivares @endif) </h3>
        <?php $count= 0; $suma=0; $sumabs=0; $suma1=0; $resta=0; ?>
				@csrf
				<table width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>

						<th class='t'>N°</th>
						<th class='t'>Asegurado</th>
						<th class='t'>Vehículo</th>
						<th class='t'>Placa</th>
						<th class='t'>Fecha de Emisión</th>
						<th class='t'>Precio </th>
						<th class='t'>Telefono</th>
					</tr>
				</thead>
				<tbody>
					@foreach($policies as $policy)
					    <tr>
                        <td>{{$policy->id}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->vehicle_model}}</td>
						<td>{{$policy->vehicle_registration}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>

                        @if(!$policy->statusu)

						<td>@if($payment->currency == 'dolar')
                            {{number_format($policy->total_premium * $euro / $dolar, 2)}} $
                            @else
                            {{number_format($policy->total_premium * $euro, 2)}} Bs
                            @endif
                        </td>
                        <td>{{$policy->client_phone}}</td>
                        @else

                        <td><strong style="color:red;">{{number_format($policy->total_premium * $euro / $dolar, 2)}} $</strong></td>
                        <?php $resta= $resta + $policy->total_premium; ?>
                        <td><strong style="color:red;">NULA</strong></td>
                        @endif

                        </tr>
          <?php
              $count= $count + 1;
              $comision = $policy->user->profit_percentage / 100;
              $suma1= $suma1 + $policy->total_premium;
              $suma = $suma1 - $resta;
              $total = $suma - ($suma * $comision);
              $sumabs = $sumabs + ($policy->total_premium * $policy->foreign); ?>
					@endforeach
                    <tr>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Total: </strong></td>
                        <td>
                            @if($payment->currency == 'dolar')
                            <strong>{{number_format($payment->total, 2)}} $</strong>
                            @else
                            <strong>{{number_format($payment->total * $euro, 2)}} Bs</strong>
                            @endif
                        </td>
                        <td style="border: hidden; border-top: solid 1px gray; border-left: solid 1px gray; border-collapse: collapse;"></td>
                    </tr>

                    <tr>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Comisión: </strong></td>
                        <td>
                            @if($payment->currency == 'dolar')
                            <strong>{{number_format(($payment->profit_percentage * $payment->total)/100, 2)}} $</strong>
                            @else
                            <strong>{{number_format(($payment->profit_percentage * $payment->total * $euro)/100, 2)}} Bs</strong>
                            @endif
                        </td>
                        <td style="border: hidden; border-left: solid 1px gray; border-collapse: collapse;"></td>
                    </tr>
                    <tr>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Total a Entregar: </strong></td>
                        <td> @if($payment->currency == 'dolar')
                            <strong>{{number_format($payment->total_payment, 2)}} $</strong>
                            @else
                            <strong>{{number_format($payment->total_payment * $euro, 2)}} Bs</strong>
                            @endif
                        <td style="border: hidden; border-left: solid 1px gray; border-collapse: collapse;"></td>
                    </tr>

				</tbody>
			</table>
            <p>Cantidad de Polizas Vendidas: {{$count}}</p>
        </div>
</body>

</html>

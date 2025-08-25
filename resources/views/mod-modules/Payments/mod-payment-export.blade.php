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
<div class="hea">
    <img src="{{asset('images/logo.jpg')}}" alt="logo" class="logo">

</div>
<div class='datos'>

   <table style="border:hidden;" cellspacing="0">
    <tr>
        <td class='dtd'><strong>Oficina:</strong> </td>
        <td class='dtd'> {{$user->office->office_address}} </td>
    </tr>
    <tr>
        <td class='dtd'><strong>Vendedor:</strong> </td>
        <td class='dtd'> {{$user->name.' '.$user->lastname}} </td>
    </tr>
        @if($user->mod_id)
    <tr>
        <td class='dtd'><strong>Supervisor:</strong> </td>
        <td class='dtd'> {{$user->moderator->name}} </td>
    </tr>
    @endif
    <?php $hoy = date('d-m-Y'); ?>

    <tr>
        <td class='dtd'><strong>Fecha de Emisión:</strong> </td>
        <td class='dtd'> {{$hoy}} </td>
    </tr>
   </table>


</div>


<div class='dtable'>
        <?php $count= 0; $suma=0; $sumabs=0; $suma1=0; $resta=0;  ?>
				@csrf
				<table width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
                        <th class='t' width="8%">N°</th>
						<th class='t' width="30%">Tomador</th>
						<th class='t' width="10%">Placa</th>
						<th class='t' width="15%">Fecha de Emisión</th>
                        <th class='t' width="13%">Tasa el Día</th>
						<th class='t' width="10%">Precio €</th>
                        <th class='t' width="14%">Precio Bs TA</th>

					</tr>
				</thead>
				<tbody>
                    @foreach($policies as $policy)
                    <tr>
                        <td>{{ $policy->id }}</td>
                        <td>{{ $policy->client_name . ' ' . $policy->client_lastname }}</td>
                        <td>{{ $policy->vehicle_registration }}</td>
                        <td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y') }}</td>

                        @php
                            $count++;                           
                            $suma += $policy->total_premium;                            
                            $sumabs += $policy->total_premium * $policy->foreign;
                            $comision = bcdiv(bcmul($suma, $user->profit_percentage, 4), 100, 2);
                            $comisionbs = bcdiv(bcmul($sumabs, $user->profit_percentage, 4), 100, 2);
                            $total_neto = bcsub($suma, $comision, 2);
                            $total_netobs = bcsub($sumabs, $comisionbs, 2);
                        @endphp
                         <td>{{ number_format($policy->foreign, 2, ',', '.') }}</td>
                        <td style="text-align: right">{{ number_format($policy->total_premium, 2, ',', '.') }} €</td>
                        <td style="text-align: right">{{ number_format($policy->total_premium * $policy->foreign, 2, ',', '.') }} Bs</td>

                    </tr>


                    @endforeach
                    <tr>
                        <td colspan="4" style="border:none;"></td>
                        <td><strong>Total Vendido:</strong></td>
                        <td style="text-align: right"><strong>{{number_format($suma,2)}} €</strong></td>
                        <td style="text-align: right"><strong>{{number_format($sumabs,2)}} Bs</strong></td>
                    </tr>

                    <tr>
                        <td colspan="4" style="border:none;"></td>
                        <td><strong>Comisión:</strong></td>
                        <td style="text-align: right"><strong>{{number_format($comision,2)}} €</strong></td>
                        <td style="text-align: right"><strong>{{number_format($comisionbs,2)}} Bs</strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border:none;"></td>
                        <td><strong>Total Entrega:</strong></td>
                        <td style="text-align: right"><strong>{{number_format($total_neto,2)}} €</strong></td>
                        <td style="text-align: right"><strong>{{number_format($total_netobs,2)}} Bs</strong></td>
                    </tr>


				</tbody>
			</table>
            <p>Cantidad de Polizas Vendidas: {{$count}}</p>
            </div>
</body>

</html>

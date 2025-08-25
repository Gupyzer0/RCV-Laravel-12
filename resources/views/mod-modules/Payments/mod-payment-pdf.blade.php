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

 .dtdd {
    text-align: right;
    border: hidden;
 }




</style>

<body>
{{-- <div class="hea">
    <img src="{{asset('images/lgoo2.png')}}" alt="logo" class="logo">

</div> --}}




<div class='dtable'>
        <?php $count= 0; $suma=0; $sumabs=0; $suma1=0; $resta=0;  ?>
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
						<th class='t'>Precio €</th>
                        <th class='t'>Precio Bs TA</th>
                        <th class='t'>Tasa el Día</th>
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
						<td>{{number_format($policy->total_premium, 2,',','.')}} €</td>
						<td>{{number_format($policy->total_premium * $policy->foreign, 2,',','.')}} Bs</td>
            <td>{{number_format($policy->foreign, 2)}}</td>
            <td>{{$policy->client_phone}}</td>
            @else
            <td><strong style="color:red;">{{number_format($policy->total_premium, 2,',','.')}} €</strong></td>
            <?php $resta= $resta + $policy->total_premium; ?>
						<td><strong style="color:red;">{{number_format($policy->total_premium * $policy->foreign, 2,',','.')}} Bs</strong></td>
            <td><strong style="color:red;">{{number_format($policy->foreign, 2,',','.')}}</strong></td>
            <td><strong style="color:red;">NULA</strong></td>
            @endif

          </tr>
          <?php
              $count= $count + 1;
              $comision = $policy->user->profit_percentage / 100;
              $suma1= $suma1 + $policy->total_premium;
              $suma = $suma1 - $resta;
              if(!$policy->statusu){
              $sumabs = $sumabs + ($policy->total_premium * $policy->foreign);
              $comisions = Auth::user()->profi_percentaje / 100;
              }
                ?>
					@endforeach
                    <tr>

                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Total: </strong></td>
                        <td><strong>{{number_format($suma, 2,',','.')}} €</strong></td>
                        <td><strong>{{number_format($sumabs, 2,',','.')}} Bs</strong></td>
                        <td><strong></strong></td>

                        <td style="border: hidden; border-top: solid 1px gray; border-left: solid 1px gray; border-collapse: collapse;"></td>


                    </tr>
                    <tr>

                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Comisión Aliado: </strong></td>
                        <td><strong>{{number_format($suma * $comision, 2,',','.')}} €</strong></td>
                        <td><strong>{{number_format(($sumabs * $comision), 2,',','.')}} Bs</strong></td>
                        <td><strong></strong></td>
                        <td style="border: hidden; border-left: solid 1px gray; border-collapse: collapse;"></td>

                    </tr>
                     <tr>

                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Comisión Supervisor: </strong></td>
                        <td><strong>{{number_format($suma * $comisions, 2,',','.')}} €</strong></td>
                        <td><strong>{{number_format(($sumabs * $comisions), 2,',','.')}} Bs</strong></td>
                        <td><strong></strong></td>
                        <td style="border: hidden; border-left: solid 1px gray; border-collapse: collapse;"></td>

                    </tr>
                    <tr>

                        <td id='total'></td>
                        <td id='total'></td>
                        <td id='total'></td>
                        <td><strong>Total a Entregar: </strong></td>
                        <td><strong>{{number_format($suma - ($suma * $comision) - ($suma * $comisions), 2,',','.')}} €</strong></td>
                        <td><strong>{{number_format($sumabs - ($sumabs * $comision) - ($sumabs * $comisions), 2,',','.')}} Bs</strong></td>
                        <td><strong></strong></td>
                        <td style="border: hidden; border-left: solid 1px gray; border-collapse: collapse;"></td>

                    </tr>

				</tbody>
			</table>
            <p>Cantidad de Polizas Vendidas: {{$count}}</p>
            </div>
</body>

</html>


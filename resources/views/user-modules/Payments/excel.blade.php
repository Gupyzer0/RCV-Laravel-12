<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0"
				style="font-size: 12px;">
				<thead>
					<tr>
						<th>Num. Afiliación</th>
						<th>Beneficiario</th>
						<th>Vehículo</th>
						<th>Fecha de Emisión</th>
						<th>Precio</th>
						<th>Telefono</th>
					
						
					</tr>
				</thead>
				<tbody>
					@foreach($policies as $policy)
					<tr>
						
						<td>{{$policy->id}}</td>
						<td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
						<td>{{$policy->vehicle_brand. ' '.$policy->vehicle_model}}</td>
						<td>{{ \Carbon\Carbon::parse($policy->created_at)->format('d-m-Y')}}</td>
						<input type="hidden" value="{{$policy->user->office->office_address}}" name="office">
						<input type="hidden" value="{{$policy->user->profit_percentage}}" name="profit_percentage">
						<td>{{number_format($policy->total_premium)}} $</td>
						<td>{{$policy->client_phone}}</td>
						
						
						
						
					</tr>
					@endforeach
				</tbody>
			</table>
    
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    table, th, td{
        border: 1px solid black;
    }
    h1{
        font-size: 20px;
    }
    table{
        border-collapse:collapse;
    }

</style>
<body>
    <table>
    <caption style="display:block;"><h1 class ='titulo'>POLIZAS VENCIDAS</h1></caption>
        <thead>
          <tr style="text-align:center;">
            <th>N° de Contrato</th>
            <th>Vendedor</th>
            <th>Beneficiario</th>
            <th>Telefono</th>
            <th>Ubicación</th>
            <th>Fecha de vencimiento</th>

         </tr>
        </thead>
        <tbody>
            @foreach($policies as $policy)
            <tr style="text-align:center;">
                @if($policy->idp)
                <td>{{$policy->idp}}</td>
                @else
                <td>{{$policy->id}}</td>
                @endif
                @if(isset($policy->admin_id))
				<td>Administrador</td>
				@else
				<td>{{$policy->user->name.' '.$policy->user->lastname}}</td>
				@endif
                <td>{{$policy->client_name.' '.$policy->client_lastname}}</td>
                <td>{{$policy->client_phone}}</td>
				<td>{{$policy->estado->estado.', '.$policy->municipio->municipio}}</td>
                <td>{{$policy->expiring_date}}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</body>
</html>

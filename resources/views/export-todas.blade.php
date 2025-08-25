<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lista de Usuarios</title>
</head>
<style>

            *{
    font-family: arial, "sans-serif";
    font-weight: normal;
    font-size: 11px;


  }
    .logo{
        width:    9cm;
        height:   2.6cm;
        opacity: 0.8;
    }

    .tab, th, td{
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
    }
    .tab th, h3{
        font-weight: bold;
        text-transform: uppercase;
    }
</style>


<body>


    <h3 style="text-align: center; margin-top:2px; font-size: 15px;">Cartera de Clientes</h3>



    <table width="100%" border="0" align="center" style="margin-top: 14px; border:none;" cellspacing="0" bordercolor="#000000" class="tab">
        <thead>
            <tr>
                <th>Nº</th>
                <th>Nombre y Apellido</th>
                <th>Cedula</th>
                <th>Telfono</th>
                <th>Vehiculo</th>
                <th>Precio</th>
                <th>Estado</th>

            </tr>
        </thead>

        <tbody>
            @php $con = 0;  @endphp
            @foreach($policies as $policy)
            <tr>
                @php  $con = $con + 1;  @endphp
                <td>{{$con}}</td>
                <td>{{ $policy->client_name.' '.$policy->client_lastname }}</td>
                <td>{{ $policy->client_lastname }}</td>
                <td>{{ $policy->client_ci }}</td>
                <td>{{ $policy->vehicle_brand.' '.$policy->vehicle_model }}</td>
                <td>{{ $policy->total_premium }} $</td>
                <td>{{ $policy->estado->estado }}</td>
            </tr>


            @endforeach
        </tbody>


    </table>
</body>
</html>

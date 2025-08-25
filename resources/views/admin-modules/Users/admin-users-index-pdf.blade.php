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
    <div class="logo"> <img src="{{asset('images/logo.jpg')}}"  height="100%" width="100%" /></div>

    <h3 style="text-align: center; margin-top:2px; font-size: 15px;"> Lista de Usuarios</h3>



    <table width="100%" border="0" align="center" style="margin-top: 14px; border:none;" cellspacing="0" bordercolor="#000000" class="tab">
        <thead>
            <tr>
                <th>Nº</th>
                <th>Nombre y Apellido</th>
                <th>Cedula</th>
                <th>Telfono</th>
                <th>Oficina</th>
                <th>Ubicación</th>
                <th>Ingreso</th>

            </tr>
        </thead>

        <tbody>
            @php
            $con = 0;
            @endphp

            @foreach ($users as $user )

            <tr>

                @php
                    $con = $con + 1;
                @endphp
                <td>{{$con}}</td>
				<td>{{$user->name.' '.$user->lastname}}</td>
				<td>{{$user->ci}}</td>
				<td>{{$user->phone_number}}</td>
				<td>{{$user->office->office_address}}</td>
                <td>{{$user->office->estado->estado. ', ' .$user->office->municipio->municipio. ', ' .$user->office->parroquia->parroquia}}</td>
				<td>{{\Carbon\Carbon::parse($user->created_at)->format('d/m/Y')}}</td>
            </tr>


            @endforeach
        </tbody>


    </table>
</body>
</html>

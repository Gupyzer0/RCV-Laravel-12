<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Planes PDF</title>
</head>
<style>

    .vp-table td, .vp-table{
        border:1px solid black;
        border-collapse: collapse;
    }
    .vp-table th{
        border:1px solid black;
        border-collapse: collapse;
        background: rgb(190, 0, 0);
        color: rgb(255, 255, 255);
    }
</style>
<body>
    <div class="logo">
        <img src="{{ asset('images/logobp.jpg') }}" width="200" />
    </div>
    <table>
       
        <caption style="display:block;margin-top:25px;"><h1 style="font-size: 18px; margin-bottom: none;">DESCRIPCIÓN DEL PLAN</h1></caption>
    </table>

    <table style="width:100%; margin-top: 20px;" class="vp-table">
        <thead>
            <tr>
                <th colspan="5">{{ $price->description }}</th>
            </tr>
            <tr>
                <th style="background: rgb(202, 63, 63);" rowspan="2">Descripción</th>
                <th style="background: rgb(202, 63, 63);" colspan="2">Cobertura</th>
                <th style="background: rgb(202, 63, 63);" colspan="2">Prima</th>              
            </tr>
            <tr>                
                <th style="background: rgb(190, 83, 83);">Divisas</th>
                <th style="background: rgb(190, 83, 83);">Bolivares</th>                
                <th style="background: rgb(190, 83, 83);">Divisas</th>
                <th style="background: rgb(190, 83, 83);">Bolivares</th>
            </tr>
        </thead>

        <!-- Primera fila (campo) -->
        @if(!empty($price->campo))
            <tr style="text-align:center;">
                <td style="text-align:left;"><strong>{{ mb_strtoupper(mb_substr($price->campo, 0, 1, 'UTF-8')) . mb_strtolower(mb_substr($price->campo, 1, null, 'UTF-8'), 'UTF-8') }}</strong></td>
                <td>{{ number_format($price->campoc * $foreign_reference, 2, ",", ".") }} $ </td>
                <td>{{ number_format($price->campoc * $euro, 2, ",", ".") }} </td>
                <td>{{ number_format($price->campop * $foreign_reference, 2) }} $ </td>
                <td>{{ number_format($price->campop * $euro, 2) }} </td>
            </tr>
        @endif
        
       <!-- Luego, campos del 1 al 7 -->
        @for ($i = 1; $i <= 7; $i++)
        @php
            $currentCampo = 'campo' . $i;
        @endphp
               
        @if(!empty($price->{$currentCampo}))
            <tr style="text-align:center;">
                <td style="text-align:left;"><strong>{{ mb_strtoupper(mb_substr($price->{$currentCampo}, 0, 1, 'UTF-8')) . mb_strtolower(mb_substr($price->{$currentCampo}, 1, null, 'UTF-8'), 'UTF-8') }} </strong></td>
                <td>{{ number_format($price->{'campoc'.$i} * $foreign_reference, 2, ",", ".") }} $ </td>
                <td>{{ number_format($price->{'campoc'.$i} * $euro, 2, ",", ".") }} </td>
                <td>{{ number_format($price->{'campop'.$i} * $foreign_reference, 2) }} $ </td>
                <td>{{ number_format($price->{'campop'.$i} * $euro, 2) }} </td>
            </tr>
        @endif
        @endfor
       
        <tr style="text-align:center;">
            <td  colspan="3"><strong>Total </strong></td>
    
            
            <td>{{number_format($price->total_premium * $foreign_reference, 2)}} $</td>
            <td>{{number_format($price->total_premium * $euro, 2)}} </td>
        </tr>


      </table>

</body>
</html>

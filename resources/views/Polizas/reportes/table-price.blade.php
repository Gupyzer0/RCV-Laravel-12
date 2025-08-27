<table width="100%" border="0" align="center" cellspacing="0" bordercolor="#000000" class="borde">
@php
    $pdolar = $euro/$dolar;
@endphp
    <tr>
        <th colspan="3" class="lineat">{{$poliza->price->description}} </th>
      </tr>
      <tr style="font: italic;" style="font-weight: bold,;" align="center" >

        <td><strong>Descripción:</strong></td>
        <td><strong>Suma Asegurada:</strong></td>
        <td><strong>Prima:</strong></td>
      </tr>

      <tr style="font: italic;">
        <td>{{$poliza->price->campo}} </td>
        <td align="center">{{number_format($poliza->price->campoc * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop * $pdolar, 2)}} $</td>
      </tr>

      <tr style="font: italic;">
        <td>{{$poliza->price->campo1}} </td>
        <td align="center">{{number_format($poliza->price->campoc1 * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop1 * $pdolar, 2)}} $</td>
      </tr>

      @if($poliza->price->campo2)
      <tr style="font: italic;">
        <td>{{$poliza->price->campo2}} </td>
        <td align="center">{{number_format($poliza->price->campoc2 * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop2 * $pdolar, 2)}} $</td>
      </tr>
      @else
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      @endif

      @if($poliza->price->campo3)
      <tr style="font: italic;">
        <td>{{$poliza->price->campo3}} </td>
        <td align="center">{{number_format($poliza->price->campoc3 * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop3 * $pdolar, 2)}} $</td>
      </tr>
      @else
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      @endif
      @if($poliza->price->campo4)
      <tr style="font: italic;">
        <td>{{$poliza->price->campo4}}</td>
        <td align="center">{{number_format($poliza->price->campoc4 * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop4 * $pdolar, 2)}} $</td>
      </tr>
      @else
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      @endif

      @if($poliza->price->campo5)
      <tr style="font: italic;">
        <td>{{$poliza->price->campo5}} </td>
        <td align="center">{{number_format($poliza->price->campoc5 * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop5 * $pdolar, 2)}} $</td>
      </tr>
      @else
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      @endif
      @if($poliza->price->campo6)
      <tr style="font: italic;">
        <td>{{$poliza->price->campo6}} </td>
        <td align="center">{{number_format($poliza->price->campoc6 * $pdolar, 2)}} $</td>
        <td align="center">{{number_format($poliza->price->campop6 * $pdolar, 2)}} $</td>
      </tr>
      @else
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      @endif
      @if($poliza->trailer)
      <tr>
        <td>Extension trailer</td>
        <td style="font-size: 9px">Queda entendido y convenido que se excluye el traslado de materiales de construcción y transporte de químicos y explosivos</td>
        <td>20%</td>
      </tr>
      @endif
      @if($poliza->price_id == 36 || $poliza->price_id == 97)
      @php
          $add = (5 * $euro)/$dolar;
          $vehicle_weight = preg_replace('/\D/', '', $poliza->vehicle_weight);
          $tone = ceil($vehicle_weight / 1000)  - 12;
          $ttone = $tone * $add;
      @endphp
      <tr>
        <td>Tonelada Adicional </td>
        <td>{{$tone }}</td>
        <td>{{number_format($ttone, 2)}}$</td>
      </tr>
      @endif

      <tr style="font: italic;">
        <td align="center"><strong>Total: </strong></td>
        <td align="center">&nbsp;</td>
        <td align="center">{{number_format($poliza->total_premium * $pdolar, 2)}} $</td>
      </tr>


</table>

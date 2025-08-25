<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Correlativo</title>
</head>
<style>
  * {
    font-family: arial, "sans-serif";
  }
  .table, td {
    border: 1px solid gray;
    border-collapse: collapse;
    text-align: center;
  }
  .t {
    border: 1px solid black;
    background-color: lightgrey;
  }
  .dtable {
    margin-top: 110px;
  }
  #total {
    border: hidden;
    border-top: solid 0px gray;
    border-collapse: collapse;
  }
  .logo {
    height: 80px;
    width: 280px;
  }
  .hea {
    float: left;
  }
  .datos {
    margin-top: 15px;
    font-size: 12px;
    float: right;
  }
  .dtd {
    border: hidden;
  }
</style>

<body>

<div class='datos'>
  <table style="border:hidden;" cellspacing="0">    
    <tr>
      <td class='dtd'><strong>Supervisor:</strong></td>
      <td class='dtd'>{{ $supervisorName }}</td>
    </tr>
    <?php $hoy = date('d-m-Y'); ?>
    <tr>
      <td class='dtd'><strong>Fecha de Emisión:</strong></td>
      <td class='dtd'>{{ $hoy }}</td>
    </tr>
  </table>
</div>

<div class='dtable'>
  <h3 style="text-align: center">Correlativo Polizas No Pagadas</h3>

  <?php
    $totalGeneral = 0;
    $totalBsGeneral = 0;
    $comisionGeneralBs = 0;
  ?>

  @foreach ($groupedPolicies as $userId => $policies)
    <h4>Vendedor: {{ $policies->first()->user->name }} {{ $policies->first()->user->lastname }}</h4>
    <table width="100%" cellspacing="0" style="font-size: 12px;">
      <thead>
        <tr>
          <th class='t'>N° Póliza</th>
          <th class='t'>Tomador</th>
          <th class='t'>Vehículo</th>
          <th class='t'>Placa</th>
          <th class='t'>Fecha de Emisión</th>
          <th class='t'>Precio €</th>
          <th class='t'>Precio Bs</th>
          <th class='t'>Tasa</th>
          <th class='t'>Teléfono</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $totalVendedor = 0;
          $totalBsVendedor = 0;
          $comisionBsVendedor = 0;
        ?>
        @foreach ($policies as $policy)
          <tr>
            <td>{{ $policy->id }}</td>
            <td>{{ $policy->client_name }} {{ $policy->client_lastname }}</td>
            <td>{{ $policy->vehicle_brand }} {{ $policy->vehicle_model }}</td>
            <td>{{ $policy->vehicle_registration }}</td>
            <td>{{ $policy->created_at->format('d/m/Y') }}</td>
            <td>{{ number_format($policy->total_premium, 2) }}</td>
            <td>{{ number_format($policy->total_premium * $policy->foreign, 2) }}</td>
            <td>{{ number_format($policy->foreign, 2) }}</td>
            <td>{{ $policy->client_phone }}</td>
          </tr>
          <?php
            $totalVendedor += $policy->total_premium;
            $totalBsVendedor += $policy->total_premium * $policy->foreign;
          ?>
        @endforeach
        <?php
          $comisionBsVendedor = $totalBsVendedor * ($policies->first()->user->profit_percentage / 100);
          $totalGeneral += $totalVendedor;
          $totalBsGeneral += $totalBsVendedor;
          $comisionGeneralBs += $comisionBsVendedor;
        ?>
        <tr class="total-row">
          <td colspan="5" style="text-align: right;"><strong>Total:</strong></td>
          <td><strong>{{ number_format($totalVendedor, 2) }}</strong></td>
          <td><strong>{{ number_format($totalBsVendedor, 2) }}</strong></td>
          <td colspan="2"></td>
        </tr>
        <tr class="total-row">
          <td colspan="5" style="text-align: right;"><strong>Comisión Bs:</strong></td>
          <td></td>
          <td><strong>{{ number_format($comisionBsVendedor, 2) }}</strong></td>
          <td colspan="2"></td>
        </tr>
      </tbody>
    </table>
    <br><br>
  @endforeach

  <!-- Totales generales -->
  <table width="100%" cellspacing="0" style="font-size: 12px;">
    <tr>
      <td colspan="5" style="text-align: right;"><strong>Total General €:</strong></td>
      <td><strong>{{ number_format($totalGeneral, 2) }}</strong></td>
      <td><strong>{{ number_format($totalBsGeneral, 2) }}</strong></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="5" style="text-align: right;"><strong>Comisión General Bs:</strong></td>
      <td></td>
      <td><strong>{{ number_format($comisionGeneralBs, 2) }}</strong></td>
      <td colspan="2"></td>
    </tr>
    <tr>
      <td colspan="5" style="text-align: right;"><strong>Total a Recibir:</strong></td>
      <td></td>
      <td><strong>{{ number_format($totalBsGeneral - $comisionGeneralBs, 2) }}</strong></td>
      <td colspan="2"></td>
    </tr>
  </table>
</div>

</body>
</html>
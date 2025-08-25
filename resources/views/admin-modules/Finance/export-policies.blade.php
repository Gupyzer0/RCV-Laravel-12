<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h3 { margin: 0; }
    </style>
    <title>Polizas - Reportadas</title>
</head>
<body>

    <div class="header">
        <h3>Reporte de Pólizas - {{ $adminName }}</h3>
        <p>Rango de fecha: {{ $startDate }} al {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Poliza</th>
                <th>Vendedor</th>
                <th>Precio (€)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($policies as $policy)
            <tr>
                <td>{{ $policy->id }}</td>
                <td>{{ $policy->user->name }}</td>
                <td>{{ number_format($policy->total_premium, 2) }} €</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="2"><strong>Total </strong></td>
                <td><strong>{{number_format($totalPremiumSum, 2)}} €</strong></td>
            </tr>
        </tbody>
    </table>

</body>
</html>

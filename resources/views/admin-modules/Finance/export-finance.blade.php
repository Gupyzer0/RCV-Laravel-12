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
    <title>Resumen de Pólizas</title>
</head>
<body>

    <div class="header">
        <h3>Resumen de Pólizas</h3>
        <p>Rango de fecha: {{ $startDate }} al {{ $endDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Admin</th>
                <th>Cantidad de Pólizas</th>
                <th>Total Vendido €</th>
                <th>Total Vendido Bs</th>
                <th>16.5%</th>
                <th>3.5%</th>
                <th>Total (20%)</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($sumAndCountByType))
                @foreach($sumAndCountByType as $item)
                    <tr>
                        <td>{{ $item->type_name }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ number_format($item->total, 2) }} €</td>
                        <td>{{ number_format($item->total_foreign, 2) }} Bs</td>
                        <td>{{ number_format(($item->total * 16.5) / 100, 2) }} € / {{ number_format(($item->total_foreign * 16.5) / 100, 2) }} Bs</td>
                        <td>{{ number_format(($item->total * 3.5) / 100, 2) }} € / {{ number_format(($item->total_foreign * 3.5) / 100, 2) }} Bs</td>
                        <td>{{ number_format(($item->total * 20) / 100, 2) }} € / {{ number_format(($item->total_foreign * 20) / 100, 2) }} Bs</td>
                    </tr>
                @endforeach
            @endif
            <tr>
                <td colspan="2"><strong>Total </strong></td>
                <td><strong>{{ number_format($grandTotalPremium, 2) }} €</strong></td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <th>Total General</th>
                <td><strong>{{ number_format($totalPolicies, 0, ',', '.') }}</strong></td>
                <th>{{ number_format($grandTotalPremium, 2) }} €</th>
                <th></th>
                <th>{{ number_format(($grandTotalPremium * 20) / 100, 2) }} €</th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>

</body>
</html>

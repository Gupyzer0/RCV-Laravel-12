<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Póliza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            max-width: 400px;
            width: 90%;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h2 {
            color: #8B0000; /* Rojo oscuro */
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .card {
            border: none;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .card-header {
            background-color: #8B0000; /* Rojo oscuro */
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            padding: 12px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .card-body {
            padding: 15px;
        }
        .card-body p {
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: #555;
            text-align: left;
        }
        .highlight {
            color: #8B0000; /* Rojo oscuro */
            font-weight: bold;
        }
        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 0.8rem;
        }
        .button-group {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-download {
            background-color: #8B0000; /* Rojo oscuro */
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 1rem;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-download:hover {
            background-color: #ff4747; /* Rojo más oscuro */
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detalles del Vehículo</h2>

        <!-- Datos del Vehículo -->
        <div class="card">
            <div class="card-header">Información del Vehículo</div>
            <div class="card-body">
                <p><span class="highlight">Vence el:</span> {{ \Carbon\Carbon::parse($policy->expiring_date)->format('d/m/Y') }}</p>
                <p><span class="highlight">Marca:</span> {{ $policy->vehicle_brand }}</p>
                <p><span class="highlight">Modelo:</span> {{ $policy->vehicle_model }}</p>
                <p><span class="highlight">Color:</span> {{ $policy->vehicle_color }}</p>
                <p><span class="highlight">Placa:</span> {{ $policy->vehicle_registration }}</p>
            </div>
        </div>

        <!-- Datos del Personal -->
        <div class="card">
            <div class="card-header">Información del Personal</div>
            <div class="card-body">
                <p><span class="highlight">Nombre y Apellido:</span> {{ $policy->client_name.' '.$policy->client_lastname }}</p>
                <p><span class="highlight">Plan:</span> {{ $policy->price->description }}</p>
            </div>
        </div>

        <!-- Botones de descarga -->
        <div class="button-group">
            <a href="{{ route('policy.download', $policy->id) }}" class="btn-download">
                Descargar Póliza
            </a>
            <a href="{{ route('policy.download.conditions', $policy->id) }}" class="btn-download">
                Condicionado
            </a>
        </div>

        <!-- Texto de pie de página -->
        <div class="footer-text">
            &copy; 2023 Líder de Seguros para Vehículos. Todos los derechos reservados.
        </div>
    </div>
</body>
</html>

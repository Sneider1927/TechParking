<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura {{ $registro->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: white;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #28a745;
            font-size: 32px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .factura-info {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .info-col {
            display: table-cell;
            width: 50%;
            padding: 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .info-col h4 {
            color: #28a745;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .info-col p {
            margin: 5px 0;
            font-size: 13px;
        }
        .info-col strong {
            color: #333;
        }
        .details {
            width: 100%;
            margin: 30px 0;
        }
        .details h3 {
            color: #28a745;
            font-size: 16px;
            margin-bottom: 15px;
            text-transform: uppercase;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #28a745;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 13px;
        }
        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 13px;
        }
        table tr:last-child td {
            border-bottom: none;
        }
        .total-section {
            background-color: #f8f9fa;
            margin-top: 20px;
            padding: 20px;
            border: 2px solid #28a745;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 12px;
        }
        .total-label {
            display: table-cell;
            width: 70%;
            font-size: 14px;
            color: #333;
        }
        .total-value {
            display: table-cell;
            width: 30%;
            text-align: right;
            font-size: 14px;
            color: #333;
        }
        .total-row.grand-total .total-label {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .total-row.grand-total .total-value {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Tech Parking</h1>
            <p>Factura de Servicio de Estacionamiento</p>
        </div>

        <!-- Info de Factura y Vehículo -->
        <div class="factura-info">
            <div class="info-col">
                <h4>Información de la Factura</h4>
                <p><strong>Factura #:</strong> {{ $registro->id }}</p>
                <p><strong>Fecha Emisión:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            </div>
            <div class="info-col">
                <h4>Información del Vehículo</h4>
                @php
                    $tipoVehiculo = $registro->vehiculo->tipo;
                    $tarifaVehiculo = config('tarifas')[$tipoVehiculo] ?? 3000;
                @endphp
                <p><strong>Placa:</strong> {{ $registro->vehiculo->placa }}</p>
                <p><strong>Tipo:</strong> {{ ucfirst($tipoVehiculo) }}</p>
                <p><strong>Tarifa:</strong> ${{ number_format($tarifaVehiculo, 0, ',', '.') }} por hora</p>
            </div>
        </div>

        <!-- Detalles de Horarios -->
        <div class="details">
            <h3>Detalles de Estancia</h3>
            <table>
                <tr>
                    <th style="width: 50%">Concepto</th>
                    <th style="width: 50%; text-align: right;">Valor</th>
                </tr>
                <tr>
                    <td><strong>Hora de Entrada</strong></td>
                    <td class="text-right">{{ $registro->hora_entrada->format('d/m/Y H:i:s') }}</td>
                </tr>
                <tr>
                    <td><strong>Hora de Salida</strong></td>
                    <td class="text-right">{{ $registro->hora_salida->format('d/m/Y H:i:s') }}</td>
                </tr>
                @php
                    $horas = max(1, ceil($registro->hora_entrada->floatDiffInHours($registro->hora_salida)));
                    $tarifa = config('tarifas')[$registro->vehiculo->tipo] ?? 3000;
                    $valorTotal = $registro->valor_total > 0 ? $registro->valor_total : ($horas * $tarifa);
                @endphp
                <tr>
                    <td><strong>Horas de Estancia</strong></td>
                    <td class="text-right">{{ $horas }} horas</td>
                </tr>
            </table>
        </div>

        <!-- Cálculo de Monto -->
        <div class="total-section">
            <div class="total-row">
                <div class="total-label">Horas:</div>
                <div class="total-value">{{ $horas }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Tarifa por hora:</div>
                <div class="total-value">${{ number_format($tarifa, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Subtotal:</div>
                <div class="total-value">${{ number_format($valorTotal, 0, ',', '.') }}</div>
            </div>
            <div class="total-row">
                <div class="total-label">Descuentos:</div>
                <div class="total-value">$0</div>
            </div>
            <div class="total-row grand-total">
                <div class="total-label">TOTAL A PAGAR:</div>
                <div class="total-value">${{ number_format($valorTotal, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Nota:</strong> El pago mínimo es de 1 hora aunque haya permanecido menos tiempo.</p>
            <p>Factura generada el {{ now()->format('d/m/Y H:i:s') }}</p>
            <p style="margin-top: 20px; border-top: 1px solid #ddd; padding-top: 20px;">
                Gracias por utilizar nuestro parqueadero
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Haberes</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0 15px;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .titulo {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .periodo {
            font-size: 14px;
            text-align: center;
            margin-bottom: 8px;
        }
        .info {
            margin-bottom: 14px;
        }
        .info strong {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid #888;
            padding: 6px;
            font-size: 12px;
        }
        th {
            background: #f1f1f1;
            text-align: left;
        }
        .right {
            text-align: right;
        }
        .total {
            background: #f7f7f7;
            font-weight: bold;
        }
        .liquido {
            font-size: 16px;
            background: #e6ffe6;
            font-weight: bold;
            border: 2px solid #333;
            text-align: right;
            padding: 10px;
        }
        .firma {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="titulo">Recibo de Haberes</div>
        <div class="periodo">
            Periodo: <strong>{{ $data['periodo'] }}</strong>
            &nbsp;&nbsp; | &nbsp;&nbsp;
            N° Liquidación: <strong>{{ $data['numero'] }}</strong>
        </div>
    </div>

    <div class="info">
        <strong>Legajo:</strong> {{ $data['empleado']['legajo'] }} &nbsp;&nbsp;
        <strong>Título:</strong> {{ ucfirst($data['empleado']['titulo']) }} <br>
        <strong>Fecha de ingreso:</strong> {{ \Carbon\Carbon::parse($data['empleado']['fecha_ingreso'])->format('d/m/Y') }} <br>
        <strong>Antigüedad previa:</strong> {{ $data['empleado']['pre_antiguedad'] }} años
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="3">Conceptos Remunerativos</th>
            </tr>
            <tr>
                <th style="width:70px;">Código</th>
                <th>Descripción</th>
                <th style="width:120px;" class="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['remunerativos'] as $item)
                <tr>
                    <td>{{ $item['concepto']['codigo'] }}</td>
                    <td>{{ $item['concepto']['descripcion'] }}</td>
                    <td class="right">${{ number_format($item['importe'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">Total Remunerativos</td>
                <td class="right">${{ number_format($data['total_remunerativos'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th colspan="3">Descuentos / No Remunerativos</th>
            </tr>
            <tr>
                <th style="width:70px;">Código</th>
                <th>Descripción</th>
                <th style="width:120px;" class="right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['no_remunerativos'] as $item)
                <tr>
                    <td>{{ $item['concepto']['codigo'] }}</td>
                    <td>{{ $item['concepto']['descripcion'] }}</td>
                    <td class="right">${{ number_format($item['importe'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total">
                <td colspan="2">Total Descuentos / No Remunerativos</td>
                <td class="right">${{ number_format($data['total_no_remunerativos'], 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="liquido">
        Neto a cobrar: ${{ number_format($data['total_liquido'], 2, ',', '.') }}
    </div>

</body>
</html>

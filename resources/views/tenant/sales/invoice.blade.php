<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $sale->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .container {
            width: 100%;
        }

        h1 {
            font-size: 20px;
            margin: 0;
        }

        h2 {
            font-size: 14px;
            margin-bottom: 6px;
        }

        .muted {
            color: #666;
        }

        .row {
            width: 100%;
            margin-bottom: 20px;
            clear: both;
        }

        .col {
            float: left;
            width: 48%;
        }

        .right {
            text-align: right;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border-bottom: 1px solid #ddd;
            padding: 8px 6px;
        }

        th {
            background: #f2f2f2;
            text-align: left;
            font-weight: bold;
        }

        td.right {
            text-align: right;
        }

        .totals {
            margin-top: 20px;
            width: 100%;
        }

        .totals td {
            padding: 6px;
        }

        .totals .label {
            text-align: right;
            color: #555;
        }

        .totals .value {
            text-align: right;
            font-weight: bold;
        }

        .total-final {
            font-size: 14px;
            border-top: 2px solid #000;
        }

        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #777;
            text-align: center;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>

<body>
<div class="container">

    {{-- CABECERA --}}
    <div class="row clearfix">
        <div class="col">
            <h1>FACTURA</h1>
            <div class="muted">
                Nº {{ $sale->id }}<br>
                Fecha: {{ $sale->closed_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="col right">
            {{-- LOGO OPCIONAL --}}
            {{-- <img src="{{ public_path('logo.png') }}" height="40"> --}}
            <strong>{{ $tenant->name }}</strong><br>
            {{ $tenant->address ?? '' }}<br>
            CIF: {{ $tenant->cif ?? '—' }}
        </div>
    </div>

    {{-- CLIENTE --}}
    <div class="box">
        <h2>Cliente</h2>

        @if ($sale->customer)
            <strong>{{ $sale->customer->nombre }}</strong><br>
            {{ $sale->customer->direccion ?? '' }}
        @else
            Cliente genérico
        @endif
    </div>

    {{-- LINEAS --}}
    <table>
        <thead>
        <tr>
            <th>Concepto</th>
            <th class="right">Cantidad</th>
            <th class="right">Precio</th>
            <th class="right">IVA</th>
            <th class="right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($sale->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td class="right">{{ $item->quantity }}</td>
                <td class="right">{{ number_format($item->unit_price, 2) }} €</td>
                <td class="right">{{ number_format($item->tax_amount, 2) }} €</td>
                <td class="right">{{ number_format($item->subtotal, 2) }} €</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- TOTALES --}}
    <table class="totals">
        <tr>
            <td class="label">Subtotal</td>
            <td class="value">{{ number_format($sale->subtotal, 2) }} €</td>
        </tr>
        <tr>
            <td class="label">IVA</td>
            <td class="value">{{ number_format($sale->tax_total, 2) }} €</td>
        </tr>
        <tr class="total-final">
            <td class="label">TOTAL</td>
            <td class="value">{{ number_format($sale->total, 2) }} €</td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Documento generado automáticamente · {{ $tenant->name }}
    </div>

</div>
</body>
</html>

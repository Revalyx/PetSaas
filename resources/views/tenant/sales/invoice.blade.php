<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura #{{ $sale->id }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0f172a;
            background: #ffffff;
        }

        .container {
            width: 100%;
        }

        /* ====== ARIS COLORS ====== */
        .aris-primary {
            color: #14b8a6;
        }

        .aris-muted {
            color: #64748b;
        }

        .aris-border {
            border-color: #e2e8f0;
        }

        /* ====== HEADER ====== */
        h1 {
            font-size: 22px;
            margin: 0;
            font-weight: bold;
        }

        h2 {
            font-size: 14px;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .row {
            width: 100%;
            margin-bottom: 24px;
            clear: both;
        }

        .col {
            float: left;
            width: 48%;
        }

        .right {
            text-align: right;
        }

        /* ====== BOX ====== */
        .box {
            border: 1px solid #e2e8f0;
            padding: 12px;
            margin-bottom: 20px;
            background: #f8fafc;
        }

        /* ====== TABLE ====== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background: #f1f5f9;
            border-bottom: 2px solid #e2e8f0;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #334155;
        }

        td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px 6px;
            font-size: 12px;
        }

        td.right {
            text-align: right;
        }

        /* ====== TOTALS ====== */
        .totals {
            margin-top: 24px;
            width: 100%;
        }

        .totals td {
            padding: 6px;
        }

        .totals .label {
            text-align: right;
            color: #475569;
        }

        .totals .value {
            text-align: right;
            font-weight: bold;
        }

        .total-final td {
            font-size: 14px;
            border-top: 2px solid #0f172a;
            padding-top: 10px;
        }

        /* ====== FOOTER ====== */
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #64748b;
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
            <h1 class="aris-primary">FACTURA</h1>
            <div class="aris-muted">
                Nº {{ $sale->id }}<br>
                Fecha: {{ $sale->closed_at->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="col right">
            <strong>{{ $tenant->name }}</strong><br>
            <span class="aris-muted">
                {{ $tenant->address ?? '' }}<br>
                CIF: {{ $tenant->cif ?? '—' }}
            </span>
        </div>
    </div>

    {{-- CLIENTE --}}
    <div class="box">
        <h2>Cliente</h2>

        @if ($sale->customer)
            <strong>{{ $sale->customer->nombre }}</strong><br>
            <span class="aris-muted">
                {{ $sale->customer->direccion ?? '' }}
            </span>
        @else
            <span class="aris-muted">Cliente genérico</span>
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

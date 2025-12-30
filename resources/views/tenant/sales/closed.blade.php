@extends('layouts.tenant')

@section('title', 'Venta cerrada')

@section('content')

<style>
:root {
    --aris-bg-light: #f8fafc;
    --aris-bg-dark: #020617;
    --aris-bg-dark-2: #0f172a;

    --aris-primary: #14b8a6;
    --aris-primary-hover: #0d9488;

    --aris-border: #1e293b;
    --aris-text-soft: #94a3b8;
}
</style>

<div class="max-w-xl mx-auto py-12 px-4">

    <div class="rounded-2xl p-8
                bg-gradient-to-b from-white to-slate-50
                dark:from-[var(--aris-bg-dark-2)] dark:to-[var(--aris-bg-dark)]
                border border-slate-200 dark:border-[var(--aris-border)]
                shadow-2xl text-center space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">
                Venta #{{ $sale->id }}
            </h1>

            <p class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                Venta cerrada
                @if ($sale->closed_at)
                    · {{ $sale->closed_at->format('d/m/Y H:i') }}
                @endif
            </p>
        </div>

        {{-- CLIENTE --}}
        <div>
            @if ($sale->customer)
                <p class="text-xs uppercase tracking-wide text-slate-400 dark:text-[var(--aris-text-soft)]">
                    Cliente
                </p>

                <p class="font-semibold text-[var(--aris-primary)]">
                    {{ $sale->customer->nombre }}
                    {{ $sale->customer->apellidos ?? '' }}
                    @if (!empty($sale->customer->telefono))
                        · {{ $sale->customer->telefono }}
                    @endif
                </p>
            @else
                <p class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                    Cliente genérico
                </p>
            @endif
        </div>

        {{-- LÍNEAS --}}
        <div class="text-left rounded-xl
                    bg-white dark:bg-[var(--aris-bg-dark)]
                    border border-slate-200 dark:border-[var(--aris-border)]
                    p-4">
            <ul class="divide-y divide-slate-200 dark:divide-[var(--aris-border)]">
                @foreach ($sale->items as $item)
                    <li class="py-2 flex justify-between text-sm">
                        <span class="text-slate-700 dark:text-slate-200">
                            {{ $item->name }} x{{ $item->quantity }}
                        </span>
                        <span class="font-medium">
                            {{ number_format($item->subtotal, 2) }} €
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- TOTALES --}}
        <div class="rounded-xl
                    bg-slate-100 dark:bg-[var(--aris-bg-dark-2)]
                    border border-slate-200 dark:border-[var(--aris-border)]
                    p-4 text-right space-y-1 text-sm">
            <div>
                Subtotal:
                <span class="font-medium">
                    {{ number_format($sale->subtotal, 2) }} €
                </span>
            </div>

            <div>
                IVA:
                <span class="font-medium">
                    {{ number_format($sale->tax_total, 2) }} €
                </span>
            </div>

            <div class="text-lg font-extrabold text-slate-800 dark:text-white">
                Total: {{ number_format($sale->total, 2) }} €
            </div>
        </div>

        {{-- ACCIONES --}}
        <div class="flex gap-3 pt-2">

            <a href="{{ route('tenant.sales.index') }}"
               class="flex-1 py-2.5 rounded-xl
                      bg-slate-200 hover:bg-slate-300
                      dark:bg-[var(--aris-bg-dark-2)] dark:hover:bg-[var(--aris-bg-dark)]
                      text-slate-800 dark:text-white font-semibold transition">
                Volver a ventas
            </a>

            <a href="{{ route('tenant.sales.invoice', $sale->id) }}"
               target="_blank"
               class="flex-1 py-2.5 rounded-xl
                      bg-[var(--aris-primary)]
                      hover:bg-[var(--aris-primary-hover)]
                      text-slate-900 font-extrabold shadow transition">
                Ticket / Factura
            </a>

        </div>

    </div>

</div>
@endsection

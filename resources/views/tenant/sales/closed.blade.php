@extends('layouts.tenant')

@section('content')
<div class="max-w-xl mx-auto py-12">

    <div class="bg-white/5 border border-white/10 rounded p-6 text-center">

        {{-- HEADER --}}
        <h1 class="text-2xl font-semibold mb-1">
            Venta #{{ $sale->id }}
        </h1>

        <p class="text-sm text-gray-400 mb-6">
            Venta cerrada
            @if ($sale->closed_at)
                · {{ $sale->closed_at->format('d/m/Y H:i') }}
            @endif
        </p>

        {{-- CLIENTE --}}
        <div class="mb-4">
            @if ($sale->customer)
                <p class="text-sm text-gray-400">
                    Cliente
                </p>
                <p class="text-blue-400 font-medium">
                    {{ $sale->customer->nombre }}
                    {{ $sale->customer->apellidos ?? '' }}
                    @if (!empty($sale->customer->telefono))
                        · {{ $sale->customer->telefono }}
                    @endif
                </p>
            @else
                <p class="text-sm text-gray-400">
                    Cliente genérico
                </p>
            @endif
        </div>

        {{-- LINEAS --}}
        <div class="text-left mb-4">
            <ul class="divide-y divide-white/10">
                @foreach ($sale->items as $item)
                    <li class="py-2 flex justify-between">
                        <span>
                            {{ $item->name }} x{{ $item->quantity }}
                        </span>
                        <span>
                            {{ number_format($item->subtotal, 2) }} €
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- TOTALES --}}
        <div class="border-t border-white/10 pt-4 text-right space-y-1">
            <div class="text-sm">
                Subtotal: {{ number_format($sale->subtotal, 2) }} €
            </div>
            <div class="text-sm">
                IVA: {{ number_format($sale->tax_total, 2) }} €
            </div>
            <div class="text-lg font-semibold">
                Total: {{ number_format($sale->total, 2) }} €
            </div>
        </div>

        {{-- ACCIONES --}}
        <div class="mt-4 flex gap-3">
    <a href="{{ route('tenant.sales.index') }}"
       class="flex-1 text-center py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
        Volver a ventas
    </a>

    <a href="{{ route('tenant.sales.invoice', $sale->id) }}"
       target="_blank"
       class="flex-1 text-center py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Ticket / Factura
    </a>
</div>


    </div>

</div>
@endsection

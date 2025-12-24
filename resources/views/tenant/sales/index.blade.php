@extends('layouts.tenant')

@section('content')
<div class="max-w-5xl mx-auto py-8">

    {{-- HEADER --}}
    <div class="flex justify-between items-center mb-4">
        <div>
            <h1 class="text-2xl font-semibold">Ventas</h1>

            @if(request()->filled('q'))
                <span class="text-sm text-gray-400">
                    Resultados para "{{ request('q') }}"
                </span>
            @endif
        </div>

        <form method="POST" action="{{ route('tenant.sales.store') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                ➕ Nueva venta
            </button>
        </form>
    </div>

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('tenant.sales.index') }}" class="mb-6">
        <div class="flex gap-2">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Buscar por ID o cliente..."
                   class="flex-1 bg-black/30 border border-white/10 rounded px-3 py-2 text-white placeholder-gray-400">

            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Buscar
            </button>

            @if(request()->filled('q'))
                <a href="{{ route('tenant.sales.index') }}"
                   class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    {{-- SIN VENTAS --}}
    @if ($sales->isEmpty())
        <div class="bg-white/10 p-8 rounded text-center text-gray-300">
            <p class="mb-2">No hay ventas abiertas.</p>
            <p class="text-sm text-gray-400">
                Use el buscador o cree una nueva venta.
            </p>
        </div>

    @else

        {{-- VENTA ACTIVA (LA MÁS RECIENTE) --}}
        @php $mainSale = $sales->first(); @endphp

        <div class="bg-white/5 border border-white/10 rounded p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold">
                        Venta activa #{{ $mainSale->id }}
                    </h2>

                    <p class="text-sm text-gray-400">
                        @if ($mainSale->customer)
                            Cliente:
                            <span class="text-blue-400">
                                {{ $mainSale->customer->nombre }}
                                {{ $mainSale->customer->apellidos ?? '' }}
                            </span>
                            @if (!empty($mainSale->customer->telefono))
                                · {{ $mainSale->customer->telefono }}
                            @else
                                · ID {{ $mainSale->customer->id }}
                            @endif
                        @else
                            Cliente genérico
                        @endif
                    </p>
                </div>

                <div class="text-right space-y-2">
                    <div class="text-2xl font-bold">
                        {{ number_format($mainSale->total, 2) }} €
                    </div>

                    <div class="flex gap-2 justify-end">
                        <a href="{{ route('tenant.sales.show', $mainSale->id) }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Continuar
                        </a>

                        <form method="POST"
                              action="{{ route('tenant.sales.destroy', $mainSale->id) }}"
                              onsubmit="return confirm('¿Cancelar esta venta? Se devolverá el stock.')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- OTRAS VENTAS --}}
        @if ($sales->count() > 1)
            <h3 class="text-sm text-gray-400 mb-2">
                Otras ventas abiertas
            </h3>

            <div class="space-y-2">
                @foreach ($sales->skip(1) as $sale)
                    <div class="flex justify-between items-center bg-white/5 border border-white/10 rounded px-4 py-3">
                        <div>
                            <div>
                                Venta #{{ $sale->id }}
                            </div>

                            <div class="text-xs text-gray-400">
                                @if ($sale->customer)
                                    {{ $sale->customer->nombre }}
                                    {{ $sale->customer->apellidos ?? '' }}
                                    @if (!empty($sale->customer->telefono))
                                        · {{ $sale->customer->telefono }}
                                    @endif
                                @else
                                    Cliente genérico
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <span class="font-medium">
                                {{ number_format($sale->total, 2) }} €
                            </span>

                            <a href="{{ route('tenant.sales.show', $sale->id) }}"
                               class="text-blue-400 hover:underline">
                                Abrir
                            </a>

                            <form method="POST"
                                  action="{{ route('tenant.sales.destroy', $sale->id) }}"
                                  onsubmit="return confirm('¿Cancelar esta venta?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-400 hover:text-red-600">
                                    ✖
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    @endif

</div>
@endsection

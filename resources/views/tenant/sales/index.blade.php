@extends('layouts.tenant')

@section('title', 'Ventas')

@section('content')

<style>
:root {
    --aris-bg-light: #f8fafc;
    --aris-bg-dark: #020617;
    --aris-bg-dark-2: #0f172a;

    --aris-primary: #14b8a6;
    --aris-primary-hover: #0d9488;

    --aris-danger: #ef4444;
    --aris-danger-hover: #dc2626;

    --aris-border: #1e293b;
    --aris-text-soft: #94a3b8;
}
</style>

<div class="max-w-5xl mx-auto py-8 px-4 space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">
                Ventas
            </h1>

            @if(request()->filled('q'))
                <p class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                    Resultados para “{{ request('q') }}”
                </p>
            @endif
        </div>

        <form method="POST" action="{{ route('tenant.sales.store') }}">
            @csrf
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl
                       bg-gradient-to-r from-[var(--aris-primary)] to-[var(--aris-primary-hover)]
                       text-slate-900 font-semibold shadow hover:brightness-110 transition">
                ➕ Nueva venta
            </button>
        </form>
    </div>

    {{-- BUSCADOR --}}
    <form method="GET" action="{{ route('tenant.sales.index') }}">
        <div class="flex flex-col sm:flex-row gap-2">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Buscar por ID o cliente…"
                   class="flex-1 rounded-xl px-4 py-2
                          bg-white dark:bg-[var(--aris-bg-dark)]
                          border border-slate-200 dark:border-[var(--aris-border)]
                          text-slate-800 dark:text-white
                          placeholder-slate-400
                          focus:outline-none focus:ring-2 focus:ring-[var(--aris-primary)]">

            <button type="submit"
                    class="px-5 py-2 rounded-xl
                           bg-[var(--aris-primary)] hover:bg-[var(--aris-primary-hover)]
                           text-slate-900 font-semibold">
                Buscar
            </button>

            @if(request()->filled('q'))
                <a href="{{ route('tenant.sales.index') }}"
                   class="px-5 py-2 rounded-xl
                          bg-slate-200 dark:bg-[var(--aris-bg-dark-2)]
                          text-slate-700 dark:text-slate-200">
                    Limpiar
                </a>
            @endif
        </div>
    </form>

    {{-- SIN VENTAS --}}
    @if ($sales->isEmpty())
        <div class="rounded-2xl p-10 text-center
                    bg-white dark:bg-[var(--aris-bg-dark)]
                    border border-slate-200 dark:border-[var(--aris-border)]">
            <p class="text-lg font-semibold text-slate-700 dark:text-white mb-2">
                No hay ventas
            </p>
            <p class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                Use el buscador o cree una nueva venta.
            </p>
        </div>
    @else

        @php $mainSale = $sales->first(); @endphp

        {{-- VENTA ACTIVA SOLO SI ESTÁ ABIERTA --}}
        @if ($mainSale->status === 'open')
            <div class="rounded-2xl p-6
                        bg-gradient-to-br from-white to-slate-50
                        dark:from-[var(--aris-bg-dark-2)] dark:to-[var(--aris-bg-dark)]
                        border border-slate-200 dark:border-[var(--aris-border)]
                        shadow-xl">

                <div class="flex flex-col md:flex-row justify-between gap-6">
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-800 dark:text-white">
                            Venta activa #{{ $mainSale->id }}
                        </h2>

                        <p class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)] mt-1">
                            @if ($mainSale->customer)
                                Cliente:
                                <span class="text-[var(--aris-primary)] font-semibold">
                                    {{ $mainSale->customer->nombre }}
                                    {{ $mainSale->customer->apellidos ?? '' }}
                                </span>
                            @else
                                Cliente genérico
                            @endif
                        </p>
                    </div>

                    <div class="text-right space-y-3">
                        <div class="text-3xl font-extrabold text-slate-800 dark:text-white">
                            {{ number_format($mainSale->total, 2) }} €
                        </div>

                        <div class="flex gap-3 justify-end">
                            <a href="{{ route('tenant.sales.show', $mainSale->id) }}"
                               class="px-5 py-2 rounded-xl
                                      bg-[var(--aris-primary)] hover:bg-[var(--aris-primary-hover)]
                                      text-slate-900 font-semibold">
                                Continuar
                            </a>

                            <form method="POST"
                                  action="{{ route('tenant.sales.destroy', $mainSale->id) }}"
                                  onsubmit="return confirm('¿Cancelar esta venta? Se devolverá el stock.')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="px-5 py-2 rounded-xl
                                           bg-[var(--aris-danger)] hover:bg-[var(--aris-danger-hover)]
                                           text-white font-semibold">
                                    Cancelar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- LISTADO DE TODAS LAS VENTAS --}}
        <h3 class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
            Historial de ventas
        </h3>

        <div class="space-y-2">
            @foreach ($sales as $sale)
                <div class="flex justify-between items-center
                            rounded-xl px-4 py-3
                            bg-white dark:bg-[var(--aris-bg-dark)]
                            border border-slate-200 dark:border-[var(--aris-border)]
                            hover:shadow transition">

                    <div>
                        <div class="font-semibold text-slate-800 dark:text-white flex items-center gap-2">
                            Venta #{{ $sale->id }}

                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $sale->status === 'open'
                                    ? 'bg-teal-100 text-teal-800'
                                    : 'bg-slate-200 text-slate-700 dark:bg-slate-700 dark:text-slate-300' }}">
                                {{ $sale->status === 'open' ? 'Abierta' : 'Cerrada' }}
                            </span>
                        </div>

                        <div class="text-xs text-slate-500 dark:text-[var(--aris-text-soft)]">
                            @if ($sale->customer)
                                {{ $sale->customer->nombre }} {{ $sale->customer->apellidos ?? '' }}
                            @else
                                Cliente genérico
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="font-bold text-slate-800 dark:text-white">
                            {{ number_format($sale->total, 2) }} €
                        </span>

                        <a href="{{ route('tenant.sales.show', $sale->id) }}"
                           class="text-[var(--aris-primary)] font-semibold hover:underline">
                            Ver
                        </a>

                        @if ($sale->status === 'open')
                            <form method="POST"
                                  action="{{ route('tenant.sales.destroy', $sale->id) }}"
                                  onsubmit="return confirm('¿Cancelar esta venta?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 font-bold">
                                    ✖
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection

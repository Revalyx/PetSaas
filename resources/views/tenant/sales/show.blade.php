@extends('layouts.tenant')

@section('title', 'Venta')

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

    --aris-success: #22c55e;
    --aris-success-hover: #16a34a;

    --aris-border: #1e293b;
    --aris-text-soft: #94a3b8;
}
</style>

<div class="max-w-7xl mx-auto py-8 grid grid-cols-1 lg:grid-cols-3 gap-8 px-4">

    {{-- ===============================
        COLUMNA IZQUIERDA · PRODUCTOS
    =============================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- ERROR STOCK --}}
        @if ($errors->has('stock'))
            <div class="p-3 rounded-xl
                        bg-red-500/15 text-red-400
                        border border-red-500/30">
                {{ $errors->first('stock') }}
            </div>
        @endif

        {{-- VOLVER --}}
        <div>
            <a href="{{ route('tenant.sales.index') }}"
               class="inline-flex items-center gap-2
                      text-sm text-slate-500 dark:text-[var(--aris-text-soft)]
                      hover:text-slate-800 dark:hover:text-white">
                ← Volver a ventas
            </a>
        </div>

        <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white">
            Productos
        </h2>

        {{-- BUSCADOR --}}
        <input type="text"
               id="productSearch"
               placeholder="Buscar producto…"
               class="w-full px-4 py-2 rounded-xl
                      bg-white dark:bg-[var(--aris-bg-dark)]
                      border border-slate-200 dark:border-[var(--aris-border)]
                      text-slate-800 dark:text-white
                      placeholder-slate-400
                      focus:outline-none focus:ring-2 focus:ring-[var(--aris-primary)]">

        {{-- CLIENTE --}}
        <form method="POST"
              action="{{ route('tenant.sales.assign-client', $sale->id) }}"
              class="space-y-1">
            @csrf

            <label class="block text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                Cliente (opcional)
            </label>

            <select name="customer_id"
                    onchange="this.form.submit()"
                    class="w-full rounded-xl px-3 py-2
                           bg-white dark:bg-[var(--aris-bg-dark)]
                           border border-slate-200 dark:border-[var(--aris-border)]
                           text-slate-800 dark:text-white">
                <option value="">— Cliente genérico —</option>

                @foreach ($clientes as $cliente)
                    <option value="{{ $cliente->id }}"
                        @selected($sale->customer_id === $cliente->id)>
                        {{ $cliente->nombre }}
                        {{ $cliente->apellidos ?? '' }}
                        @if (!empty($cliente->telefono))
                            · {{ $cliente->telefono }}
                        @endif
                    </option>
                @endforeach
            </select>
        </form>

        {{-- GRID PRODUCTOS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">

            @foreach ($products as $product)
                <form method="POST"
                      action="{{ route('tenant.sales.items.from-product', $sale->id) }}"
                      data-product-name="{{ strtolower($product->producto) }}"
                      class="product-card rounded-2xl p-4
                             bg-white dark:bg-[var(--aris-bg-dark)]
                             border border-slate-200 dark:border-[var(--aris-border)]
                             hover:shadow-lg transition space-y-2">
                    @csrf

                    <div class="font-semibold text-slate-800 dark:text-white">
                        {{ $product->producto }}
                    </div>

                    <div class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                        {{ number_format($product->pvp, 2) }} € · Stock {{ $product->stock }}
                    </div>

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <input type="number"
                           name="quantity"
                           value="1"
                           min="1"
                           step="1"
                           class="w-full rounded-xl px-3 py-1.5
                                  bg-white dark:bg-[var(--aris-bg-dark-2)]
                                  border border-slate-200 dark:border-[var(--aris-border)]
                                  text-slate-800 dark:text-white">

                    <button type="submit"
                        class="w-full py-1.5 rounded-xl
                               bg-[var(--aris-primary)]
                               hover:bg-[var(--aris-primary-hover)]
                               text-slate-900 font-semibold">
                        Añadir
                    </button>
                </form>
            @endforeach

        </div>
    </div>

    {{-- ===============================
        COLUMNA DERECHA · VENTA
    =============================== --}}
    <div class="rounded-2xl p-6 h-fit
                bg-gradient-to-b from-white to-slate-50
                dark:from-[var(--aris-bg-dark-2)] dark:to-[var(--aris-bg-dark)]
                border border-slate-200 dark:border-[var(--aris-border)]
                shadow-xl space-y-4">

        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 dark:text-white">
                Venta #{{ $sale->id }}
            </h2>

            <p class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                @if ($sale->customer)
                    Cliente:
                    <span class="text-[var(--aris-primary)] font-semibold">
                        {{ $sale->customer->nombre }}
                        {{ $sale->customer->apellidos ?? '' }}
                    </span>
                    @if (!empty($sale->customer->telefono))
                        · {{ $sale->customer->telefono }}
                    @endif
                @else
                    Cliente genérico
                @endif
            </p>
        </div>

        @if ($sale->items->isEmpty())
            <div class="text-sm text-slate-500 dark:text-[var(--aris-text-soft)]">
                Añade productos para continuar la venta.
            </div>
        @else
            <ul class="divide-y divide-slate-200 dark:divide-[var(--aris-border)]">
                @foreach ($sale->items as $item)
                    <li class="py-2 flex justify-between items-center">
                        <div class="font-medium text-slate-800 dark:text-white">
                            {{ $item->name }} x{{ $item->quantity }}
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="font-semibold">
                                {{ number_format($item->subtotal, 2) }} €
                            </span>

                            <form method="POST"
                                  action="{{ route('tenant.sales.items.destroy', [$sale->id, $item->id]) }}"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 font-bold">
                                    ❌
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-slate-200 dark:border-[var(--aris-border)] pt-4 text-right space-y-1">
                <div>Subtotal: {{ number_format($sale->subtotal, 2) }} €</div>
                <div>IVA: {{ number_format($sale->tax_total, 2) }} €</div>
                <div class="text-xl font-extrabold">
                    Total: {{ number_format($sale->total, 2) }} €
                </div>
            </div>

            {{-- CTA FINAL --}}
            <form method="POST"
                  action="{{ route('tenant.sales.close', $sale->id) }}"
                  onsubmit="return confirm('¿Cerrar y cobrar esta venta? No se podrá modificar después.')">
                @csrf

                <button type="submit"
                    class="w-full py-2.5 rounded-xl
                           bg-[var(--aris-success)]
                           hover:bg-[var(--aris-success-hover)]
                           text-slate-900 font-extrabold shadow">
                    Cerrar / Cobrar venta
                </button>
            </form>
        @endif
    </div>

</div>

{{-- BUSCADOR JS (SIN TOCAR) --}}
<script>
document.getElementById('productSearch').addEventListener('input', function () {
    const search = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.dataset.productName.includes(search)
            ? 'block'
            : 'none';
    });
});
</script>
@endsection

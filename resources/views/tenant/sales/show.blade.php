@extends('layouts.tenant')

@section('content')
<div class="max-w-7xl mx-auto py-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- COLUMNA IZQUIERDA: PRODUCTOS --}}
    <div class="lg:col-span-2">
        @if ($errors->has('stock'))
    <div class="mb-4 p-3 rounded bg-red-600/20 text-red-300 border border-red-600/30">
        {{ $errors->first('stock') }}
    </div>
@endif


        {{-- VOLVER --}}
        <div class="mb-4">
            <a href="{{ route('tenant.sales.index') }}"
               class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white">
                ← Volver a ventas
            </a>
        </div>

        <h2 class="text-xl font-semibold mb-4">Productos</h2>

        {{-- BUSCADOR --}}
        <input type="text"
               id="productSearch"
               placeholder="Buscar producto..."
               class="w-full mb-6 px-3 py-2 rounded bg-black/30 border border-white/10 text-white placeholder-gray-400">

        {{-- CLIENTE --}}
        <form method="POST"
              action="{{ route('tenant.sales.assign-client', $sale->id) }}"
              class="mb-6">
            @csrf

            <label class="block text-sm text-gray-400 mb-1">
                Cliente (opcional)
            </label>

            <select name="customer_id"
                    onchange="this.form.submit()"
                    class="w-full bg-black/30 border border-white/10 rounded px-2 py-2 text-white">
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
                      class="product-card border border-white/10 rounded p-4 bg-white/5">
                    @csrf

                    <div class="font-medium mb-1">
                        {{ $product->producto }}
                    </div>

                    <div class="text-sm text-gray-400 mb-2">
                        {{ number_format($product->pvp, 2) }} € · Stock {{ $product->stock }}
                    </div>

                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    <input type="number"
                           name="quantity"
                           value="1"
                           min="1"
                           step="1"
                           class="w-full mb-2 rounded px-2 py-1 bg-black/30 border border-white/10 text-white">

                    <button type="submit"
                        class="w-full py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Añadir
                    </button>
                </form>
            @endforeach
        </div>
    </div>

    {{-- COLUMNA DERECHA: VENTA --}}
    <div class="bg-white/5 border border-white/10 rounded p-5 h-fit">

        <h2 class="text-xl font-semibold mb-1">
            Venta #{{ $sale->id }}
        </h2>

        <p class="text-sm text-gray-400 mb-4">
            @if ($sale->customer)
                Cliente:
                <span class="text-blue-400">
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

        @if ($sale->items->isEmpty())
            <div class="text-gray-400 text-sm">
                Añade productos para continuar la venta.
            </div>
        @else
            <ul class="divide-y divide-white/10 mb-4">
                @foreach ($sale->items as $item)
                    <li class="py-2 flex justify-between items-center">
                        <div class="font-medium">
                            {{ $item->name }} x{{ $item->quantity }}
                        </div>

                        <div class="flex items-center gap-3">
                            <span>
                                {{ number_format($item->subtotal, 2) }} €
                            </span>

                            <form method="POST"
                                  action="{{ route('tenant.sales.items.destroy', [$sale->id, $item->id]) }}"
                                  onsubmit="return confirm('¿Eliminar este producto?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="text-red-400 hover:text-red-600">
                                    ❌
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-white/10 pt-4 text-right space-y-1">
                <div>Subtotal: {{ number_format($sale->subtotal, 2) }} €</div>
                <div>IVA: {{ number_format($sale->tax_total, 2) }} €</div>
                <div class="text-lg font-semibold">
                    Total: {{ number_format($sale->total, 2) }} €
                </div>
            </div>

            {{-- CTA FINAL --}}
<div class="mt-4">
    <form method="POST"
          action="{{ route('tenant.sales.close', $sale->id) }}"
          onsubmit="return confirm('¿Cerrar y cobrar esta venta? No se podrá modificar después.')">
        @csrf

        <button type="submit"
            class="w-full py-2 bg-green-600 text-white rounded hover:bg-green-700">
            Cerrar / Cobrar venta
        </button>
    </form>
</div>

        @endif
    </div>

</div>

{{-- BUSCADOR JS --}}
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

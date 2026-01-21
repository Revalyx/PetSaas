@extends('layouts.tenant')

@section('title', 'Editar producto')

@section('content')
<div class="container mx-auto py-10 max-w-2xl"
     x-data="{
        pricingMode: 'auto',

        precio_real: {{ old('precio_real', $product->precio_real ?? $product->precio) }},
        impuesto: {{ old('porcentaje_impuesto', $product->porcentaje_impuesto ?? 21) }},
        margen: {{ old('margen', $product->margen ?? 0) }},
        pvp_manual: {{ old('pvp', $product->pvp) }},

        get margenSeguro() {
            if (this.margen < 0) return 0
            if (this.margen > 95) return 95
            return this.margen
        },

        get precioSinIva() {
            const coste = Number(this.precio_real || 0)
            const m = Number(this.margenSeguro) / 100
            if (coste <= 0) return 0
            return coste / (1 - m)
        },

        get beneficioCalculado() {
            return (this.precioSinIva - Number(this.precio_real || 0)).toFixed(2)
        },

        get ivaCalculado() {
            return (this.precioSinIva * (Number(this.impuesto || 0) / 100)).toFixed(2)
        },

        get pvpCalculado() {
            return (this.precioSinIva + Number(this.ivaCalculado)).toFixed(2)
        }
     }">

    <h1 class="text-3xl font-extrabold mb-6 text-slate-800 dark:text-slate-100">
        Editar producto
    </h1>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/40
                    bg-red-100 dark:bg-red-900/30
                    p-4 text-red-800 dark:text-red-200">
            <div class="font-semibold mb-2">⚠️ Hay errores en el formulario</div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800
                shadow-xl rounded-2xl p-8
                border border-slate-200 dark:border-slate-700">

        <form method="POST"
              action="{{ route('tenant.products.update', $product->id) }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf
            @method('PUT')

            {{-- INPUT BASE --}}
            @php
                $input = 'w-full rounded-xl border px-3 py-2
                          bg-white text-slate-900 border-slate-300
                          dark:bg-slate-900 dark:text-slate-100 dark:border-slate-600
                          focus:ring-2 focus:ring-teal-500 focus:outline-none';
            @endphp

            {{-- ID ADICIONAL --}}
            <div>
                <label class="block text-sm font-medium mb-1">ID adicional</label>
                <input type="text" name="id_adicional"
                       value="{{ old('id_adicional', $product->id_adicional) }}"
                       class="{{ $input }}">
                <p class="text-xs text-slate-500 mt-1">
                    Referencia interna opcional (SKU, código propio, etc.).
                </p>
            </div>

            {{-- CÓDIGO DE BARRAS --}}
            <div>
                <label class="block text-sm font-medium mb-1">Código de barras</label>
                <input type="text" name="codigo_barras"
                       maxlength="50" inputmode="numeric"
                       value="{{ old('codigo_barras', $product->codigo_barras) }}"
                       class="{{ $input }}">
                <p class="text-xs text-slate-500 mt-1">
                    Solo números. Máximo 50 caracteres.
                </p>
            </div>

            {{-- PRODUCTO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nombre del producto</label>
                <input type="text" name="producto" required
                       value="{{ old('producto', $product->producto) }}"
                       class="{{ $input }}">
            </div>

            {{-- CATEGORÍA --}}
            <div>
                <label class="block text-sm font-medium mb-1">Categoría</label>
                <input type="text" name="categoria"
                       value="{{ old('categoria', $product->categoria) }}"
                       class="{{ $input }}">
            </div>

            {{-- PRECIO REAL --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Precio de compra (proveedor)
                </label>
                <input type="number" step="0.01" min="0" name="precio_real" required
                       x-model.number="precio_real"
                       class="{{ $input }}">
                <p class="text-xs text-slate-500 mt-1">
                    Coste real del producto sin IVA.
                </p>
            </div>

            {{-- IVA --}}
            <div>
                <label class="block text-sm font-medium mb-1">% IVA</label>
                <input type="number" step="0.01" min="0"
                       name="porcentaje_impuesto"
                       x-model.number="impuesto"
                       class="{{ $input }}">
            </div>

            {{-- MODO PRECIO --}}
            <div class="rounded-xl border border-slate-300 dark:border-slate-600 p-4 space-y-3">
                <div class="text-sm font-semibold">
                    ¿Cómo desea fijar el precio?
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="radio" value="auto" x-model="pricingMode">
                    Calcular automáticamente con margen
                </label>

                <label class="flex items-center gap-2 text-sm">
                    <input type="radio" value="manual" x-model="pricingMode">
                    Introducir PVP manualmente
                </label>
            </div>

            {{-- MARGEN --}}
            <div x-show="pricingMode === 'auto'">
                <label class="block text-sm font-medium mb-1">
                    Margen (%) <span class="text-xs text-slate-500">(máx. 95%)</span>
                </label>
                <input type="number" step="0.01" min="0" max="95"
                       name="margen"
                       x-model.number="margen"
                       class="{{ $input }}">

                <div class="mt-2 text-xs text-slate-600 dark:text-slate-300 space-y-1">
                    <div>Beneficio: <strong x-text="beneficioCalculado + ' €'"></strong></div>
                    <div>IVA: <strong x-text="ivaCalculado + ' €'"></strong></div>
                    <div>PVP final: <strong x-text="pvpCalculado + ' €'"></strong></div>
                </div>
            </div>

            {{-- PVP MANUAL --}}
            <div x-show="pricingMode === 'manual'">
                <label class="block text-sm font-medium mb-1">PVP (€)</label>
                <input type="number" step="0.01" min="0"
                       name="pvp"
                       x-model.number="pvp_manual"
                       class="{{ $input }}">
            </div>

            {{-- STOCK --}}
            <div>
                <label class="block text-sm font-medium mb-1">Stock</label>
                <input type="number" min="0" name="stock" required
                       value="{{ old('stock', $product->stock) }}"
                       class="{{ $input }}">
            </div>

            {{-- IMAGEN ACTUAL --}}
            @if($product->image_path)
                <div>
                    <p class="text-xs text-slate-500 mb-2">Imagen actual</p>
                    <img src="{{ asset('storage/'.$product->image_path) }}"
                         class="w-32 h-32 object-cover rounded-xl border">
                </div>
            @endif

            {{-- NUEVA IMAGEN --}}
            <div>
                <label class="block text-sm font-medium mb-1">Cambiar imagen</label>
                <input type="file" name="image" accept="image/*"
                       class="{{ $input }}">
            </div>

            {{-- ALT SEO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Texto alternativo (SEO)</label>
                <input type="text" name="image_alt"
                       value="{{ old('image_alt', $product->image_alt) }}"
                       class="{{ $input }}">
            </div>

            {{-- INPUTS OCULTOS --}}
            <input type="hidden" name="precio"
                   :value="pricingMode === 'auto'
                            ? precioSinIva.toFixed(2)
                            : (pvp_manual / (1 + impuesto/100)).toFixed(2)">

            <input type="hidden" name="pvp"
                   :value="pricingMode === 'auto' ? pvpCalculado : pvp_manual">

            <input type="hidden" name="beneficio"
                   :value="pricingMode === 'auto'
                            ? beneficioCalculado
                            : (pvp_manual - precio_real).toFixed(2)">

            <input type="hidden" name="margen"
                   :value="pricingMode === 'auto' ? margenSeguro : 0">

            <button type="submit"
                    class="w-full py-3 rounded-xl
                           bg-teal-600 hover:bg-teal-700
                           text-white font-semibold transition shadow">
                Guardar cambios
            </button>
        </form>
    </div>
</div>
@endsection

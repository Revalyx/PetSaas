@extends('layouts.tenant')

@section('title', 'Añadir producto')

@section('content')
<div class="container mx-auto py-10 max-w-2xl"
     x-data="{
        pricingMode: 'auto', // auto | manual

        // PRECIOS (fuente de verdad)
        precio_real: {{ old('precio_real', 0) ?: 0 }},
        impuesto: {{ old('porcentaje_impuesto', 21) ?: 0 }},
        margen: {{ old('margen', 0) ?: 0 }},
        pvp_manual: {{ old('pvp', 0) ?: 0 }},

        // ===== LÓGICA CORRECTA (MARGEN REAL) =====
        // precio_sin_iva = precio_real / (1 - margen/100)
        // beneficio = precio_sin_iva - precio_real
        // iva = precio_sin_iva * (impuesto/100)
        // pvp = precio_sin_iva + iva

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
        Añadir producto
    </h1>

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-500/40
                    bg-red-100 dark:bg-red-900/30
                    p-4 text-red-800 dark:text-red-200">
            <div class="flex items-center gap-2 mb-2 font-semibold">
                <span>⚠️</span>
                <span>Hay errores en el formulario</span>
            </div>
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
              action="{{ route('tenant.products.store') }}"
              enctype="multipart/form-data"
              class="space-y-5">
            @csrf

            {{-- ID ADICIONAL --}}
            <div>
                <label class="block text-sm font-medium mb-1">ID adicional</label>
                <input type="text" name="id_adicional"
                       value="{{ old('id_adicional') }}"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Referencia interna opcional (SKU, código propio, etc.).
                </p>
            </div>

            {{-- CÓDIGO DE BARRAS --}}
            <div>
                <label class="block text-sm font-medium mb-1">Código de barras</label>
                <input type="text" name="codigo_barras"
                       value="{{ old('codigo_barras') }}"
                       maxlength="50"
                       inputmode="numeric"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Solo números. Máximo 50 caracteres.
                </p>
            </div>

            {{-- PRODUCTO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nombre del producto</label>
                <input type="text" name="producto" required
                       value="{{ old('producto') }}"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Nombre visible para clientes y facturación.
                </p>
            </div>

            {{-- CATEGORÍA --}}
            <div>
                <label class="block text-sm font-medium mb-1">Categoría</label>
                <input type="text" name="categoria"
                       value="{{ old('categoria') }}"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Ayuda a filtrar y organizar el inventario.
                </p>
            </div>

            {{-- PRECIO REAL --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Precio de compra (proveedor)
                </label>
                <input type="number" step="0.01" min="0" name="precio_real" required
                       x-model.number="precio_real"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Coste real del producto sin IVA.
                </p>
            </div>

            {{-- IMPUESTO --}}
            <div>
                <label class="block text-sm font-medium mb-1">% Impuesto (IVA)</label>
                <input type="number" step="0.01" min="0" name="porcentaje_impuesto" required
                       x-model.number="impuesto"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    IVA aplicado al precio de venta.
                </p>
            </div>

            {{-- MODO PRECIO --}}
            <div class="rounded-xl border p-4 space-y-3">
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
                <input type="number"
                       step="0.01"
                       min="0"
                       max="95"
                       name="margen"
                       x-model.number="margen"
                       class="w-full rounded-xl border px-3 py-2">
                <p x-show="margen > 95" class="text-xs text-red-600 mt-1">
                    El margen máximo permitido es 95% para evitar precios inválidos.
                </p>

                <div class="mt-2 text-xs text-slate-600 space-y-1">
                    <div>Beneficio: <strong x-text="beneficioCalculado + ' €'"></strong></div>
                    <div>IVA: <strong x-text="ivaCalculado + ' €'"></strong></div>
                    <div>PVP final: <strong x-text="pvpCalculado + ' €'"></strong></div>
                </div>
            </div>

            {{-- PVP MANUAL --}}
            <div x-show="pricingMode === 'manual'">
                <label class="block text-sm font-medium mb-1">PVP (€)</label>
                <input type="number" step="0.01" min="0" name="pvp"
                       x-model.number="pvp_manual"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Precio final al cliente con IVA incluido.
                </p>
            </div>

            {{-- STOCK --}}
            <div>
                <label class="block text-sm font-medium mb-1">Stock inicial</label>
                <input type="number" min="0" name="stock" required
                       value="{{ old('stock') }}"
                       class="w-full rounded-xl border px-3 py-2">
                <p class="text-xs text-slate-500 mt-1">
                    Unidades disponibles al crear el producto.
                </p>
            </div>

            {{-- IMAGEN --}}
            <div>
                <label class="block text-sm font-medium mb-2">Imagen del producto</label>
                <input type="file" name="image" accept="image/*"
                       onchange="previewImage(event)"
                       class="w-full rounded-xl border px-3 py-2">
                <img id="preview"
                     class="w-40 h-40 mt-3 object-cover rounded-xl border hidden">
            </div>

            {{-- ALT SEO --}}
            <div class="relative">
                <label class="block text-sm font-medium mb-1 flex items-center gap-2">
                    Texto alternativo (SEO)
                    <span class="relative group cursor-pointer text-slate-400">
                        ℹ️
                        <span class="absolute left-1/2 -translate-x-1/2 mt-2
                                     hidden group-hover:block
                                     bg-slate-800 text-white text-xs
                                     rounded-lg px-3 py-2 w-64 z-10">
                            Describe la imagen para buscadores y accesibilidad.
                            <br><br>
                            Ejemplo: <em>Pelota roja de goma para perros pequeños</em>
                        </span>
                    </span>
                </label>
                <input type="text" name="image_alt"
                       value="{{ old('image_alt') }}"
                       class="w-full rounded-xl border px-3 py-2">
            </div>

            {{-- INPUTS OCULTOS PARA BACKEND --}}
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
                Guardar producto
            </button>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.classList.remove('hidden');
}
</script>
@endsection

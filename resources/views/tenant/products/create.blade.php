@extends('layouts.tenant')

@section('title', 'Añadir producto')

@section('content')

<style>
/* ===========================
   VARIABLES ARIS
=========================== */
:root {
    --aris-bg-light: #f8fafc;
    --aris-bg-dark: #020617;
    --aris-bg-dark-2: #0f172a;

    --aris-primary: #14b8a6;
    --aris-primary-hover: #0d9488;

    --aris-border-light: #cbd5f5;
    --aris-border-dark: #1e293b;

    --aris-text-light: #020617;
    --aris-text-dark: #e5e7eb;

    --aris-muted-light: #475569;
    --aris-muted-dark: #94a3b8;
}

/* ===========================
   INPUTS ARIS
=========================== */
.aris-input {
    background-color: white;
    color: var(--aris-text-light);
    border: 1px solid var(--aris-border-light);
}

.dark .aris-input {
    background-color: var(--aris-bg-dark-2);
    color: var(--aris-text-dark);
    border-color: var(--aris-border-dark);
}

.aris-input::placeholder {
    color: var(--aris-muted-light);
}

.dark .aris-input::placeholder {
    color: var(--aris-muted-dark);
}

.aris-input:focus {
    outline: none;
    border-color: var(--aris-primary);
    box-shadow: 0 0 0 2px rgba(20,184,166,.35);
}

/* RADIO */
.aris-radio {
    accent-color: var(--aris-primary);
}
</style>

<div class="container mx-auto py-10 max-w-2xl"
     x-data="{
        pricingMode: 'auto',

        precio_real: {{ old('precio_real', 0) ?: 0 }},
        impuesto: {{ old('porcentaje_impuesto', 21) ?: 0 }},
        margen: {{ old('margen', 0) ?: 0 }},
        pvp_manual: {{ old('pvp', 0) ?: 0 }},

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
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- CÓDIGO DE BARRAS --}}
            <div>
                <label class="block text-sm font-medium mb-1">Código de barras</label>
                <input type="text" name="codigo_barras"
                       value="{{ old('codigo_barras') }}"
                       maxlength="50"
                       inputmode="numeric"
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- PRODUCTO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nombre del producto</label>
                <input type="text" name="producto" required
                       value="{{ old('producto') }}"
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- CATEGORÍA --}}
            <div>
                <label class="block text-sm font-medium mb-1">Categoría</label>
                <input type="text" name="categoria"
                       value="{{ old('categoria') }}"
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- PRECIO REAL --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Precio de compra (proveedor)
                </label>
                <input type="number" step="0.01" min="0" name="precio_real" required
                       x-model.number="precio_real"
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- IMPUESTO --}}
            <div>
                <label class="block text-sm font-medium mb-1">% Impuesto (IVA)</label>
                <input type="number" step="0.01" min="0" name="porcentaje_impuesto" required
                       x-model.number="impuesto"
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- MODO PRECIO --}}
            <div class="rounded-xl border p-4 space-y-3">
                <div class="text-sm font-semibold">
                    ¿Cómo desea fijar el precio?
                </div>

                <label class="flex items-center gap-2 text-sm">
                    <input type="radio" value="auto" x-model="pricingMode" class="aris-radio">
                    Calcular automáticamente con margen
                </label>

                <label class="flex items-center gap-2 text-sm">
                    <input type="radio" value="manual" x-model="pricingMode" class="aris-radio">
                    Introducir PVP manualmente
                </label>
            </div>

            {{-- MARGEN --}}
            <div x-show="pricingMode === 'auto'">
                <label class="block text-sm font-medium mb-1">
                    Margen (%)
                </label>
                <input type="number"
                       step="0.01"
                       min="0"
                       max="95"
                       name="margen"
                       x-model.number="margen"
                       class="w-full rounded-xl px-3 py-2 aris-input">

                <div class="mt-2 text-xs text-slate-600 dark:text-slate-400 space-y-1">
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
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- STOCK --}}
            <div>
                <label class="block text-sm font-medium mb-1">Stock inicial</label>
                <input type="number" min="0" name="stock" required
                       value="{{ old('stock') }}"
                       class="w-full rounded-xl px-3 py-2 aris-input">
            </div>

            {{-- IMAGEN --}}
            <div>
                <label class="block text-sm font-medium mb-2">Imagen del producto</label>
                <input type="file" name="image" accept="image/*"
                       onchange="previewImage(event)"
                       class="w-full rounded-xl px-3 py-2 aris-input">
                <img id="preview"
                     class="w-40 h-40 mt-3 object-cover rounded-xl border hidden">
            </div>

            {{-- ALT SEO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Texto alternativo (SEO)</label>
                <input type="text" name="image_alt"
                       value="{{ old('image_alt') }}"
                       class="w-full rounded-xl px-3 py-2 aris-input">
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
                           bg-[var(--aris-primary)]
                           hover:bg-[var(--aris-primary-hover)]
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

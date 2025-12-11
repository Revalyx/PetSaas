@extends('layouts.tenant')
@section('title', 'Editar producto')

@section('content')
<div class="container mx-auto py-10 max-w-xl">

    <h1 class="text-3xl font-bold mb-6 text-white">Editar producto</h1>

    <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-lg p-8">

        <form method="POST" action="{{ route('tenant.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ID ADICIONAL --}}
            <div class="mb-6">
                <label class="text-gray-300">ID adicional</label>
                <input type="text" name="id_adicional"
                    value="{{ old('id_adicional', $product->id_adicional) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- CÓDIGO DE BARRAS --}}
            <div class="mb-6">
                <label class="text-gray-300">Código de barras</label>
                <input type="text" name="codigo_barras"
                    maxlength="13" inputmode="numeric"
                    pattern="\d{8,13}"
                    value="{{ old('codigo_barras', $product->codigo_barras) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                    placeholder="Ej: 1234567890123"
                />
                <p class="text-xs text-gray-500">Solo números (8–13 dígitos).</p>
            </div>

            {{-- CATEGORÍA --}}
            <div class="mb-6">
                <label class="text-gray-300">Categoría</label>
                <input type="text" name="categoria"
                    value="{{ old('categoria', $product->categoria) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- NOMBRE DEL PRODUCTO --}}
            <div class="mb-6">
                <label class="text-gray-300">Nombre del producto</label>
                <input type="text" name="producto" required
                    value="{{ old('producto', $product->producto) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- PRECIO BASE --}}
            <div class="mb-6">
                <label class="text-gray-300">Precio base (€)</label>
                <input type="number" name="precio" step="0.01" required
                    value="{{ old('precio', $product->precio) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                    oninput="recalculate()"
                />
            </div>

            {{-- % IMPUESTO --}}
            <div class="mb-6">
                <label class="text-gray-300">Porcentaje impuesto (%)</label>
                <input type="number" step="0.01" name="porcentaje_impuesto" required
                    value="{{ old('porcentaje_impuesto', $product->porcentaje_impuesto) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                    oninput="recalculate()"
                />
            </div>

            {{-- PVP CALCULADO --}}
            <div class="mb-6">
                <label class="text-gray-300">PVP (€)</label>
                <input type="number" step="0.01" name="pvp" readonly
                    value="{{ old('pvp', $product->pvp) }}"
                    class="w-full bg-gray-700 border border-blue-600 rounded-lg px-3 py-2 text-green-300 font-bold"
                />
            </div>

            {{-- BENEFICIO --}}
            <div class="mb-6">
                <label class="text-gray-300">Beneficio (€)</label>
                <input type="number" step="0.01" name="beneficio" readonly
                    value="{{ old('beneficio', $product->beneficio) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- MARGEN --}}
            <div class="mb-6">
                <label class="text-gray-300">Margen (€)</label>
                <input type="number" step="0.01" name="margen" readonly
                    value="{{ old('margen', $product->margen) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- STOCK --}}
            <div class="mb-6">
                <label class="text-gray-300">Stock</label>
                <input type="number" name="stock" min="0" required
                    value="{{ old('stock', $product->stock) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- IMAGEN ACTUAL --}}
            @if($product->image_path)
                <div class="mb-6">
                    <p class="text-gray-400 mb-2">Imagen actual:</p>
                    <img src="{{ asset('storage/' . $product->image_path) }}"
                         class="w-32 h-32 object-cover rounded-lg shadow">
                </div>
            @endif

            {{-- NUEVA IMAGEN --}}
            <div class="mb-6">
                <label class="text-gray-300">Cambiar imagen</label>
                <input type="file" name="image" accept="image/*" class="w-full mt-2">
            </div>

            {{-- ALT --}}
            <div class="mb-6">
                <label class="text-gray-300">Texto alternativo</label>
                <input type="text" name="image_alt"
                    value="{{ old('image_alt', $product->image_alt) }}"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white"
                />
            </div>

            {{-- BOTÓN --}}
            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 transition text-white font-semibold rounded-lg">
                Guardar cambios
            </button>

        </form>

    </div>

</div>

<script>
function recalculate() {
    let precio = parseFloat(document.querySelector("[name='precio']").value) || 0;
    let impuesto = parseFloat(document.querySelector("[name='porcentaje_impuesto']").value) || 0;

    let pvp = precio + (precio * impuesto / 100);

    document.querySelector("[name='pvp']").value = pvp.toFixed(2);
    document.querySelector("[name='beneficio']").value = (pvp - precio).toFixed(2);
    document.querySelector("[name='margen']").value = (pvp - precio).toFixed(2);
}
</script>
@endsection

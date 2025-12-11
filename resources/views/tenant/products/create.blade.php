@extends('layouts.tenant')

@section('title', 'Añadir producto')

@section('content')
<div class="container mx-auto py-10 max-w-2xl">

    <h1 class="text-3xl font-bold mb-6 text-gray-100">Añadir producto</h1>

    <div class="bg-gray-900 shadow-lg rounded-lg p-8 border border-gray-700">

        <form method="POST" action="{{ route('tenant.products.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- ID ADICIONAL --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">ID adicional</label>
                <input type="text" name="id_adicional" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- CÓDIGO DE BARRAS --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Código de barras</label>
                <input type="text" name="codigo_barras" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- CATEGORÍA --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Categoría</label>
                <input type="text" name="categoria" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- PRODUCTO --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Nombre del producto</label>
                <input type="text" name="producto" required class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- PRECIO --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Precio (€)</label>
                <input type="number" step="0.01" name="precio" required class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- PORCENTAJE IMPUESTO --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">% Impuesto</label>
                <input type="number" step="0.01" name="porcentaje_impuesto" required class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- PVP --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">PVP (€) — Opcional (se calcula si lo dejas vacío)</label>
                <input type="number" step="0.01" name="pvp" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- BENEFICIO --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Beneficio (€) — Opcional</label>
                <input type="number" step="0.01" name="beneficio" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- MARGEN --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Margen (%) — Opcional</label>
                <input type="number" step="0.01" name="margen" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- STOCK --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Stock</label>
                <input type="number" name="stock" required class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            {{-- IMAGEN --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-2">Imagen del producto</label>

                <input type="file" name="image" accept="image/*" 
                    onchange="previewImage(event)"
                    class="w-full p-2 rounded bg-gray-800 text-white">

                <div class="mt-3">
                    <img id="preview" class="w-40 h-40 object-cover rounded border border-gray-700 hidden">
                </div>
            </div>

            {{-- IMAGEN ALT --}}
            <div class="mb-5">
                <label class="block text-gray-300 mb-1">Texto alternativo (SEO)</label>
                <input type="text" name="image_alt" class="w-full p-2 rounded bg-gray-800 text-white">
            </div>

            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded font-semibold">
                Guardar producto
            </button>
        </form>

    </div>
</div>

<script>
function previewImage(event) {
    let img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.classList.remove('hidden');
}
</script>

@endsection

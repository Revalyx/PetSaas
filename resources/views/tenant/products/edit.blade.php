@extends('layouts.tenant')
@section('title', 'Editar producto')

@section('content')
<div class="container mx-auto py-10 max-w-xl">

    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Editar producto</h1>

    <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg p-8 border border-gray-200 dark:border-gray-700">

        <form method="POST" action="{{ route('tenant.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- NOMBRE --}}
            <div class="relative mb-6">
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 -top-3 text-sm text-blue-600">Nombre</label>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="relative mb-6">
                <textarea name="description" rows="3"
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >{{ old('description', $product->description) }}</textarea>
                <label class="absolute left-3 -top-3 text-sm text-blue-600">Descripción</label>
            </div>

            {{-- PRECIO --}}
            <div class="relative mb-6">
                <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 -top-3 text-sm text-blue-600">Precio (€)</label>
            </div>

            {{-- STOCK --}}
            <div class="relative mb-6">
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 -top-3 text-sm text-blue-600">Stock</label>
            </div>

            {{-- BARCODE (VALIDADO) --}}
            <div class="mb-6">
                <label class="block text-gray-300 mb-1">Código de barras</label>

                <input
                    type="text"
                    name="barcode"
                    value="{{ old('barcode', $product->barcode) }}"
                    maxlength="13"
                    pattern="\d{8,13}"
                    inputmode="numeric"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white
                        focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ej: 1234567890123 (solo números)"
                />

                <p class="text-xs text-gray-500 mt-1">
                    Solo números — Longitud permitida: 8 a 13 dígitos.
                </p>

                @error('barcode')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- IMAGEN ACTUAL --}}
            @if($product->image_url)
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-300 mb-2">Imagen actual:</p>
                    <img src="{{ $product->image_url }}" class="w-32 h-32 object-cover rounded-lg shadow">
                </div>
            @endif

            {{-- NUEVA IMAGEN --}}
            <div class="mb-6">
                <label class="font-semibold text-gray-700 dark:text-gray-300">Cambiar imagen</label>
                <input type="file" name="image" accept="image/*" class="w-full mt-2">
            </div>

            {{-- ALT --}}
            <div class="relative mb-8">
                <input type="text" name="image_alt" value="{{ old('image_alt', $product->image_alt) }}"
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 -top-3 text-sm text-blue-600">Texto alternativo</label>
            </div>

            {{-- BOTÓN --}}
            <button type="submit"
                class="w-full py-3 bg-green-600 hover:bg-green-700 transition text-white font-semibold rounded-lg">
                Guardar cambios
            </button>

        </form>

    </div>

</div>
@endsection

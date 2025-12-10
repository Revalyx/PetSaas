@extends('layouts.tenant')
@section('title', 'Crear producto')

@section('content')
<div class="container mx-auto py-10 max-w-xl">

    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">Añadir producto</h1>

    <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg p-8 border border-gray-200 dark:border-gray-700">

        <form method="POST" action="{{ route('tenant.products.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- NOMBRE --}}
            <div class="relative mb-6">
                <input type="text" name="name" required
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 top-3 text-gray-500 dark:text-gray-400 transition-all
                    peer-focus:-top-3 peer-focus:text-sm peer-focus:text-blue-600 peer-valid:-top-3 peer-valid:text-sm">
                    Nombre
                </label>
            </div>

            {{-- DESCRIPCIÓN --}}
            <div class="relative mb-6">
                <textarea name="description" rows="3"
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                ></textarea>
                <label class="absolute left-3 top-3 text-gray-500 dark:text-gray-400 transition-all
                    peer-focus:-top-3 peer-focus:text-sm peer-focus:text-blue-600 peer-valid:-top-3 peer-valid:text-sm">
                    Descripción
                </label>
            </div>

            {{-- PRECIO --}}
            <div class="relative mb-6">
                <input type="number" step="0.01" name="price" required
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 top-3 text-gray-500 dark:text-gray-400 transition-all
                    peer-focus:-top-3 peer-focus:text-sm peer-focus:text-blue-600 peer-valid:-top-3 peer-valid:text-sm">
                    Precio (€)
                </label>
            </div>

            {{-- STOCK --}}
            <div class="relative mb-6">
                <input type="number" name="stock" required
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 top-3 text-gray-500 dark:text-gray-400 transition-all
                    peer-focus:-top-3 peer-focus:text-sm peer-focus:text-blue-600 peer-valid:-top-3 peer-valid:text-sm">
                    Stock
                </label>
            </div>

            {{-- BARCODE --}}
            <div class="mb-6">
                <label class="block text-gray-300 mb-1">Código de barras</label>

                <input
                    type="text"
                    name="barcode"
                    value="{{ old('barcode') }}"
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

            {{-- IMAGEN --}}
            <div class="mb-6">
                <label class="font-semibold text-gray-700 dark:text-gray-300 mb-2 block">Imagen del producto</label>
                <input type="file" name="image" accept="image/*" class="w-full">
            </div>

            {{-- ALT --}}
            <div class="relative mb-8">
                <input type="text" name="image_alt"
                    class="peer w-full border border-gray-300 dark:border-gray-700 rounded-lg p-3 bg-transparent
                    text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <label class="absolute left-3 top-3 text-gray-500 dark:text-gray-400 transition-all
                    peer-focus:-top-3 peer-focus:text-sm peer-focus:text-blue-600 peer-valid:-top-3 peer-valid:text-sm">
                    Texto alternativo
                </label>
            </div>

            {{-- BOTÓN --}}
            <button type="submit"
                class="w-full py-3 bg-blue-600 hover:bg-blue-700 transition text-white font-semibold rounded-lg">
                Guardar producto
            </button>

        </form>

    </div>

</div>
@endsection

@extends('layouts.tenant')

@section('title', 'Productos')

@section('content')

<div class="p-6">

    {{-- TÍTULO + BOTONES --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Productos</h1>

        <div class="flex gap-3">

            {{-- Botón cambiar vista --}}
            <button id="toggleViewBtn"
                onclick="toggleView()"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg shadow transition">
                Cambiar a Vista Lista
            </button>

            {{-- Crear producto --}}
            <a href="{{ route('tenant.products.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                + Nuevo Producto
            </a>
        </div>
    </div>


    {{-- =========================   VISTA GRID (DEFAULT)   ========================= --}}
    <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach($products as $p)
        <div class="bg-gray-800 border border-gray-700 rounded-xl p-5 shadow-lg hover:scale-[1.01] transition">

            {{-- IMAGEN --}}
            <div class="flex justify-center mb-4">
                @if($p->image_url)
                    <img src="{{ $p->image_url }}" class="w-28 h-28 object-cover rounded-lg shadow">
                @else
                    <div class="w-28 h-28 bg-gray-600 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                        Sin imagen
                    </div>
                @endif
            </div>

            {{-- DATOS --}}
            <h2 class="text-xl font-semibold text-white text-center">{{ $p->name }}</h2>
            <p class="text-gray-400 text-center mt-1">{{ $p->barcode ?? '—' }}</p>
            <p class="text-green-400 text-center font-bold text-lg mt-2">{{ number_format($p->price, 2) }} €</p>

            <div class="text-center mt-2">
                <span class="px-3 py-1 rounded text-xs
                    @if($p->stock == 0) bg-red-600
                    @elseif($p->stock < 5) bg-yellow-400 text-black
                    @else bg-green-600
                    @endif
                ">
                    Stock: {{ $p->stock }}
                </span>
            </div>

            {{-- ACCIONES --}}
            <div class="flex justify-center gap-3 mt-5">
                <a href="{{ route('tenant.products.edit', $p->id) }}"
                   class="px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">✏️</a>

                <button onclick="openDeleteModal({{ $p->id }}, '{{ $p->name }}')"
                        class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    🗑️
                </button>
            </div>
        </div>
        @endforeach

    </div>


    {{-- =========================   VISTA LISTA (INICIALMENTE OCULTA)   ========================= --}}
    <div id="listView" class="hidden">

        <div class="bg-gray-800/40 border border-gray-700 rounded-xl shadow-lg overflow-hidden">

            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-700 text-gray-200 uppercase text-xs tracking-wider">
                        <th class="px-4 py-3">Imagen</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Precio</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Código</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody class="text-white text-sm">
                    @foreach($products as $p)
                    <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">

                        <td class="px-4 py-3">
                            @if($p->image_url)
                                <img src="{{ $p->image_url }}" class="w-12 h-12 object-cover rounded">
                            @else
                                <span class="text-gray-500 text-xs italic">Sin imagen</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 font-semibold">{{ $p->name }}</td>
                        <td class="px-4 py-3">{{ number_format($p->price, 2) }} €</td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs
                                @if($p->stock == 0) bg-red-600
                                @elseif($p->stock < 5) bg-yellow-500 text-black
                                @else bg-green-600
                                @endif
                            ">
                                {{ $p->stock }}
                            </span>
                        </td>

                        <td class="px-4 py-3">{{ $p->barcode ?? '—' }}</td>

                        <td class="px-4 py-3 flex justify-center gap-3">

                            <a href="{{ route('tenant.products.edit', $p->id) }}"
                                class="px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                ✏️
                            </a>

                            <button onclick="openDeleteModal({{ $p->id }}, '{{ $p->name }}')"
                                    class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                🗑️
                            </button>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</div>



{{-- ========================== MODAL DE BORRADO ========================== --}}
<div id="deleteModal"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden justify-center items-center z-50">

    <div class="bg-gray-800 border border-gray-700 p-6 rounded-xl w-full max-w-md shadow-xl">

        <h2 class="text-xl font-bold text-white mb-4">Eliminar producto</h2>

        <p id="deleteMessage" class="text-gray-300 mb-6"></p>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end gap-3">

                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg">
                    Cancelar
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                    Eliminar
                </button>

            </div>
        </form>

    </div>

</div>



{{-- ========================== JS: TOGGLE VISTA + MODAL ========================== --}}
<script>

function toggleView() {
    const grid = document.getElementById('gridView');
    const list = document.getElementById('listView');
    const btn = document.getElementById('toggleViewBtn');

    if (grid.classList.contains('hidden')) {
        grid.classList.remove('hidden');
        list.classList.add('hidden');
        btn.textContent = "Cambiar a Vista Lista";
    } else {
        grid.classList.add('hidden');
        list.classList.remove('hidden');
        btn.textContent = "Cambiar a Vista Grid";
    }
}


function openDeleteModal(id, name) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const msg = document.getElementById('deleteMessage');

    form.action = `/tenant/products/${id}`;
    msg.textContent = `¿Seguro que desea eliminar el producto "${name}"? Esta acción no se puede deshacer.`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

</script>

@endsection

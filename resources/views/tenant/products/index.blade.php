@extends('layouts.tenant')

@section('title', 'Productos')

@section('content')

<div class="p-6">

    {{-- TÍTULO + BOTONES --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-white">Productos</h1>

        <div class="flex gap-3">
            <button id="toggleViewBtn"
                onclick="toggleView()"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg shadow transition">
                Cambiar a Vista Lista
            </button>

            <a href="{{ route('tenant.products.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                + Nuevo Producto
            </a>
        </div>
    </div>


    {{-- ========================= GRID (DEFAULT) ========================= --}}
<div id="gridView" class="w-full flex justify-center">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl w-full">

        @foreach($products as $p)
        <div class="bg-gray-800/90 border border-gray-700 rounded-2xl p-6 shadow-lg 
                    hover:-translate-y-1 hover:shadow-blue-500/20 transition-all duration-200 flex flex-col">

            {{-- IMAGEN --}}
            <div class="flex justify-center mb-4">
                @if($p->image_path)
                    <img src="{{ asset('storage/' . $p->image_path) }}"
                         class="w-32 h-32 object-cover rounded-xl shadow border border-gray-600">
                @else
                    <div class="w-32 h-32 bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 text-xs">
                        Sin imagen
                    </div>
                @endif
            </div>

            {{-- TITULO --}}
            <h2 class="text-lg font-bold text-white text-center">{{ $p->producto }}</h2>

            {{-- CODIGO --}}
            <p class="text-gray-400 text-xs text-center mt-1">
                Código: {{ $p->codigo_barras ?: '—' }}
            </p>

            {{-- CATEGORÍA --}}
            @if($p->categoria)
            <div class="flex justify-center mt-2">
                <span class="px-3 py-1 rounded-full text-xs bg-gray-700 text-gray-300 border border-gray-600">
                    {{ $p->categoria }}
                </span>
            </div>
            @endif

            <hr class="border-gray-700 my-4">

            {{-- BLOQUE PRECIOS --}}
            <div class="text-sm text-gray-300 space-y-2">

                <div class="flex justify-between">
                    <span>Precio base:</span>
                    <span class="font-semibold">{{ number_format($p->precio, 2) }} €</span>
                </div>

                <div class="flex justify-between">
                    <span>Impuesto aplicado:</span>
                    <span class="font-semibold">{{ number_format($p->porcentaje_impuesto, 2) }} %</span>
                </div>

                <div class="flex justify-between text-green-400 font-semibold text-lg pt-2">
                    <span>PVP:</span>
                    <span>{{ number_format($p->pvp, 2) }} €</span>
                </div>

                <div class="flex justify-between">
                    <span>Beneficio:</span>
                    <span class="font-semibold">{{ number_format($p->beneficio, 2) }} €</span>
                </div>

                <div class="flex justify-between">
                    <span>Margen:</span>
                    <span class="font-semibold">{{ number_format($p->margen, 2) }} €</span>
                </div>

            </div>

            {{-- STOCK --}}
            <div class="text-center mt-4">
                <span class="px-4 py-1 rounded-full text-xs font-semibold
                    @if($p->stock == 0) bg-red-600
                    @elseif($p->stock < 5) bg-yellow-400 text-black
                    @else bg-green-600
                    @endif">
                    Stock: {{ $p->stock }}
                </span>
            </div>

            {{-- FECHA --}}
            <p class="text-center text-gray-500 text-xs mt-4">
                Añadido el {{ $p->created_at->format('d/m/Y') }}
            </p>

            {{-- ACCIONES --}}
            <div class="flex justify-center gap-4 mt-6">

                <a href="{{ route('tenant.products.edit', $p->id) }}"
                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-600 hover:bg-indigo-700 
                          shadow-md hover:shadow-indigo-500/30 transition text-xl">
                    ✏️
                </a>

                <button onclick="openDeleteModal({{ $p->id }}, '{{ $p->producto }}')"
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-600 hover:bg-red-700 
                               shadow-md hover:shadow-red-500/30 transition text-xl">
                    🗑️
                </button>

            </div>

        </div>
        @endforeach

    </div>
</div>



    {{-- ========================= LIST VIEW ========================= --}}
    <div id="listView" class="hidden mt-6">

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
                            @if($p->image_path)
                                <img src="{{ asset('storage/' . $p->image_path) }}"
                                     class="w-12 h-12 object-cover rounded">
                            @else
                                <span class="text-gray-500 text-xs italic">Sin imagen</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 font-semibold">{{ $p->producto }}</td>
                        <td class="px-4 py-3">{{ number_format($p->pvp, 2) }} €</td>

                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded text-xs
                                @if($p->stock == 0) bg-red-600
                                @elseif($p->stock < 5) bg-yellow-500 text-black
                                @else bg-green-600
                                @endif">
                                {{ $p->stock }}
                            </span>
                        </td>

                        <td class="px-4 py-3">{{ $p->codigo_barras ?: '—' }}</td>

                        <td class="px-4 py-3 flex justify-center gap-3">

                            <a href="{{ route('tenant.products.edit', $p->id) }}"
                                class="px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                ✏️
                            </a>

                            <button onclick="openDeleteModal({{ $p->id }}, '{{ $p->producto }}')"
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



{{-- ========================== MODAL BORRADO ========================== --}}
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



{{-- ========================== JS ========================== --}}
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

@extends('layouts.tenant')

@section('title', 'Productos')

@section('content')

<div class="p-6 space-y-6">

    {{-- MENSAJE OK --}}
    @if(session('ok'))
        <div class="p-4 rounded-xl border border-emerald-500/40 bg-emerald-700/20 text-emerald-200">
            {{ session('ok') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Productos</h1>
            <p class="text-sm text-gray-400">Gestione su inventario de forma rápida y segura</p>
        </div>

        <div class="flex gap-3">
            <button id="toggleViewBtn"
                    onclick="toggleView()"
                    class="px-4 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-white transition shadow-sm">
                Cambiar a Vista Lista
            </button>

            <a href="{{ route('tenant.products.create') }}"
               class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white transition shadow-sm font-semibold">
                + Nuevo Producto
            </a>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="rounded-2xl border border-gray-700 bg-gray-800/70 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input id="searchInput"
                   type="text"
                   placeholder="Buscar por nombre o código…"
                   class="w-full rounded-xl bg-gray-900 text-white border border-gray-700 px-3 py-2
                          focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select id="categoryFilter"
                    class="w-full rounded-xl bg-gray-900 text-white border border-gray-700 px-3 py-2">
                <option value="">Todas las categorías</option>
                @foreach($products->pluck('categoria')->filter()->unique() as $cat)
                    <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                @endforeach
            </select>

            <select id="stockFilter"
                    class="w-full rounded-xl bg-gray-900 text-white border border-gray-700 px-3 py-2">
                <option value="">Todo el stock</option>
                <option value="out">Sin stock</option>
                <option value="low">Stock bajo (&lt; 5)</option>
            </select>
        </div>
    </div>

    {{-- GRID VIEW --}}
    <div id="gridView" class="w-full flex justify-center">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl w-full">

            @foreach($products as $p)
<div
    class="product-card relative bg-gradient-to-br from-gray-800 via-gray-800 to-gray-900
           border border-gray-700/60 rounded-2xl p-6 flex flex-col
           transition-all duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-blue-500/10"
    data-name="{{ strtolower($p->producto) }}"
    data-code="{{ strtolower($p->codigo_barras ?? '') }}"
    data-category="{{ strtolower($p->categoria ?? '') }}"
    data-stock="{{ $p->stock }}"
>

    {{-- IMAGEN --}}
    <div class="flex justify-center mb-4">
        @if($p->image_path)
            <img src="{{ asset('storage/' . $p->image_path) }}"
                 class="w-28 h-28 object-cover rounded-xl border border-gray-600 shadow-sm bg-white">
        @else
            <div class="w-28 h-28 bg-gray-700/60 rounded-xl
                        flex items-center justify-center text-gray-400 text-xs">
                Sin imagen
            </div>
        @endif
    </div>

    {{-- NOMBRE --}}
    <h2 class="text-lg font-semibold text-white text-center leading-tight">
        {{ $p->producto }}
    </h2>

    {{-- CÓDIGO --}}
    <p class="text-[11px] text-gray-400 text-center mt-1">
        Código: {{ $p->codigo_barras ?: '—' }}
    </p>

    {{-- CATEGORÍA --}}
    @if($p->categoria)
        <div class="flex justify-center mt-3">
            <span class="px-3 py-1 rounded-full text-[11px]
                         bg-gray-700/70 text-gray-300 border border-gray-600/50">
                {{ $p->categoria }}
            </span>
        </div>
    @endif

    {{-- SEPARADOR --}}
    <hr class="border-gray-700/60 my-4">

    {{-- BLOQUE PRECIO --}}
    <div class="flex justify-between items-center text-sm">
        <span class="text-gray-400">PVP</span>
        <span class="font-semibold text-emerald-400 text-base">
            {{ number_format($p->pvp, 2) }} €
        </span>
    </div>

    {{-- STOCK --}}
    <div class="flex justify-center mt-4">
        <span class="px-4 py-1 rounded-full text-xs font-semibold tracking-wide
            @if($p->stock == 0) bg-red-600/90
            @elseif($p->stock < 5) bg-yellow-400 text-black
            @else bg-emerald-600/90
            @endif">
            Stock: {{ $p->stock }}
        </span>
    </div>

    {{-- ACCIONES --}}
    <div class="flex justify-center gap-3 mt-6">

        <button title="Añadir stock"
                onclick="openStockModal({{ $p->id }}, {{ $p->stock }}, '{{ $p->producto }}')"
                class="w-10 h-10 rounded-xl bg-emerald-600 hover:bg-emerald-500
                       flex items-center justify-center transition shadow">
            ➕
        </button>

        <a title="Editar producto"
           href="{{ route('tenant.products.edit', $p->id) }}"
           class="w-10 h-10 rounded-xl bg-indigo-600 hover:bg-indigo-500
                  flex items-center justify-center transition shadow">
            ✏️
        </a>

        <button title="Eliminar producto"
                onclick="openDeleteModal({{ $p->id }}, '{{ $p->producto }}')"
                class="w-10 h-10 rounded-xl bg-red-600 hover:bg-red-500
                       flex items-center justify-center transition shadow">
            🗑️
        </button>

    </div>
</div>
@endforeach


        </div>
    </div>

{{-- LIST VIEW --}}
<div id="listView" class="hidden mt-6">
    <div class="bg-gradient-to-br from-gray-800 to-gray-900
                border border-gray-700 rounded-2xl shadow-xl overflow-hidden">

        <table class="w-full text-left text-sm text-gray-300">
            <thead class="bg-gray-700/60 text-gray-200 text-xs uppercase tracking-wide">
                <tr>
                    <th class="px-4 py-3">Producto</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">PVP</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($products as $p)
                <tr class="border-t border-gray-700 hover:bg-gray-700/40 transition">

                    {{-- PRODUCTO + IMAGEN --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            @if($p->image_path)
                                <img src="{{ asset('storage/' . $p->image_path) }}"
                                     class="w-10 h-10 object-cover rounded-lg border border-gray-600">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-700
                                            flex items-center justify-center text-[10px] text-gray-400">
                                    —
                                </div>
                            @endif

                            <div>
                                <div class="font-semibold text-white leading-tight">
                                    {{ $p->producto }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $p->codigo_barras ?: '—' }}
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- CATEGORÍA --}}
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs
                                     bg-gray-700 text-gray-300">
                            {{ $p->categoria ?: '—' }}
                        </span>
                    </td>

                    {{-- PVP --}}
                    <td class="px-4 py-3 font-semibold text-emerald-400">
                        {{ number_format($p->pvp, 2) }} €
                    </td>

                    {{-- STOCK --}}
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($p->stock == 0) bg-red-600
                            @elseif($p->stock < 5) bg-yellow-400 text-black
                            @else bg-emerald-600
                            @endif">
                            {{ $p->stock }}
                        </span>
                    </td>

                    {{-- ACCIONES --}}
                    <td class="px-4 py-3">
                        <div class="flex justify-center gap-2">

                            <button title="Añadir stock"
                                    onclick="openStockModal({{ $p->id }}, {{ $p->stock }}, '{{ $p->producto }}')"
                                    class="w-8 h-8 rounded-lg bg-emerald-600 hover:bg-emerald-500
                                           flex items-center justify-center transition">
                                ➕
                            </button>

                            <a title="Editar"
                               href="{{ route('tenant.products.edit', $p->id) }}"
                               class="w-8 h-8 rounded-lg bg-indigo-600 hover:bg-indigo-500
                                      flex items-center justify-center transition">
                                ✏️
                            </a>

                            <button title="Eliminar"
                                    onclick="openDeleteModal({{ $p->id }}, '{{ $p->producto }}')"
                                    class="w-8 h-8 rounded-lg bg-red-600 hover:bg-red-500
                                           flex items-center justify-center transition">
                                🗑️
                            </button>

                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


</div>

{{-- MODAL BORRAR --}}
<div id="deleteModal" class="fixed inset-0 hidden justify-center items-center bg-black/60 backdrop-blur-sm z-50">
    <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl w-full max-w-md shadow-xl">
        <h2 class="text-xl font-bold text-white mb-4">Eliminar producto</h2>
        <p id="deleteMessage" class="text-gray-300 mb-6"></p>

        <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-xl bg-gray-600 hover:bg-gray-500 text-white">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-semibold">
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL STOCK --}}
<div id="stockModal" class="fixed inset-0 hidden justify-center items-center bg-black/60 backdrop-blur-sm z-50">
    <div class="bg-gray-800 border border-gray-700 p-6 rounded-2xl w-full max-w-md shadow-xl">
        <h2 class="text-xl font-bold text-white mb-2">Actualizar stock</h2>
        <p id="stockProductName" class="text-gray-400 mb-1"></p>
        <p id="currentStockText" class="text-sm text-gray-400 mb-4"></p>

        <form id="stockForm" method="POST">
            @csrf
            <input type="number" name="quantity" required
                   class="w-full rounded-xl bg-gray-900 text-white border border-gray-700 px-3 py-2 mb-4"
                   placeholder="Ej: 10 o -5">

            <div class="flex justify-end gap-3">
                <button type="button"
                        onclick="closeStockModal()"
                        class="px-4 py-2 rounded-xl bg-gray-600 hover:bg-gray-500 text-white">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500
                               text-white font-semibold">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleView() {
    const grid = document.getElementById('gridView');
    const list = document.getElementById('listView');
    const btn = document.getElementById('toggleViewBtn');

    if (grid.classList.contains('hidden')) {
        grid.classList.remove('hidden');
        list.classList.add('hidden');
        btn.textContent = 'Cambiar a Vista Lista';
    } else {
        grid.classList.add('hidden');
        list.classList.remove('hidden');
        btn.textContent = 'Cambiar a Vista Grid';
    }
}


function openDeleteModal(id, name) {
    document.getElementById('deleteForm').action = `/tenant/products/${id}`;
    document.getElementById('deleteMessage').textContent =
        `¿Eliminar "${name}"? Esta acción no se puede deshacer.`;
    document.getElementById('deleteModal').classList.remove('hidden');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function openStockModal(id, stock, name) {
    document.getElementById('stockProductName').textContent = name;
    document.getElementById('currentStockText').textContent = `Stock actual: ${stock}`;
    document.getElementById('stockForm').action =
        "{{ url('/') }}/tenant/products/" + id + "/stock";
    document.getElementById('stockModal').classList.remove('hidden');
}
function closeStockModal() {
    document.getElementById('stockModal').classList.add('hidden');
}
</script>

@endsection

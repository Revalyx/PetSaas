@extends('layouts.tenant')

@section('title', 'Productos')

@section('content')

<div
    class="p-6 space-y-6"
    x-data="{
        search: '',
        category: '',
        stockFilter: '',
        view: localStorage.getItem('products_view') ?? 'grid',

        products: @js($products->map(fn($p) => [
            'id' => $p->id,
            'producto' => $p->producto,
            'codigo' => $p->codigo_barras,
            'categoria' => $p->categoria,
            'pvp' => number_format($p->pvp, 2),
            'stock' => $p->stock,
            'image' => $p->image_path,
        ])),

        totalCount: 0,
        shownCount: 0,

        init() {
            this.totalCount = this.products.length
            this.shownCount = this.products.length

            this.$watch('filtered.length', value => {
                this.animateCount(this.shownCount, value, v => this.shownCount = v)
            })

            localStorage.setItem('products_view', this.view)
        },

        animateCount(from, to, cb) {
            const start = performance.now()
            const duration = 250
            const step = now => {
                const p = Math.min((now - start) / duration, 1)
                cb(Math.round(from + (to - from) * p))
                if (p < 1) requestAnimationFrame(step)
            }
            requestAnimationFrame(step)
        },

        stockClasses(stock) {
            if (stock === 0) return 'bg-red-600 text-white animate-pulse'
            if (stock < 5) return 'bg-amber-500 text-black'
            if (stock < 10) return 'bg-teal-300 text-teal-900'
            return 'bg-teal-600 text-white'
        },

        stockTitle(stock) {
            if (stock === 0) return 'Sin stock. Reponer urgentemente'
            if (stock < 5) return 'Stock bajo. Conviene reponer'
            if (stock < 10) return 'Stock medio'
            return 'Stock correcto'
        },

        get filtered() {
            return this.products.filter(p => {
                const q = this.search.toLowerCase()
                if (q && !`${p.producto} ${p.codigo ?? ''}`.toLowerCase().includes(q)) return false
                if (this.category && (p.categoria ?? '').toLowerCase() !== this.category) return false
                if (this.stockFilter === 'out' && p.stock !== 0) return false
                if (this.stockFilter === 'low' && p.stock >= 5) return false
                return true
            })
        }
    }"
>

@if(session('ok'))
    <div class="p-4 rounded-xl border border-teal-500/40 bg-teal-100 text-teal-800
                dark:bg-teal-900/30 dark:text-teal-200 dark:border-teal-500/30">
        {{ session('ok') }}
    </div>
@endif

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 dark:text-slate-100 tracking-tight">
            Productos
        </h1>
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Gestión de inventario
        </p>
    </div>

    <div class="flex gap-3">
        <button
            @click="view = view === 'grid' ? 'list' : 'grid'; localStorage.setItem('products_view', view)"
            class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800
                   dark:bg-slate-700 dark:hover:bg-slate-600 dark:text-slate-100 transition shadow-sm">
            <span x-text="view === 'grid' ? 'Cambiar a Vista Lista' : 'Cambiar a Vista Columnas'"></span>
        </button>

        <a href="{{ route('tenant.products.create') }}"
           class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700
                  text-white transition shadow-sm font-semibold">
            + Nuevo Producto
        </a>
    </div>
</div>

<div class="rounded-2xl border border-slate-200 dark:border-slate-700
            bg-white dark:bg-slate-800 p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <input type="text" x-model="search"
               placeholder="Buscar por nombre o código…"
               class="w-full rounded-xl bg-white dark:bg-slate-700
                      text-slate-800 dark:text-slate-100
                      border border-slate-300 dark:border-slate-600
                      px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500">

        <select x-model="category"
                class="w-full rounded-xl bg-white dark:bg-slate-700
                       text-slate-800 dark:text-slate-100
                       border border-slate-300 dark:border-slate-600 px-3 py-2">
            <option value="">Todas las categorías</option>
            @foreach($products->pluck('categoria')->filter()->unique() as $cat)
                <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
            @endforeach
        </select>

        <select x-model="stockFilter"
                class="w-full rounded-xl bg-white dark:bg-slate-700
                       text-slate-800 dark:text-slate-100
                       border border-slate-300 dark:border-slate-600 px-3 py-2">
            <option value="">Todo el stock</option>
            <option value="out">Sin stock</option>
            <option value="low">Stock bajo (&lt; 5)</option>
        </select>

    </div>
</div>

<div class="text-sm text-slate-500 dark:text-slate-400">
    Mostrando
    <span class="font-semibold text-slate-700 dark:text-slate-200" x-text="shownCount"></span>
    productos
</div>

<div x-show="view === 'grid'" class="w-full flex justify-center">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl w-full">

        <template x-for="p in filtered" :key="p.id">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                        rounded-2xl p-6 flex flex-col transition hover:shadow-lg">

                <div class="flex justify-center mb-4">
                    <template x-if="p.image">
                        <img :src="`/storage/${p.image}`"
                             class="w-28 h-28 object-cover rounded-xl border border-slate-300 dark:border-slate-600">
                    </template>
                    <template x-if="!p.image">
                        <div class="w-28 h-28 rounded-xl bg-slate-100 dark:bg-slate-700
                                    flex items-center justify-center text-slate-400 text-xs">
                            Sin imagen
                        </div>
                    </template>
                </div>

                <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100 text-center"
                    x-text="p.producto"></h2>

                <p class="text-xs text-slate-500 dark:text-slate-400 text-center mt-1"
                   x-text="'Código: ' + p.codigo ?? '—'"></p>

                <div class="flex justify-center mt-2">
                    <span class="px-3 py-1 rounded-full text-[11px] font-semibold
                                 bg-teal-100 text-teal-800
                                 dark:bg-teal-900/40 dark:text-teal-300"
                          x-text="p.categoria ?? 'Sin categoría'"></span>
                </div>

                <hr class="border-slate-200 dark:border-slate-700 my-4">

                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">PVP</span>
                    <span class="font-semibold text-teal-600"
                          x-text="`${p.pvp} €`"></span>
                </div>

                <div class="flex justify-between text-sm mt-2">
                    <span class="font-semibold"
                          :class="{
                            'text-red-600': p.stock === 0,
                            'text-amber-600': p.stock > 0 && p.stock < 5,
                            'text-teal-600': p.stock >= 5
                          }">
                    </span>
                </div>

                <div class="flex justify-center mt-4">
                    <span class="px-4 py-1 rounded-full text-xs font-semibold"
                          :class="stockClasses(p.stock)">
                        Stock: <span x-text="p.stock"></span>
                    </span>
                </div>

                <div class="flex justify-center gap-3 mt-6">
                    <button
                                    onclick="openStockModal(this.dataset.id, this.dataset.stock, this.dataset.name)"
                                    :data-id="p.id"
                                    :data-stock="p.stock"
                                    :data-name="p.producto"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                        bg-white text-teal-600 border border-slate-200
                                        hover:bg-teal-600 hover:text-white transition">
                                    ➕
                                </button>

                                <a
                                    :href="`/tenant/products/${p.id}/edit`"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                        bg-white text-slate-600 border border-slate-200
                                        hover:bg-slate-600 hover:text-white transition">
                                    ✏️
                                </a>

                    <button onclick="openDeleteModal(this.dataset.id, this.dataset.name)"
                            :data-id="p.id" :data-name="p.producto"
                            class="w-8 h-8 flex items-center justify-center rounded-lg
                                        bg-white text-slate-600 border border-slate-200
                                        hover:bg-slate-600 hover:text-white transition">🗑️</button>
                </div>

            </div>
        </template>
    </div>
</div>

<div x-show="view === 'list'" class="mt-6">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                rounded-2xl shadow-xl overflow-hidden">

        <table class="w-full text-left text-sm text-slate-700 dark:text-slate-300">
            <thead class="bg-slate-100 dark:bg-slate-700 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3">Imagen</th>
                    <th class="px-4 py-3">Producto</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">PVP</th>
                    <th class="px-4 py-3">Stock</th>
                    <th class="px-4 py-3 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                <template x-for="p in filtered" :key="p.id">
                    <tr class="border-t border-slate-200 dark:border-slate-700
                               hover:bg-slate-50 dark:hover:bg-slate-700/40 transition">

                        <td class="px-4 py-3">
                            <template x-if="p.image">
                                <img :src="`/storage/${p.image}`"
                                     class="w-10 h-10 object-cover rounded-lg
                                            border border-slate-300 dark:border-slate-600">
                            </template>
                            <template x-if="!p.image">
                                <div class="w-10 h-10 rounded-lg bg-slate-200 dark:bg-slate-700
                                            flex items-center justify-center text-[10px] text-slate-400">
                                    —
                                </div>
                            </template>
                        </td>

                        <td class="px-4 py-3 font-semibold" x-text="p.producto"></td>
                        <td class="px-4 py-3" x-text="p.categoria ?? '—'"></td>
                        <td class="px-4 py-3 text-teal-600" x-text="`${p.pvp} €`"></td>
                        


                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold"
                                  :class="stockClasses(p.stock)">
                                <span x-text="p.stock"></span>
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex justify-center gap-2">
                                <button
                                    onclick="openStockModal(this.dataset.id, this.dataset.stock, this.dataset.name)"
                                    :data-id="p.id"
                                    :data-stock="p.stock"
                                    :data-name="p.producto"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                        bg-white text-teal-600 border border-slate-200
                                        hover:bg-teal-600 hover:text-white transition">
                                    ➕
                                </button>

                                <a
                                    :href="`/tenant/products/${p.id}/edit`"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg
                                        bg-white text-slate-600 border border-slate-200
                                        hover:bg-slate-600 hover:text-white transition">
                                    ✏️
                                </a>


                                <button onclick="openDeleteModal(this.dataset.id, this.dataset.name)"
                                        :data-id="p.id" :data-name="p.producto"
                                        class="w-8 h-8 rounded-lg bg-white text-red-600 border border-slate-200
                                               hover:bg-red-600 hover:text-white transition">🗑️</button>
                            </div>
                        </td>

                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

</div>

<div id="deleteModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                p-6 rounded-2xl w-full max-w-md shadow-2xl">
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-4">Eliminar producto</h2>
        <p id="deleteMessage" class="text-slate-600 dark:text-slate-300 mb-6"></p>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300
                               dark:bg-slate-600 dark:hover:bg-slate-500 dark:text-white">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-semibold">
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<div id="stockModal"
     class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                p-6 rounded-2xl w-full max-w-md shadow-2xl">
        <h2 class="text-xl font-bold text-slate-800 dark:text-slate-100 mb-2">Actualizar stock</h2>
        <p id="stockProductName" class="text-slate-500 dark:text-slate-400 mb-1"></p>
        <p id="currentStockText" class="text-sm text-slate-500 dark:text-slate-400 mb-4"></p>
        <form id="stockForm" method="POST">
            @csrf
            <input type="number" name="quantity" required
                   class="w-full rounded-xl bg-white dark:bg-slate-700
                          border border-slate-300 dark:border-slate-600
                          px-3 py-2 mb-4">
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeStockModal()"
                        class="px-4 py-2 rounded-xl bg-slate-200 hover:bg-slate-300
                               dark:bg-slate-600 dark:hover:bg-slate-500 dark:text-white">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white font-semibold">
                    Actualizar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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

<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>ARIS · Panel</title>

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        (() => {
            const html = document.documentElement;
            const saved = localStorage.getItem("petsaas_theme");
            const systemDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

            if (saved === "dark") html.classList.add("dark");
            else if (saved === "light") html.classList.remove("dark");
            else html.classList.toggle("dark", systemDark);

            window.toggleTheme = () => {
                const isDark = html.classList.contains("dark");
                html.classList.toggle("dark", !isDark);
                localStorage.setItem("petsaas_theme", isDark ? "light" : "dark");
            };
        })();
    </script>

    <script>
        tailwind.config = {
            darkMode: "class"
        }
    </script>
</head>

<body class="h-screen flex bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100">

<!-- SIDEBAR -->
<aside class="w-64 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 flex flex-col p-6">

    <!-- LOGO -->
    <div class="flex items-center gap-3 mb-10">
        <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" class="w-9 h-9">
        <span class="text-xl font-bold">ARIS</span>
    </div>

    <!-- NAV -->
    <nav class="flex flex-col gap-1 text-sm">

        @php
            $active = 'bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 font-semibold';
        @endphp

        <a href="{{ route('tenant.dashboard') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.dashboard') ? $active : '' }}">
            📊 Panel de Control
        </a>

        <a href="{{ route('tenant.clientes.index') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.clientes.*') ? $active : '' }}">
            👤 Clientes
        </a>

        <a href="{{ route('tenant.mascotas.index') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.mascotas.*') ? $active : '' }}">
            🐕 Mascotas
        </a>

        <a href="{{ route('tenant.appointments.index') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.appointments.*') ? $active : '' }}">
            📖 Citas
        </a>

        <!-- 🔴 CALENDARIO (RESTAURADO) -->
        <a href="{{ route('tenant.appointments.calendar') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.appointments.calendar') ? $active : '' }}">
            🗓️ Calendario
        </a>

        <a href="{{ route('tenant.products.index') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.products.*') ? $active : '' }}">
            📦 Productos
        </a>

        <a href="{{ route('tenant.sales.index') }}"
           class="px-4 py-2.5 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700
           {{ request()->routeIs('tenant.sales.*') ? $active : '' }}">
            💳 Ventas
        </a>

    </nav>

    <!-- FOOTER -->
    <div class="mt-auto pt-6 border-t border-slate-200 dark:border-slate-700 space-y-3">

        <button onclick="toggleTheme()"
            class="w-full px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-sm">
            🌓 Cambiar tema
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">
                Cerrar sesión
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<main class="flex-1 overflow-y-auto p-10">
    <div class="max-w-6xl mx-auto space-y-10">

        @if(!isset($hide_header))
            <header class="pb-4 border-b border-slate-200 dark:border-slate-700">
                <h1 class="text-3xl font-bold">@yield('title', 'Panel')</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                    {{ auth()->user()->name }} · {{ auth()->user()->tenant->name }}
                </p>
            </header>
        @endif

        @yield('content')

    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>

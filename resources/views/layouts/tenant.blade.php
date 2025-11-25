<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>PetSaaS - Panel de Empresa</title>

    <!-- Tailwind -->
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

    <script> window.tailwind = { config: { darkMode: "class" } } </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade { animation: fadeIn .35s ease-out forwards; }
    </style>
</head>

<body class="h-screen flex bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white dark:bg-gray-900 shadow-2xl flex flex-col p-6 border-r border-gray-200 dark:border-gray-800">
        
        <div class="flex items-center gap-3 mb-10">
            <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png" class="w-10 h-10 opacity-90 drop-shadow">
            <span class="text-xl font-bold tracking-tight">PetSaaS</span>
        </div>

        <nav class="flex flex-col gap-1 text-gray-700 dark:text-gray-300">

            <a href="{{ route('tenant.dashboard') }}"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('tenant.dashboard') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
               📊 Dashboard
            </a>

            <a href="{{ route('tenant.clientes.index') }}"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('tenant.clientes.*') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
               👤 Listado de Clientes
            </a>

            <a href="{{ route('tenant.mascotas.index') }}"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800 {{ request()->routeIs('tenant.mascotas.*') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
               🐕 Mascotas
            </a>

            <a href="{{ route('tenant.appointments.index') }}"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 
               hover:bg-gray-100 dark:hover:bg-gray-800
               {{ request()->routeIs('tenant.appointments.index')
               || request()->routeIs('tenant.appointments.create')
               || request()->routeIs('tenant.appointments.edit')
               ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm'
               : '' }}">
               🗂️ Citas
            </a>

            <a href="{{ route('tenant.appointments.calendar') }}"
            class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 
                hover:bg-gray-100 dark:hover:bg-gray-800
                {{ request()->routeIs('tenant.appointments.calendar') 
                    ? 'bg-blue-600 text-white font-semibold shadow-md dark:bg-blue-700 dark:text-white' : '' }}">
                📆 Calendario
            </a>

        </nav>

        <div class="mt-auto pt-6 flex flex-col gap-3 border-t border-gray-200 dark:border-gray-800">
            <button onclick="toggleTheme()" class="w-full bg-gray-200 dark:bg-gray-800 px-4 py-2.5 rounded-xl shadow-sm flex justify-center items-center gap-2">
                🌓 Cambiar tema
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full bg-red-500 text-white px-4 py-2.5 rounded-xl hover:bg-red-600 shadow-sm">
                    🔓 Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>

    <!-- CONTENIDO -->
    <main class="flex-1 fade overflow-y-auto h-full p-10 
                bg-gradient-to-br from-gray-100 via-gray-100 to-gray-200
                dark:from-gray-950 dark:via-gray-900 dark:to-gray-900">

        <div class="max-w-5xl mx-auto space-y-10">

            @if(!isset($hide_header))
                <div class="pb-4 border-b border-gray-200/40 dark:border-gray-700/40">
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        @yield('title', 'Panel de Empresa')
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Bienvenido, <span class="font-semibold">{{ auth()->user()->name }}</span><br>
                        Empresa: <span class="font-semibold text-orange-500">{{ auth()->user()->tenant->name }}</span>
                    </p>
                </div>
            @endif

            @yield('content')

        </div>
    </main>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>
</html>

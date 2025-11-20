<!DOCTYPE html>
<html lang="es" class="min-h-screen">

<head>
    <meta charset="UTF-8">
    <title>PetSaaS - Superadmin</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade { animation: fadeIn .35s ease-out forwards; }
    </style>

    <!-- Tema oscuro antes del render -->
    <script>
        (() => {
            const saved = localStorage.getItem('petsaas_dark');
            if (saved === 'true') document.documentElement.classList.add('dark');
        })();
    </script>
</head>

<body class="min-h-screen flex bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white dark:bg-gray-900 shadow-xl flex flex-col p-6
                   border-r border-gray-200 dark:border-gray-800 min-h-screen">

        <!-- LOGO -->
        <div class="flex items-center gap-3 mb-10">
            <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png"
                 class="w-10 h-10 opacity-90">
            <span class="text-xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">
                PetSaaS
            </span>
        </div>

        <!-- NAV -->
        <nav class="flex flex-col gap-1 text-gray-700 dark:text-gray-300">

            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-500 mb-2">
                Navegación
            </div>

            <a href="{{ route('superadmin.dashboard') }}"
               class="px-4 py-2 rounded-lg transition flex items-center gap-2
                      hover:bg-orange-100 dark:hover:bg-gray-800
                      {{ request()->routeIs('superadmin.dashboard') ? 'bg-orange-200/80 dark:bg-gray-800 font-semibold' : '' }}">
                🏠 <span>Dashboard</span>
            </a>

            <a href="{{ route('superadmin.health') }}"
                class="px-4 py-2 rounded-lg transition flex items-center gap-2
                       hover:bg-orange-100 dark:hover:bg-gray-800
                       {{ request()->routeIs('superadmin.health') ? 'bg-orange-200/80 dark:bg-gray-800 font-semibold' : '' }}">
                🛡️ <span>Salud del sistema</span>
            </a>

            <a href="{{ route('superadmin.tenants.create') }}"
               class="px-4 py-2 rounded-lg transition flex items-center gap-2
                      hover:bg-orange-100 dark:hover:bg-gray-800
                      {{ request()->routeIs('superadmin.tenants.create') ? 'bg-orange-200/80 dark:bg-gray-800 font-semibold' : '' }}">
                ➕ <span>Crear Empresa</span>
            </a>

            <a href="{{ route('superadmin.users.create') }}"
               class="px-4 py-2 rounded-lg transition flex items-center gap-2
                      hover:bg-orange-100 dark:hover:bg-gray-800
                      {{ request()->routeIs('superadmin.users.create') ? 'bg-orange-200/80 dark:bg-gray-800 font-semibold' : '' }}">
                👤 <span>Usuarios</span>
            </a>

            <a href="#"
               class="px-4 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-800
                      transition flex items-center gap-2">
                ⚙️ <span>Ajustes</span>
            </a>
        </nav>

        <!-- FOOTER -->
        <div class="mt-auto pt-6 flex flex-col gap-3 border-t border-gray-200 dark:border-gray-800">

            <!-- Tema -->
            <button id="darkToggle"
                class="w-full bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                       px-4 py-2 rounded-lg hover:opacity-80 transition flex items-center justify-center gap-2">
                🌓 <span>Tema</span>
            </button>

            <!-- Logout seguro -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition flex items-center justify-center gap-2">
                    🔓 Cerrar Sesión
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENIDO -->
    <main class="flex-1 min-h-screen p-8 lg:p-10 fade
                 bg-gradient-to-br from-gray-100 via-gray-100 to-gray-200
                 dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-900 overflow-visible">

        <div class="max-w-7xl mx-auto space-y-10">

            <!-- Top Bar -->
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        @yield('title', 'Superadmin')
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Panel de control general de PetSaaS.
                    </p>
                </div>

                <div class="hidden sm:flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                 bg-orange-100 dark:bg-orange-900/40 
                                 text-orange-700 dark:text-orange-300">
                        Superadmin
                    </span>
                    <div class="h-9 w-px bg-gray-300 dark:bg-gray-700"></div>
                    <div class="flex items-center gap-2">
                        <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-orange-400 to-pink-500"></div>
                        <div class="text-right">
                            <p class="text-xs font-semibold text-gray-200">Admin</p>
                            <p class="text-xs text-gray-400">admin@petsaas.test</p>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </div>
    </main>

    <script>
        document.getElementById('darkToggle').addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('petsaas_dark', html.classList.contains('dark'));
        });
    </script>

</body>
</html>

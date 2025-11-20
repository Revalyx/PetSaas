<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>PetSaaS - Panel de Empresa</title>

    <!-- Tailwind CDN -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- FORZAR CONFIG DESPUÉS DE CARGAR TAILWIND -->
<script>
    if (!window.tailwind) window.tailwind = {};

    window.tailwind.config = {
        darkMode: "class",
    };
</script>



    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade { animation: fadeIn .35s ease-out forwards; }

        /* Drawer visible */
        #settingsDrawer.show {
            transform: translateX(0);
        }
    </style>

    <script>
(() => {
    const html = document.documentElement;

    // 1) leer preferencia guardada
    const saved = localStorage.getItem("petsaas_theme");

    // 2) determinar modo sistema
    const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

    // 3) aplicar modo inicial
    if (saved === "dark") {
        html.classList.add("dark");
    } 
    else if (saved === "light") {
        html.classList.remove("dark");
    }
    else {
        // sin preferencia guardada → usar sistema
        if (systemPrefersDark) html.classList.add("dark");
        else html.classList.remove("dark");
    }

    // 4) toggle manual
    window.toggleTheme = () => {
        const isDark = html.classList.contains("dark");

        if (isDark) {
            html.classList.remove("dark");
            localStorage.setItem("petsaas_theme", "light");
        } else {
            html.classList.add("dark");
            localStorage.setItem("petsaas_theme", "dark");
        }
    };
})();
</script>

</head>

<body class="h-full flex bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white dark:bg-gray-900 shadow-2xl flex flex-col p-6 border-r border-gray-200 dark:border-gray-800">

        <!-- LOGO -->
        <div class="flex items-center gap-3 mb-10">
            <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png"
                 class="w-10 h-10 opacity-90 drop-shadow">
            <span class="text-xl font-bold tracking-tight">PetSaaS</span>
        </div>

        <nav class="flex flex-col gap-1 text-gray-700 dark:text-gray-300">

            <div class="text-[11px] uppercase tracking-[0.2em] text-gray-500 dark:text-gray-500 mb-2">
                Menú principal
            </div>

            <!-- Dashboard -->
            <a href="{{ route('tenant.dashboard') }}"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2
                      hover:bg-gray-100 dark:hover:bg-gray-800
                      {{ request()->routeIs('tenant.dashboard') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
                📊 <span>Dashboard</span>
            </a>

            <!-- Clientes -->
            <a href="{{ route('tenant.clientes.index') }}"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2
                      hover:bg-gray-100 dark:hover:bg-gray-800
                      {{ request()->routeIs('tenant.clientes.*') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
                👤 <span>Listado de Clientes</span>
            </a>

            <!-- Mascotas -->
            <a href="#"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                🐾 <span>Mascotas</span>
            </a>

            <!-- Citas -->
            <a href="#"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                📅 <span>Citas</span>
            </a>

            <!-- Ajustes -->
            <a href="#"
               class="px-4 py-2.5 rounded-xl transition flex items-center gap-2 hover:bg-gray-100 dark:hover:bg-gray-800">
                ⚙️ <span>Ajustes</span>
            </a>

        </nav>

        <!-- FOOTER -->
        <div class="mt-auto pt-6 flex flex-col gap-3 border-t border-gray-200 dark:border-gray-800">

            <!-- Tema -->
            <button onclick="toggleTheme()"
                class="w-full bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                       px-4 py-2.5 rounded-xl hover:opacity-90 transition flex items-center justify-center gap-2 shadow-sm">
                🌓 <span>Cambiar tema</span>
            </button>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full text-center bg-red-500 text-white px-4 py-2.5 rounded-xl hover:bg-red-600 transition shadow-sm">
                    🔓 Cerrar Sesión
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 p-10 fade
                 bg-gradient-to-br from-gray-100 via-gray-100 to-gray-200
                 dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-900">

        <div class="max-w-6xl mx-auto space-y-10">

            <!-- Topbar / Encabezado -->
            <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-200/40 dark:border-gray-700/40">

                <div class="space-y-1">
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                        @yield('breadcrumb', 'Dashboard')
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        @yield('title', 'Panel de Empresa')
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                        Bienvenido, <span class="font-semibold text-gray-700 dark:text-gray-200">{{ auth()->user()->name }}</span><br>
                        Empresa: <span class="font-semibold text-orange-500">{{ auth()->user()->tenant->name }}</span>
                    </p>
                </div>

                <!-- Avatar -->
                <div class="relative">
                    <button id="avatarButton"
                            class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-pink-500
                                   flex items-center justify-center text-sm font-bold text-white shadow-md">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </button>

                    <!-- Dropdown -->
                    <div id="avatarMenu"
                         class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl shadow-xl py-2 text-sm z-30">
                        <div class="px-3 pb-2 border-b border-gray-200 dark:border-gray-800 mb-1">
                            <p class="font-semibold text-gray-800 dark:text-gray-100 text-xs">Conectado como</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <a href="#" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">Perfil</a>
                        <a href="#" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">Preferencias</a>

                        <button onclick="document.querySelector('form[action=\'{{ route('logout') }}\']').submit();"
                                class="w-full text-left px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800 text-red-500">
                            Cerrar sesión
                        </button>
                    </div>
                </div>

            </div>

            @yield('content')

        </div>
    </main>

    <script>
        // Toggle menú avatar
        const avatarBtn = document.getElementById('avatarButton');
        const avatarMenu = document.getElementById('avatarMenu');

        avatarBtn.addEventListener('click', () => {
            avatarMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!avatarBtn.contains(e.target) && !avatarMenu.contains(e.target)) {
                avatarMenu.classList.add('hidden');
            }
        });
    </script>

</body>
</html>

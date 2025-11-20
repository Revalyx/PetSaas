<!DOCTYPE html>
<html lang="es" class="min-h-screen">
<head>
    <meta charset="UTF-8">
    <title>PetSaaS - Superadmin</title>

    <!-- Tailwind config BEFORE CDN -->
    <script>
        tailwind = {
            config: {
                darkMode: 'class',
            }
        }
    </script>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- ⚠️ FORZAR CONFIG POST-CDN (IMPRESCINDIBLE) -->
    <script>
        if (!window.tailwind) window.tailwind = {};
        window.tailwind.config = {
            darkMode: "class",
        };
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade { animation: fadeIn .35s ease-out forwards; }
    </style>

    <!-- ⚫⚪ SISTEMA DE TEMA — IGUAL QUE TENANT -->
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
                localStorage.setItem("petsaas_theme", !isDark ? "dark" : "light");
            };
        })();
    </script>
</head>

<body class="min-h-screen flex bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white dark:bg-gray-900 shadow-2xl flex flex-col p-6
                   border-r border-gray-200 dark:border-gray-800 min-h-screen">

        <div class="flex items-center gap-3 mb-10">
            <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png"
                 class="w-10 h-10 opacity-90 drop-shadow">
            <span class="text-xl font-bold tracking-tight">PetSaaS</span>
        </div>

        <nav class="flex flex-col gap-1 text-gray-700 dark:text-gray-300">

            <div class="text-[11px] uppercase tracking-wider text-gray-500 dark:text-gray-500 mb-2">
                Navegación
            </div>

            <a href="{{ route('superadmin.dashboard') }}"
                class="px-4 py-2.5 rounded-xl transition flex items-center gap-2
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       {{ request()->routeIs('superadmin.dashboard') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
                🏠 Dashboard
            </a>

            <a href="{{ route('superadmin.health') }}"
                class="px-4 py-2.5 rounded-xl transition flex items-center gap-2
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       {{ request()->routeIs('superadmin.health') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
                🛡️ Salud del sistema
            </a>

            <a href="{{ route('superadmin.tenants.create') }}"
                class="px-4 py-2.5 rounded-xl transition flex items-center gap-2
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       {{ request()->routeIs('superadmin.tenants.create') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
                ➕ Crear Empresa
            </a>

            <a href="{{ route('superadmin.users.create') }}"
                class="px-4 py-2.5 rounded-xl transition flex items-center gap-2
                       hover:bg-gray-100 dark:hover:bg-gray-800
                       {{ request()->routeIs('superadmin.users.create') ? 'bg-gray-100/80 dark:bg-gray-800/80 font-semibold shadow-sm' : '' }}">
                👤 Usuarios
            </a>

            <a href="#" class="px-4 py-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                ⚙️ Ajustes
            </a>
        </nav>

        <div class="mt-auto pt-6 flex flex-col gap-3 border-t border-gray-200 dark:border-gray-800">

            <button onclick="toggleTheme()"
                class="w-full bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200 
                       px-4 py-2.5 rounded-xl hover:opacity-90 transition flex items-center justify-center gap-2 shadow-sm">
                🌓 Cambiar tema
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-red-500 text-white px-4 py-2.5 rounded-xl hover:bg-red-600 transition shadow-sm">
                    🔓 Cerrar Sesión
                </button>
            </form>

        </div>
    </aside>

    <main class="flex-1 min-h-screen p-10 fade
                 bg-gradient-to-br from-gray-100 via-gray-100 to-gray-200
                 dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-900">

        <div class="max-w-7xl mx-auto space-y-10">

            <div class="flex items-start justify-between gap-4 pb-4 border-b border-gray-200/50 dark:border-gray-700/50">

                <div class="space-y-1">
                    <div class="text-xs uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500">
                        Superadmin Panel
                    </div>

                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                        @yield('title', 'Superadmin')
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Gestión global del sistema PetSaaS.
                    </p>
                </div>

                <div class="relative">
                    <button id="avatarButton"
                            class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-pink-500
                                   flex items-center justify-center text-sm font-bold text-white shadow-md">
                        A
                    </button>

                    <div id="avatarMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800
                               rounded-xl shadow-xl py-2 text-sm z-30">

                        <div class="px-3 pb-2 border-b border-gray-200 dark:border-gray-800 mb-1">
                            <p class="font-semibold text-gray-800 dark:text-gray-100 text-xs">Conectado como</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">admin@petsaas.test</p>
                        </div>

                        <a href="#" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">Perfil</a>
                        <a href="#" class="block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-800">Preferencias</a>

                        <button onclick="document.querySelector('form[action=\'{{ route('logout') }}\']').submit();"
                                class="block w-full text-left px-3 py-2 text-red-500 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Cerrar sesión
                        </button>
                    </div>
                </div>

            </div>

            @yield('content')

        </div>
    </main>

    <script>
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

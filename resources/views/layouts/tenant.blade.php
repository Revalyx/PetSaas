<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <title>PetSaaS - Panel de Empresa</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .fade { animation: fadeIn .4s ease-out forwards; }
    </style>

    <script>
        (() => {
            const saved = localStorage.getItem('petsaas_dark');
            if (saved === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="h-full flex bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white dark:bg-gray-900 shadow-xl flex flex-col p-6 border-r border-gray-200 dark:border-gray-800">

        <!-- LOGO -->
        <div class="flex items-center gap-3 mb-10">
            <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png"
                 class="w-10 h-10 opacity-90">
            <span class="text-xl font-bold tracking-tight">
                PetSaaS
            </span>
        </div>

        <nav class="flex flex-col gap-1 text-gray-700 dark:text-gray-300">

            <div class="text-xs uppercase tracking-wider text-gray-500 dark:text-gray-500 mb-2">
                Menú
            </div>

            <!-- Dashboard -->
            <a href="{{ route('tenant.dashboard') }}"
               class="px-4 py-2 rounded-lg transition flex items-center gap-2
                      hover:bg-orange-100 dark:hover:bg-gray-800
                      {{ request()->routeIs('tenant.dashboard') ? 'bg-orange-200/70 dark:bg-gray-800 font-semibold' : '' }}">
                📊 <span>Dashboard</span>
            </a>

            <!-- Clientes -->
            <a href="#" 
               class="px-4 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-800 transition flex items-center gap-2">
                🐶 <span>Clientes</span>
            </a>

            <!-- Mascotas -->
            <a href="#"
               class="px-4 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-800 transition flex items-center gap-2">
                🐾 <span>Mascotas</span>
            </a>

            <!-- Citas -->
            <a href="#"
               class="px-4 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-800 transition flex items-center gap-2">
                📅 <span>Citas</span>
            </a>

            <!-- Ajustes -->
            <a href="#"
               class="px-4 py-2 rounded-lg hover:bg-orange-100 dark:hover:bg-gray-800 transition flex items-center gap-2">
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

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-center bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    🔓 Cerrar Sesión
                </button>
            </form>
        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 p-8 fade
                 bg-gradient-to-br from-gray-100 via-gray-100 to-gray-200
                 dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-900">

        <div class="max-w-6xl mx-auto space-y-8">

            <!-- Encabezado -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight">
                        @yield('title', 'Panel de Empresa')
                    </h1>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Bienvenido, {{ auth()->user()->name }}  
                        <br>
                        Empresa: <span class="font-semibold text-orange-500">{{ auth()->user()->tenant->name }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-orange-400 to-pink-500"></div>
                </div>
            </div>

            @yield('content')

        </div>

    </main>

    <script>
        const btn = document.getElementById('darkToggle');
        btn.addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('petsaas_dark', html.classList.contains('dark'));
        });
    </script>

</body>
</html>

<!DOCTYPE html>
<html lang="es" class="h-full" >
<head>
    <meta charset="UTF-8">
    <title>Login - PetSaaS</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Heroicons -->
    <script src="https://unpkg.com/heroicons@2.0.18/dist/heroicons.js"></script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
    </style>
</head>

<body class="h-full">

<div class="min-h-screen flex">

    <!-- ============================
         PANEL IZQUIERDO (imagen)
         ============================ -->
    <div class="hidden md:flex w-1/2 bg-gradient-to-br from-orange-400 via-amber-300 to-yellow-200 
                items-center justify-center relative overflow-hidden">

        <img src="https://cdn-icons-png.flaticon.com/512/194/194279.png"
             class="w-72 opacity-80 fade-in" 
             style="animation-delay: .3s">

        <div class="absolute bottom-6 text-white text-sm opacity-80">
            © {{ date('Y') }} PetSaaS — Gestión cloud para peluquerías caninas
        </div>
    </div>


    <!-- ============================
         PANEL DERECHO (formulario)
         ============================ -->
    <div class="flex w-full md:w-1/2 bg-gray-50 dark:bg-gray-900 
                items-center justify-center p-10">

        <div class="w-full max-w-md fade-in bg-white dark:bg-gray-800 
                    shadow-xl rounded-2xl p-10">

            <!-- Logo -->
            <div class="flex justify-center mb-5">
                <img src="https://cdn-icons-png.flaticon.com/512/616/616408.png"
                     class="w-16 h-16 opacity-90">
            </div>

            <h2 class="text-3xl font-extrabold text-center 
                       text-gray-800 dark:text-white mb-6">
                Bienvenido a PetSaaS
            </h2>

            <!-- Error -->
            @if($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 border 
                            border-red-300 rounded-lg text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- FORM -->
            <form method="POST" action="/login" class="space-y-5">
                @csrf

                <!-- EMAIL -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Email
                    </label>
                    <div class="relative">
                        <input type="email" name="email" required
                            class="w-full pl-11 pr-4 py-3 bg-gray-100 dark:bg-gray-700
                                   border border-gray-300 dark:border-gray-600 
                                   rounded-lg focus:ring-2 focus:ring-orange-500 
                                   focus:border-orange-500 dark:text-white transition"
                            placeholder="usuario@empresa.com">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400 dark:text-gray-300" 
                             xmlns="https://www.w3.org/2000/svg" fill="none" 
                             viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                             d="M16.5 12c0 1.657-1.343 3-3 3h-3a3 3 0 010-6h3c1.657 0 3 1.343 3 3zm-5.25 0h.008v.008h-.008V12zm0 0h.008v.008h-.008V12z" />
                        </svg>
                    </div>
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" name="password" required
                            class="w-full pl-11 pr-4 py-3 bg-gray-100 dark:bg-gray-700
                                   border border-gray-300 dark:border-gray-600 
                                   rounded-lg focus:ring-2 focus:ring-orange-500 
                                   focus:border-orange-500 dark:text-white transition"
                            placeholder="••••••••">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400 dark:text-gray-300" 
                             xmlns="http://www.w3.org/2000/svg" fill="none" 
                             viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" 
                                   stroke-width="1.5"
                                   d="M3 8.25c0-.621.504-1.125 1.125-1.125h15.75c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H4.125A1.125 1.125 0 013 18V8.25z" />
                             <path stroke-linecap="round" stroke-linejoin="round" 
                                   stroke-width="1.5"
                                   d="M9 12.75h.008v.008H9v-.008zm3 0h.008v.008H12v-.008zm3 0h.008v.008H15v-.008z" />
                        </svg>
                    </div>
                </div>

                <!-- REMEMBER -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-gray-600 dark:text-gray-300 text-sm">
                        <input type="checkbox" name="remember" class="rounded border-gray-400">
                        Recuérdame
                    </label>

                    <a href="#" class="text-sm text-orange-600 hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <!-- BOTÓN -->
                <button type="submit"
                    class="w-full py-3 bg-orange-600 hover:bg-orange-700 
                           text-white font-semibold rounded-lg shadow-lg
                           transform hover:scale-[1.02] transition">
                    Entrar
                </button>

            </form>

        </div>
    </div>

</div>

</body>
</html>

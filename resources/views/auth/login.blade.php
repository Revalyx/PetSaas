<!DOCTYPE html>
<html lang="es" class="h-full bg-gradient-to-br from-teal-50 via-white to-teal-100">
<head>
    <meta charset="UTF-8">
    <title>Acceso · ARIS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Tailwind config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    }
                }
            }
        }
    </script>
</head>

<body class="h-full font-sans">

<div class="min-h-screen flex items-center justify-center px-4 py-8">

    <!-- CARD PRINCIPAL -->
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-2">

            <!-- =====================
                 IZQUIERDA · LOGIN
                 ===================== -->
            <div class="p-8 md:p-14">

                <!-- BRAND -->
                <div class="mb-10">
                    <div class="flex items-center gap-3 mb-6">
                        <img src="https://cdn-icons-png.flaticon.com/512/194/194279.png"
                             alt="ARIS"
                             class="w-9 h-9">

                        <span class="text-xl font-extrabold text-slate-800">
                            ARIS
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-slate-800 mb-2">
                        Bienvenido de nuevo
                    </h1>

                    <p class="text-slate-500">
                        Gestione su negocio de forma sencilla.
                    </p>
                </div>

                <!-- ERROR -->
                @if($errors->any())
                    <div class="mb-6 p-3 rounded-lg bg-red-100 text-red-700 text-sm border border-red-200">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- FORM -->
                <form method="POST" action="/login" class="space-y-6">
                    @csrf

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" required
                            class="w-full px-4 py-3 rounded-lg
                                   border border-slate-300
                                   placeholder-slate-400
                                   focus:ring-2 focus:ring-teal-500
                                   focus:border-teal-500 transition"
                            placeholder="usuario@empresa.com">
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">
                            Contraseña
                        </label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-lg
                                   border border-slate-300
                                   placeholder-slate-400
                                   focus:ring-2 focus:ring-teal-500
                                   focus:border-teal-500 transition"
                            placeholder="••••••••">
                    </div>

                    <!-- OPCIONES -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-slate-600">
                            <input type="checkbox" name="remember"
                                   class="rounded border-slate-400">
                            Recuérdame
                        </label>

                        <a href="#" class="text-teal-600 hover:underline">
                            ¿Olvidó su contraseña?
                        </a>
                    </div>

                    <!-- BOTÓN -->
                    <button type="submit"
                        class="w-full py-3 rounded-lg
                               bg-teal-600 hover:bg-teal-700
                               text-white font-semibold
                               shadow-md transition">
                        Entrar
                    </button>
                </form>

                <div class="mt-10 text-xs text-slate-400">
                    © {{ date('Y') }} ARIS
                </div>
            </div>

            <!-- =====================
                 DERECHA · IMAGEN
                 ===================== -->
            <div class="relative hidden md:block">
                <img src="https://img.hobbyfarms.com/AdobeStock_574616417-scaled.jpeg"
     alt="Cuidado de mascotas"
     class="absolute inset-0 w-full h-full object-cover"
     style="object-position: 50% 35%;">

            </div>

        </div>
    </div>

</div>

</body>
</html>

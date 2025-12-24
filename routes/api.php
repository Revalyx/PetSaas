<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\MeController;
use App\Http\Controllers\Api\Auth\GoogleLoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Models\Tenant;

/*
|--------------------------------------------------------------------------
| API – HEALTH / DEBUG
|--------------------------------------------------------------------------
| Rutas simples para comprobar que la API está viva.
| No requieren autenticación.
|
*/

Route::get('/ping', function () {
    return response()->json(['ok' => true]);
});

/*
|--------------------------------------------------------------------------
| API – AUTHENTICATION (APP USERS)
|--------------------------------------------------------------------------
| Autenticación de usuarios de la app (NO tenants).
| Estas rutas trabajan contra la BD petsaas_core.
| Devuelven JSON y se consumirán desde la app Android.
|
*/

Route::prefix('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Registro
    |--------------------------------------------------------------------------
    | Crea un nuevo usuario de la app.
    |
    */
    Route::post('/register', [RegisterController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    | Login clásico con email + password.
    | Devuelve un api_token propio para la app.
    |
    */
    Route::post('/login', [LoginController::class, 'login']);

    /*
    |--------------------------------------------------------------------------
    | Login con Google
    |--------------------------------------------------------------------------
    | Login / registro usando Google ID Token.
    | Devuelve un api_token propio para la app.
    |
    */
    Route::post('/google', [GoogleLoginController::class, 'login']);

    // Logout (revoca token)
    Route::middleware('auth.app')->post('/logout', [LogoutController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Usuario autenticado
    |--------------------------------------------------------------------------
    | Devuelve los datos del usuario asociado al token.
    | Protegido por middleware auth.app
    |
    */
    Route::middleware('auth.app')->get('/me', [MeController::class, 'me']);
});

/*
|--------------------------------------------------------------------------
| API – TENANTS (APP)
|--------------------------------------------------------------------------
| Listado de tenants activos para la app Android.
| Protegido por auth.app.
| NO expone credenciales ni datos internos.
|
*/

Route::middleware('auth.app')->get('/tenants', function () {
    return Tenant::where('is_active', 1)
        ->select('id', 'name', 'slug')
        ->orderBy('name')
        ->get();
});

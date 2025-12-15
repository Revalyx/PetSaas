<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\MeController;

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
    |----------------------------------------------------------------------
    | Registro
    |----------------------------------------------------------------------
    | Crea un nuevo usuario de la app.
    |
    */
    Route::post('/register', [RegisterController::class, 'store']);

    /*
    |----------------------------------------------------------------------
    | Login
    |----------------------------------------------------------------------
    | Devuelve un api_token propio para la app.
    |
    */
    Route::post('/login', [LoginController::class, 'login']);

    /*
    |----------------------------------------------------------------------
    | Usuario autenticado
    |----------------------------------------------------------------------
    | Devuelve los datos del usuario asociado al token.
    | Protegido por middleware auth.app
    |
    */
    Route::middleware('auth.app')->get('/me', [MeController::class, 'me']);
});

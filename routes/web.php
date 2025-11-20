<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ClienteController;


// ========================================
// LOGIN + LOGOUT
// ========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ========================================
// HOME PUBLICA
// ========================================
Route::get('/', function () {
    return 'Página pública. Ve a /login';
});


// ========================================
// DEBUG
// ========================================
Route::get('/debug-db', function () {
    return [
        'user'      => auth()->user(),
        'mysql_db'  => \DB::connection()->getDatabaseName(),
        'tenant_db' => request()->tenant_db ?? '',
    ];
});


// ========================================
// PANEL DEL JEFE (TENANT)
// ========================================
// Middleware: auth → tenant (carga BD dinámica)
Route::middleware(['auth', 'tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

    // Dashboard del tenant
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // CRUD de clientes
    Route::get('/clientes', [ClienteController::class, 'index'])
        ->name('clientes.index');

    Route::get('/clientes/crear', [ClienteController::class, 'create'])
        ->name('clientes.create');

    Route::post('/clientes', [ClienteController::class, 'store'])
        ->name('clientes.store');
});


// ========================================
// SUPERADMIN - HEALTH
// ========================================
Route::get('/superadmin/health', [SuperAdminController::class, 'systemHealth'])
    ->middleware(['auth', 'superadmin'])
    ->name('superadmin.health');


// ========================================
// SUPERADMIN
// ========================================
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

    // Dashboard Superadmin
    Route::get('/dashboard', [SuperAdminController::class, 'index'])
        ->name('dashboard');

    // CRUD Tenants
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])
        ->name('tenants.index');

    Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])
        ->name('tenants.create');

    Route::post('/tenants/store', [SuperAdminController::class, 'storeTenant'])
        ->name('tenants.store');

    Route::delete('/tenants/{id}', [SuperAdminController::class, 'destroy'])
        ->name('tenants.destroy');

    // CRUD Usuarios de empresa
    Route::get('/users/create', [SuperAdminController::class, 'createUser'])
        ->name('users.create');

    Route::post('/users/store', [SuperAdminController::class, 'storeUser'])
        ->name('users.store');
});

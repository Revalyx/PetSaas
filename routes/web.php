<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Tenant\DashboardController;


// ========================================
// LOGIN + LOGOUT
// ========================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ========================================
// HOME PÚBLICA
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
// Middleware: primero auth → luego tenant (carga DB dinámica)
Route::middleware(['auth', 'tenant'])
    ->name('tenant.')
    ->group(function () {

    // Dashboard del tenant
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});


// ========================================
// PRUEBA DE TABLAS EN TENANT
// ========================================
Route::get('/tenant-test', function () {
    try {
        return \DB::connection('tenant')->select('SELECT * FROM pruebas');
    } catch (\Exception $e) {
        return 'ERROR: ' . $e->getMessage();
    }
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

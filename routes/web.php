<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ClienteController;
use App\Http\Controllers\Tenant\MascotaController;
use App\Http\Controllers\AppointmentController;

// ========================================================
// LOGIN + LOGOUT
// ========================================================
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========================================================
// HOME PUBLICA
// ========================================================
Route::get('/', function () {
    return 'Página pública. Ve a /login';
});

// ========================================================
// DEBUG
// ========================================================
Route::get('/debug-db', function () {
    return [
        'user'      => auth()->user(),
        'mysql_db'  => \DB::connection()->getDatabaseName(),
        'tenant_db' => request()->tenant_db ?? '',
    ];
});

// ========================================================
// PANEL DEL TENANT (JEFE)
// Middleware: auth → tenant
// ========================================================
Route::middleware(['auth', 'tenant'])
    ->prefix('tenant')
    ->name('tenant.')
    ->group(function () {

        // -------------------------------
        // DASHBOARD
        // -------------------------------
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // -------------------------------
        // CLIENTES CRUD
        // -------------------------------
        Route::get('/clientes', [ClienteController::class, 'index'])
            ->name('clientes.index');

        Route::get('/clientes/crear', [ClienteController::class, 'create'])
            ->name('clientes.create');

        Route::post('/clientes', [ClienteController::class, 'store'])
            ->name('clientes.store');

        // -------------------------------
        // MASCOTAS CRUD COMPLETO
        // -------------------------------
        Route::get('/mascotas', [MascotaController::class, 'index'])
            ->name('mascotas.index');

        Route::get('/mascotas/crear', [MascotaController::class, 'create'])
            ->name('mascotas.create');

        Route::post('/mascotas', [MascotaController::class, 'store'])
            ->name('mascotas.store');

        Route::get('/mascotas/{id}/editar', [MascotaController::class, 'edit'])
            ->name('mascotas.edit');

        Route::put('/mascotas/{id}', [MascotaController::class, 'update'])
            ->name('mascotas.update');

        Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy'])
            ->name('mascotas.destroy');

        // -------------------------------
// APPOINTMENTS (CITAS)
// -------------------------------
Route::prefix('appointments')->name('appointments.')->group(function () {

    Route::get('/', [AppointmentController::class, 'index'])
        ->name('index');

    Route::get('/create', [AppointmentController::class, 'create'])
        ->name('create');

    Route::post('/', [AppointmentController::class, 'store'])
        ->name('store');

    Route::get('/{id}/edit', [AppointmentController::class, 'edit'])
        ->name('edit');

    // 🔥 RUTA CORRECTA PARA FULLCALENDAR
    Route::get('/calendar/events', [AppointmentController::class, 'calendarEvents'])
        ->name('calendar.events');

    Route::put('/{id}', [AppointmentController::class, 'update'])
        ->name('update');

    Route::delete('/{id}', [AppointmentController::class, 'destroy'])
        ->name('destroy');
});

        // Calendario de citas
        Route::get('/appointments/calendar', [\App\Http\Controllers\AppointmentCalendarController::class, 'index'])
            ->name('appointments.calendar');

        Route::get('/appointments/calendar-events', [\App\Http\Controllers\AppointmentCalendarController::class, 'events'])
            ->name('appointments.calendar.events');

});

// ========================================================
// SUPERADMIN - HEALTH CHECK
// ========================================================
Route::get('/superadmin/health', [SuperAdminController::class, 'systemHealth'])
    ->middleware(['auth', 'superadmin'])
    ->name('superadmin.health');

// ========================================================
// SUPERADMIN PANEL
// ========================================================
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [SuperAdminController::class, 'index'])
            ->name('dashboard');

        // TENANTS CRUD
        Route::get('/tenants', [SuperAdminController::class, 'tenants'])
            ->name('tenants.index');

        Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])
            ->name('tenants.create');

        Route::post('/tenants/store', [SuperAdminController::class, 'storeTenant'])
            ->name('tenants.store');

        Route::delete('/tenants/{id}', [SuperAdminController::class, 'destroy'])
            ->name('tenants.destroy');

        // USERS CRUD
        Route::get('/users/create', [SuperAdminController::class, 'createUser'])
            ->name('users.create');

        Route::post('/users/store', [SuperAdminController::class, 'storeUser'])
            ->name('users.store');

        // Ejecutar migraciones del tenant actual
        Route::get('/run-tenant-migrations', function () {
            \Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force'    => true
            ]);

            return "Migraciones ejecutadas en este tenant.";
        })->middleware(['auth', 'tenant']);
});

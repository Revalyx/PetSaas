<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ClienteController;
use App\Http\Controllers\Tenant\MascotaController;
use App\Http\Controllers\Tenant\ProductController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Tenant\SaleController;
use App\Http\Controllers\Tenant\SaleItemController;


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
    return redirect()->route('login');
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
        // CLIENTES CRUD COMPLETO
        // -------------------------------
        Route::get('/clientes', [ClienteController::class, 'index'])
            ->name('clientes.index');

        Route::get('/clientes/crear', [ClienteController::class, 'create'])
            ->name('clientes.create');

        Route::post('/clientes', [ClienteController::class, 'store'])
            ->name('clientes.store');

        Route::get('/clientes/{id}/editar', [ClienteController::class, 'edit'])
            ->name('clientes.edit');

        Route::put('/clientes/{id}', [ClienteController::class, 'update'])
            ->name('clientes.update');

        Route::delete('/clientes/{id}', [ClienteController::class, 'destroy'])
            ->name('clientes.destroy');

        Route::get('/clients/{client}/pets',[AppointmentController::class, 'petsByClient'])
            ->name('tenant.clients.pets');
    

        // -------------------------------
        // VENTAS
        // -------------------------------
        Route::get('/sales', [SaleController::class, 'index'])
            ->name('sales.index');

        Route::get('/sales/{sale}', [SaleController::class, 'show'])
            ->name('sales.show');

        Route::post('/sales/{sale}/items/from-product', [SaleItemController::class, 'fromProduct'])
            ->name('sales.items.from-product');

        Route::post('/sales', [SaleController::class, 'store'])
            ->name('sales.store');

        Route::delete(
            '/sales/{sale}/items/{item}',
            [SaleItemController::class, 'destroy']
        )->name('sales.items.destroy');
        Route::post(
            '/sales/{sale}/assign-client',
            [\App\Http\Controllers\Tenant\SaleController::class, 'assignClient']
        )->name('sales.assign-client');

        Route::delete(
            '/sales/{sale}',
            [\App\Http\Controllers\Tenant\SaleController::class, 'destroy']
        )->name('sales.destroy');

        Route::post(
            '/sales/{sale}/close',
            [\App\Http\Controllers\Tenant\SaleController::class, 'close']
        )->name('sales.close');

        Route::get('/tenant/sales/{sale}/document', 
            [SaleController::class, 'document']
        )->name('tenant.sales.document');

        Route::get('/sales/{sale}/invoice',
            [SaleController::class, 'invoice']
        )->name('sales.invoice');





    

    


        // -------------------------------
        // MASCOTAS CRUD COMPLETO
        // -------------------------------
        Route::get('/mascotas', [MascotaController::class, 'index'])->name('mascotas.index');
        Route::get('/mascotas/crear', [MascotaController::class, 'create'])->name('mascotas.create');
        Route::post('/mascotas', [MascotaController::class, 'store'])->name('mascotas.store');
        Route::get('/mascotas/{id}/editar', [MascotaController::class, 'edit'])->name('mascotas.edit');
        Route::put('/mascotas/{id}', [MascotaController::class, 'update'])->name('mascotas.update');
        Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy'])->name('mascotas.destroy');

        // -------------------------------
        // PRODUCTOS CRUD COMPLETO (CORREGIDO)
        // -------------------------------
        // Productos CRUD COMPLETO
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/stock',[ProductController::class, 'updateStock'])->name('tenant.products.stock');


        // -------------------------------
        // APPOINTMENTS (CITAS)
        // -------------------------------
        Route::prefix('appointments')->name('appointments.')->group(function () {

            Route::get('/', [AppointmentController::class, 'index'])->name('index');
            Route::get('/create', [AppointmentController::class, 'create'])->name('create');
            Route::post('/', [AppointmentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');

            Route::get('/calendar/events', [AppointmentController::class, 'calendarEvents'])
                ->name('calendar.events');

            Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
            Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        });

        Route::get('/appointments/calendar', [\App\Http\Controllers\AppointmentCalendarController::class, 'index'])
            ->name('appointments.calendar');

        Route::get('/appointments/calendar-events', [\App\Http\Controllers\AppointmentCalendarController::class, 'events'])
            ->name('appointments.calendar.events');
    });


// ========================================================
// SUPERADMIN
// ========================================================
Route::middleware(['auth', 'superadmin'])
    ->prefix('superadmin')
    ->name('superadmin.')
    ->group(function () {

        Route::get('/health', function () {

    return view('superadmin.health.index', [
        'php'     => PHP_VERSION,
        'laravel' => app()->version(),
        'time'    => now()->toDateTimeString(),
        'tenants' => \App\Models\Tenant::all(),
    ]);

})->name('health');



        Route::get('/dashboard', [SuperAdminController::class, 'index'])
            ->name('dashboard');

        Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants.index');
        Route::get('/tenants/create', [SuperAdminController::class, 'createTenant'])->name('tenants.create');
        Route::post('/tenants/store', [SuperAdminController::class, 'storeTenant'])->name('tenants.store');
        Route::delete('/tenants/{id}', [SuperAdminController::class, 'destroy'])->name('tenants.destroy');

        Route::get('/users/create', [SuperAdminController::class, 'createUser'])->name('users.create');
        Route::post('/users/store', [SuperAdminController::class, 'storeUser'])->name('users.store');

        Route::get('/run-tenant-migrations', function () {
            \Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force'    => true
            ]);
            return "Migraciones ejecutadas en este tenant.";
        })->middleware(['auth', 'tenant']);
    });

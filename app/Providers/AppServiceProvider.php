<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom([
            database_path('migrations/core'),
            database_path('migrations/tenant'),
        ]);
    }
}


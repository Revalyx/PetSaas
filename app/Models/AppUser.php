<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    protected $table = 'app_users';

    // IMPORTANTE: conexión core
    protected $connection = 'core';

    protected $fillable = [
        'email',
        'password',
        'google_id',
        'name',
        'api_token',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];
}

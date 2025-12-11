<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'db_host',
        'db_name',
        'db_username',
        'db_password',
        'is_active'
    ];

    protected $casts = [

    ];
}

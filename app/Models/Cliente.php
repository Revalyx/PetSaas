<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $connection = 'tenant';

    protected $table = 'clientes';

    protected $fillable = [
        'nombre',
        'apellidos',
        'telefono',
        'email',
        'direccion',
        'notas',
    ];
}

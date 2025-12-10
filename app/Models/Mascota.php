<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $table = 'mascotas';
    protected $connection = 'tenant';


    protected $fillable = [
        'cliente_id',
        'nombre',
        'especie',
        'raza',
        'edad',
        'notas'
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}

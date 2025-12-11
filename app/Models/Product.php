<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'tenant';
    protected $table = 'products';

    protected $fillable = [
        'id_adicional',
        'codigo_barras',
        'categoria',
        'producto',
        'precio',
        'porcentaje_impuesto',
        'pvp',
        'precio_real',
        'beneficio',
        'margen',
        'stock',
        'image_path',
        'image_alt',
    ];

    // Accesor para obtener URL pública
    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return null;
        }

        return asset('storage/' . $this->image_path);
    }
}

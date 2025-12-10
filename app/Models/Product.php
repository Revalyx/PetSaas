<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $connection = 'tenant';
    protected $table = 'products';

    protected $fillable = [
    'name',
    'description',
    'price',
    'stock',
    'barcode',
    'image_path',
    'image_alt',
];


    // Helper para obtener URL pública de la imagen
    public function getImageUrlAttribute()
{
    return $this->image_path ? asset('storage/' . $this->image_path) : null;
}

}

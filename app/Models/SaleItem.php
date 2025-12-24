<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $connection = 'tenant';

    protected $table = 'sales_items'; 

    protected $fillable = [
        'sale_id',
        'item_type',
        'item_id',
        'name',
        'quantity',
        'unit_price',
        'tax_percent',
        'tax_amount',
        'subtotal',
    ];
}

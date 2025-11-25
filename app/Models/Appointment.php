<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id',
        'pet_id',
        'date',
        'start_time',
        'end_time',
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
    ];

    public function customer()
    {
        return $this->belongsTo(Cliente::class, 'customer_id');
    }

    public function pet()
    {
        return $this->belongsTo(Mascota::class, 'pet_id');
    }
}

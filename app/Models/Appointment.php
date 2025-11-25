<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'pet_id',
        'service_id',
        'date',
        'start_time',
        'end_time',
        'notes',
        'status',
        'type',
        'is_difficult',
    ];

    protected $casts = [
        'date' => 'date',
        'is_difficult' => 'boolean',
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

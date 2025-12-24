<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $table = 'sales';
    protected $connection = 'tenant';



    protected $fillable = [
        'tenant_id',
        'employee_id',
        'customer_id',
        'status',
        'payment_method',
        'document_type',
        'subtotal',
        'tax_total',
        'total',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'subtotal'     => 'decimal:2',
        'tax_total'    => 'decimal:2',
        'total'        => 'decimal:2',
        'closed_at'    => 'datetime'
    ];

    /* =========================
     | Relaciones
     ========================= */

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'customer_id');
    }


    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /* =========================
     | Scopes útiles
     ========================= */

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /* =========================
     | Helpers de estado
     ========================= */

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }
}

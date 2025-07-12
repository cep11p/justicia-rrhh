<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidacionConcepto extends Model
{
    use HasFactory;

    protected $table = 'liquidacion_conceptos';

    protected $fillable = [
        'importe',
        'liquidacion_empleado_id',
        'concepto_id',
    ];

    protected $casts = [
        'importe' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene la liquidación de empleado de este concepto
     */
    public function liquidacionEmpleado(): BelongsTo
    {
        return $this->belongsTo(LiquidacionEmpleado::class);
    }

    /**
     * Obtiene el concepto de esta liquidación
     */
    public function concepto(): BelongsTo
    {
        return $this->belongsTo(Concepto::class);
    }
}

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
        'liquidacion_empleado_id' => 'integer',
        'concepto_id' => 'integer',
    ];

    /**
     * Obtiene la liquidación de empleado de este concepto
     */
    public function liquidacionEmpleado(): BelongsTo
    {
        return $this->belongsTo(LiquidacionEmpleado::class);
    }

    /**
     * Obtiene el concepto de este registro
     */
    public function concepto(): BelongsTo
    {
        return $this->belongsTo(Concepto::class);
    }

    /**
     * Obtiene la liquidación a través de liquidacionEmpleado
     */
    public function liquidacion()
    {
        return $this->liquidacionEmpleado->liquidacion();
    }

    /**
     * Obtiene el empleado a través de liquidacionEmpleado
     */
    public function empleado()
    {
        return $this->liquidacionEmpleado->empleado();
    }
}

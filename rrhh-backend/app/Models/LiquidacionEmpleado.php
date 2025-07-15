<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiquidacionEmpleado extends Model
{
    use HasFactory;

    protected $table = 'liquidacion_empleados';

    protected $fillable = [
        'liquidacion_id',
        'empleado_id',
    ];

    protected $casts = [
        'liquidacion_id' => 'integer',
        'empleado_id' => 'integer',
    ];

    /**
     * Obtiene la liquidación de este registro
     */
    public function liquidacion(): BelongsTo
    {
        return $this->belongsTo(Liquidacion::class);
    }

    /**
     * Obtiene el empleado de este registro
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Obtiene los conceptos de esta liquidación de empleado
     */
    public function liquidacionConceptos(): HasMany
    {
        return $this->hasMany(LiquidacionConcepto::class);
    }
}

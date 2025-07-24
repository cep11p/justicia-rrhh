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
        'liquidacion_id',
        'concepto_id',
        'padre_id',
    ];

    protected $casts = [
        'importe' => 'decimal:2',
        'liquidacion_id' => 'integer',
        'concepto_id' => 'integer',
        'padre_id' => 'integer',
    ];

    /**
     * Obtiene el concepto de este registro
     */
    public function concepto(): BelongsTo
    {
        return $this->belongsTo(Concepto::class);
    }

    /**
     * Obtiene la liquidación a través de liquidacion
     */
    public function liquidacion()
    {
        return $this->belongsTo(Liquidacion::class);
    }

    /**
     * Obtiene el empleado a través de liquidacion
     */
    public function empleado()
    {
        return $this->liquidacion->empleado();
    }

    /**
     * Obtiene el concepto padre de este concepto
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(LiquidacionConcepto::class, 'padre_id');
    }

    /**
     * Obtiene los conceptos hijos de este concepto
     */
    public function hijos()
    {
        return $this->hasMany(LiquidacionConcepto::class, 'padre_id');
    }

    /**
     * Obtiene los conceptos remunerativos de la liquidación
     * @return HasMany
     */
    public function scopeConRemunerativos($query)
    {
        return $query->whereHas('concepto', fn($q) => $q->where('tipo', 'Remunerativo'));
    }

}

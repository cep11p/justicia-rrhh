<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Concepto extends Model
{
    use HasFactory;

    protected $table = 'conceptos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'tipo',
        'tipo_valor',
    ];

    protected $casts = [
        'codigo' => 'string',
        'nombre' => 'string',
        'descripcion' => 'string',
        'tipo' => 'string',
        'tipo_valor' => 'string',
    ];

    /**
     * Obtiene los valores de este concepto
     */
    public function valorConceptos(): HasMany
    {
        return $this->hasMany(ValorConcepto::class);
    }

    /**
     * Obtiene las liquidaciones de conceptos
     */
    public function liquidacionConceptos(): HasMany
    {
        return $this->hasMany(LiquidacionConcepto::class);
    }

    /**
     * Verifica si el concepto es remunerativo
     */
    public function isRemunerativo(): bool
    {
        return $this->tipo === 'Remunerativo';
    }

    /**
     * Verifica si el concepto es descuento
     */
    public function isDescuento(): bool
    {
        return $this->tipo === 'Descuento';
    }

    /**
     * Verifica si el concepto es de valor fijo
     */
    public function isValorFijo(): bool
    {
        return $this->tipo_valor === 'fijo';
    }

    /**
     * Verifica si el concepto es de valor porcentual
     */
    public function isValorPorcentual(): bool
    {
        return $this->tipo_valor === 'porcentual';
    }

}

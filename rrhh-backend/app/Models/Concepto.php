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
        'descripcion',
        'tipo',
        'es_remunerativo',
    ];

    protected $casts = [
        'es_remunerativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
}

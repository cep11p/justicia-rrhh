<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';

    protected $fillable = [
        'nombre',
        'tiene_funcion',
    ];

    protected $casts = [
        'tiene_funcion' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene las designaciones de este cargo
     */
    public function designaciones(): HasMany
    {
        return $this->hasMany(Designacion::class);
    }

    /**
     * Obtiene los valores de conceptos para este cargo
     */
    public function valorConceptos(): HasMany
    {
        return $this->hasMany(ValorConcepto::class);
    }
}

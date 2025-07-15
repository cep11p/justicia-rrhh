<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = [
        'persona_id',
        'fecha_ingreso',
        'legajo',
        'titulo',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'legajo' => 'string',
        'titulo' => 'string',
    ];

    /**
     * Obtiene la persona asociada al empleado
     */
    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class);
    }

    /**
     * Obtiene las designaciones del empleado
     */
    public function designaciones(): HasMany
    {
        return $this->hasMany(Designacion::class);
    }

    /**
     * Obtiene las liquidaciones del empleado
     */
    public function liquidaciones(): HasMany
    {
        return $this->hasMany(LiquidacionEmpleado::class);
    }

    /**
     * Obtiene el nombre completo del empleado
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->persona->nombre_completo ?? '';
    }

}

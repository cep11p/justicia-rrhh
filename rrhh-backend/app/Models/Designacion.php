<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Designacion extends Model
{
    use HasFactory;

    protected $table = 'designaciones';

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'empleado_id',
        'estructura_organizativa_id',
        'cargo_id',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene el empleado de esta designación
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Obtiene la estructura organizativa de esta designación
     */
    public function estructuraOrganizativa(): BelongsTo
    {
        return $this->belongsTo(EstructuraOrganizativa::class);
    }

    /**
     * Obtiene el cargo de esta designación
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}

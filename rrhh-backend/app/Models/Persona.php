<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';

    protected $fillable = [
        'cuil',
        'apellido',
        'nombre',
    ];

    protected $casts = [
        'cuil' => 'string',
        'apellido' => 'string',
        'nombre' => 'string',
    ];

    /**
     * Obtiene el empleado asociado a esta persona
     */
    public function empleado(): HasOne
    {
        return $this->hasOne(Empleado::class);
    }

    /**
     * Obtiene el nombre completo de la persona
     */
    public function getNombreCompletoAttribute(): string
    {
        return $this->apellido . ', ' . $this->nombre;
    }
}

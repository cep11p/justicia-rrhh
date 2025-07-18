<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Liquidacion extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones';

    protected $fillable = [
        'numero',
        'periodo',
        'fecha_liquidacion',
        'observaciones',
    ];

    protected $casts = [
        'numero' => 'integer',
        'periodo' => 'string',
        'fecha_liquidacion' => 'date',
        'observaciones' => 'string',
    ];

    /**
     * Obtiene el empleado de este registro
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    /**
     * Obtiene las liquidaciones de empleados para esta liquidación
     */
    public function liquidacionEmpleados(): HasMany
    {
        return $this->hasMany(LiquidacionEmpleado::class);
    }

    /**
     * Obtiene los empleados liquidados en esta liquidación
     */
    public function empleados()
    {
        return $this->belongsToMany(Empleado::class, 'liquidacion_empleados')
                    ->withPivot(['total_remunerativo', 'total_descuentos', 'neto'])
                    ->withTimestamps();
    }

    /**
     * Genera el siguiente número de liquidación
     */
    public static function generarSiguienteNumero(): int
    {
        $ultimoNumero = self::max('numero') ?? 0;
        return $ultimoNumero + 1;
    }

}

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
        'empleado_id',
    ];

    protected $casts = [
        'numero' => 'integer',
        'periodo' => 'string',
        'fecha_liquidacion' => 'date',
        'observaciones' => 'string',
        'empleado_id' => 'integer',
    ];

    /**
     * Obtiene el empleado de este registro
     */
    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
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

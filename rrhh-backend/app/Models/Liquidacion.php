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

    public function liquidacionConceptos(): HasMany
    {
        return $this->hasMany(LiquidacionConcepto::class);
    }

    /**
     * Genera el siguiente número de liquidación
     */
    public static function generarSiguienteNumero(): int
    {
        $ultimoNumero = self::max('numero') ?? 0;
        return $ultimoNumero + 1;
    }

    public function calcularConceptoAntiguedad(): void
    {
        $importe = $this->liquidacionConceptos()
            ->whereHas('concepto', function($query) {
                $query->whereIn('codigo', ['001', '002', '003']);
            })
            ->sum('importe');


        $concepto = Concepto::where('codigo', '004')->first();
        $valor_concepto = $concepto->valorConcepto($this->periodo)->valor / 100;
        $valor_concepto = $valor_concepto * $this->empleado->antiguedad;

        $liquidacion_concepto_atributos = [
            'liquidacion_id' => $this->id,
            'concepto_id' => $concepto->id,
            'importe' => $importe * $valor_concepto,
        ];
        LiquidacionConcepto::create($liquidacion_concepto_atributos);

    }

}

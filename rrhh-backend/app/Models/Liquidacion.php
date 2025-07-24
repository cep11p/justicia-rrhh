<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Liquidacion extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    protected static function boot()
    {
        parent::boot();

        // Evento que se ejecuta DESPUÉS de eliminar el registro
        static::deleted(function ($liquidacion) {
            $liquidacion->numero = null;
            $liquidacion->save();
        });

    }

    public function getTotalRemunerativosAttribute(): float
    {
        return $this->liquidacionConceptos()->whereHas('concepto', function($query) {
            $query->whereIn('tipo', ['remunerativo']);
        })->sum('importe');
    }

    public function getTotalNoRemunerativosAttribute(): float
    {
        return $this->liquidacionConceptos()->whereHas('concepto', function($query) {
            $query->whereIn('tipo', ['descuento']);
        })->sum('importe');
    }

    public function getTotalLiquidoAttribute(): float
    {
        return $this->total_remunerativos - $this->total_no_remunerativos;
    }

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
        // Esta suma el importe de los conceptos de liquidación cuyo código es '001', '002' o '003'.
        // Normalmente, estos códigos corresponden a conceptos como "Básico", "Adicional por función" y "Adicional por título".
        // Es decir, está sumando el total de los importes de esos conceptos para esta liquidación.
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

    public function conceptosRemunerativos()
    {
        return $this->liquidacionConceptos()->whereHas('concepto', function($query) {
            $query->whereIn('tipo', ['remunerativo']);
        });
    }

    public function conceptosNoRemunerativos()
    {
        return $this->liquidacionConceptos()->whereHas('concepto', function($query) {
            $query->whereIn('tipo', ['descuento']);
        });
    }

}

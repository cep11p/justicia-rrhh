<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValorConcepto extends Model
{
    use HasFactory;

    protected $table = 'valor_conceptos';

    protected $fillable = [
        'valor',
        'concepto_id',
        'cargo_id',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'concepto_id' => 'integer',
        'cargo_id' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    /**
     * Obtiene el concepto de este valor
     */
    public function concepto(): BelongsTo
    {
        return $this->belongsTo(Concepto::class);
    }

    /**
     * Scope para filtrar por período
     *
     * @param [string] $periodo YYYYMM (202501)
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByPeriodo($query, $periodo)
    {
        $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);
        $fechaInicio = $fechaPeriodo->copy()->startOfMonth();
        $fechaFin = $fechaPeriodo->copy()->endOfMonth();

        return $query->where('fecha_inicio', '<=', $fechaInicio)
                    ->where(function($query) use ($fechaFin) {
                        $query->where('fecha_fin', '>=', $fechaFin)
                              ->orWhereNull('fecha_fin');
                    });
    }


    /**
     * Obtiene el cargo de este valor (puede ser null)
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}

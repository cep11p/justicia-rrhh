<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValorConcepto extends Model
{
    use HasFactory;

    protected $table = 'valor_conceptos';

    protected $fillable = [
        'periodo',
        'valor',
        'concepto_id',
        'cargo_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene el concepto de este valor
     */
    public function concepto(): BelongsTo
    {
        return $this->belongsTo(Concepto::class);
    }

    /**
     * Obtiene el cargo de este valor (puede ser null)
     */
    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }
}

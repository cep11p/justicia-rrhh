<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstructuraOrganizativa extends Model
{
    use HasFactory;

    protected $table = 'estructuras_organizativas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'padre_id',
    ];

    protected $casts = [
        'nombre' => 'string',
        'descripcion' => 'string',
        'padre_id' => 'integer',
    ];

    /**
     * Obtiene la estructura padre
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(EstructuraOrganizativa::class, 'padre_id');
    }

    /**
     * Obtiene las estructuras hijas
     */
    public function hijos(): HasMany
    {
        return $this->hasMany(EstructuraOrganizativa::class, 'padre_id');
    }

    /**
     * Obtiene las designaciones en esta estructura
     */
    public function designaciones(): HasMany
    {
        return $this->hasMany(Designacion::class);
    }

}

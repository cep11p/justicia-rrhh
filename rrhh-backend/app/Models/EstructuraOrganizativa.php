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
        'padre_id' => 'integer',
    ];

    /**
     * Obtiene la estructura padre de esta estructura
     */
    public function padre(): BelongsTo
    {
        return $this->belongsTo(EstructuraOrganizativa::class, 'padre_id');
    }

    /**
     * Obtiene las estructuras hijas de esta estructura
     */
    public function hijos(): HasMany
    {
        return $this->hasMany(EstructuraOrganizativa::class, 'padre_id');
    }

    /**
     * Obtiene todas las estructuras hijas recursivamente
     */
    public function todosLosHijos(): HasMany
    {
        return $this->hijos()->with('todosLosHijos');
    }

    /**
     * Obtiene todas las estructuras padre recursivamente
     */
    public function todosLosPadres(): BelongsTo
    {
        return $this->padre()->with('todosLosPadres');
    }

    /**
     * Verifica si es una estructura raíz (sin padre)
     */
    public function esRaiz(): bool
    {
        return is_null($this->padre_id);
    }

    /**
     * Verifica si es una estructura hoja (sin hijos)
     */
    public function esHoja(): bool
    {
        return $this->hijos()->count() === 0;
    }

    /**
     * Obtiene el nivel de profundidad en la jerarquía
     */
    public function getNivelAttribute(): int
    {
        $nivel = 0;
        $estructura = $this;

        while ($estructura->padre) {
            $nivel++;
            $estructura = $estructura->padre;
        }

        return $nivel;
    }

    /**
     * Obtiene la ruta completa desde la raíz
     */
    public function getRutaCompletaAttribute(): string
    {
        $ruta = [$this->nombre];
        $estructura = $this;

        while ($estructura->padre) {
            $estructura = $estructura->padre;
            array_unshift($ruta, $estructura->nombre);
        }

        return implode(' > ', $ruta);
    }
}

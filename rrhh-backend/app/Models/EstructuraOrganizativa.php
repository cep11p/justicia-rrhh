<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstructuraOrganizativa extends Model
{
    use HasFactory;

    protected $table = 'estructuras_organizativas';

    protected $fillable = [
        'nombre',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Obtiene las designaciones de esta estructura organizativa
     */
    public function designaciones(): HasMany
    {
        return $this->hasMany(Designacion::class);
    }
}

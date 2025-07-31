<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmpleadoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fecha_ingreso' => $this->fecha_ingreso,
            'titulo' => $this->titulo,
            'nombre_completo' => $this->nombreCompleto,
            'legajo' => $this->legajo,
            'cuil' => $this->whenLoaded('persona', function () {
                return $this->persona->cuil;
            }),
            'designaciones' => $this->whenLoaded('designaciones', function () {
                return DesignacionResource::collection($this->designaciones);
            })
        ];
    }
}

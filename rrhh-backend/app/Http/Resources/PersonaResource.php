<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonaResource extends JsonResource
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
            'cuil' => $this->cuil,
            'apellido' => $this->apellido,
            'nombre' => $this->nombre,
            'nombre_completo' => $this->nombre_completo,
            'empleado' => $this->whenLoaded('empleado', function () {
                return new EmpleadoResource($this->empleado);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

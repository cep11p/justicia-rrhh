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
            'persona_id' => $this->persona_id,
            'fecha_ingreso' => $this->fecha_ingreso,
            'titulo' => $this->titulo,
            'nombre_completo' => $this->nombre_completo,
            'persona' => $this->whenLoaded('persona', function () {
                return new PersonaResource($this->persona);
            }),
            'designaciones' => $this->whenLoaded('designaciones', function () {
                return DesignacionResource::collection($this->designaciones);
            }),
            'liquidacion_empleados' => $this->whenLoaded('liquidacionEmpleados', function () {
                return LiquidacionEmpleadoResource::collection($this->liquidacionEmpleados);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

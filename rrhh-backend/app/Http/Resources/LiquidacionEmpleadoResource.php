<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiquidacionEmpleadoResource extends JsonResource
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
            'liquidacion_id' => $this->liquidacion_id,
            'empleado_id' => $this->empleado_id,
            'liquidacion' => $this->whenLoaded('liquidacion', function () {
                return new LiquidacionResource($this->liquidacion);
            }),
            'empleado' => $this->whenLoaded('empleado', function () {
                return new EmpleadoResource($this->empleado);
            }),
            'liquidacion_conceptos' => $this->whenLoaded('liquidacionConceptos', function () {
                return LiquidacionConceptoResource::collection($this->liquidacionConceptos);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

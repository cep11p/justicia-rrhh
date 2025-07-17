<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiquidacionResource extends JsonResource
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
            'numero' => $this->numero,
            'periodo' => $this->periodo,
            'empleado' => $this->whenLoaded('liquidacionEmpleados', function () {
                // asume que eager‐loadeaste la relación 'empleado' en LiquidacionEmpleado
                $empleados = $this->liquidacionEmpleados->pluck('empleado');
                return EmpleadoResource::collection($empleados);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CargoResource extends JsonResource
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
            'nombre' => $this->nombre,
            'tiene_funcion' => $this->tiene_funcion,
            'designaciones' => $this->whenLoaded('designaciones', function () {
                return DesignacionResource::collection($this->designaciones);
            }),
            'valor_conceptos' => $this->whenLoaded('valorConceptos', function () {
                return ValorConceptoResource::collection($this->valorConceptos);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

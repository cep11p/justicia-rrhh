<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConceptoResource extends JsonResource
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
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
            'tipo' => $this->tipo,
            'es_remunerativo' => $this->es_remunerativo,
            'valor_conceptos' => $this->whenLoaded('valorConceptos', function () {
                return ValorConceptoResource::collection($this->valorConceptos);
            }),
            'liquidacion_conceptos' => $this->whenLoaded('liquidacionConceptos', function () {
                return LiquidacionConceptoResource::collection($this->liquidacionConceptos);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

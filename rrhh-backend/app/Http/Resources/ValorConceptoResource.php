<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValorConceptoResource extends JsonResource
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
            'periodo' => $this->periodo,
            'valor' => $this->valor,
            'concepto_id' => $this->concepto_id,
            'cargo_id' => $this->cargo_id,
            'concepto' => $this->whenLoaded('concepto', function () {
                return new ConceptoResource($this->concepto);
            }),
            'cargo' => $this->whenLoaded('cargo', function () {
                return new CargoResource($this->cargo);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

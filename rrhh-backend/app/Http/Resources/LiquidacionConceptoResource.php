<?php

namespace App\Http\Resources;

use App\Models\Liquidacion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiquidacionConceptoResource extends JsonResource
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
            'importe' => $this->importe,
            'concepto_id' => $this->concepto_id,
            'concepto' => ConceptoResource::make($this->concepto)
        ];
    }
}

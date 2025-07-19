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
            'empleado' => $this->empleado,
            'remunerativos' => LiquidacionConceptoResource::collection($this->conceptosRemunerativos),
            'no_remunerativos' => LiquidacionConceptoResource::collection($this->conceptosNoRemunerativos),
            'total_remunerativos' => $this->total_remunerativos,
            'total_no_remunerativos' => $this->total_no_remunerativos,
            'total_liquido' => $this->total_liquido,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

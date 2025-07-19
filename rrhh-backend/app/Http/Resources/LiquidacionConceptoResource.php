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
            'liquidacion_empleado_id' => $this->liquidacion_empleado_id,
            'concepto_id' => $this->concepto_id,
            'liquidacion' => $this->whenLoaded('liquidacion', function () {
                return new LiquidacionResource($this->empleado);
            }),
            'concepto' => $this->whenLoaded('concepto', function () {
                return new ConceptoResource($this->concepto);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

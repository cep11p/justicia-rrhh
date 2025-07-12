<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesignacionResource extends JsonResource
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
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'empleado_id' => $this->empleado_id,
            'estructura_organizativa_id' => $this->estructura_organizativa_id,
            'cargo_id' => $this->cargo_id,
            'empleado' => $this->whenLoaded('empleado', function () {
                return new EmpleadoResource($this->empleado);
            }),
            'estructura_organizativa' => $this->whenLoaded('estructuraOrganizativa', function () {
                return new EstructuraOrganizativaResource($this->estructuraOrganizativa);
            }),
            'cargo' => $this->whenLoaded('cargo', function () {
                return new CargoResource($this->cargo);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

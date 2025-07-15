<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\EstructuraOrganizativa;
use App\Models\Cargo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Designacion>
 */
class DesignacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fecha_inicio' => $this->faker->dateTimeBetween('-2 years', '-1 month'),
            'fecha_fin' => $this->faker->optional(0.2)->dateTimeBetween('-1 month', 'now'), // 20% de probabilidad de tener fecha fin
            'empleado_id' => Empleado::factory(),
            'estructura_organizativa_id' => EstructuraOrganizativa::factory(),
            'cargo_id' => Cargo::factory(),
        ];
    }

    /**
     * Indica que la designación está vigente (sin fecha fin)
     */
    public function vigente(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_fin' => null,
        ]);
    }

    /**
     * Indica que la designación ha finalizado
     */
    public function finalizada(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_inicio' => $this->faker->dateTimeBetween('-2 years', '-6 months'),
            'fecha_fin' => $this->faker->dateTimeBetween('-6 months', '-1 month'),
        ]);
    }

    /**
     * Designación reciente (últimos 3 meses)
     */
    public function reciente(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_inicio' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'fecha_fin' => null,
        ]);
    }

    /**
     * Designación con empleado específico
     */
    public function conEmpleado(Empleado $empleado): static
    {
        return $this->state(fn (array $attributes) => [
            'empleado_id' => $empleado->id,
        ]);
    }

    /**
     * Designación con estructura específica
     */
    public function conEstructura(EstructuraOrganizativa $estructura): static
    {
        return $this->state(fn (array $attributes) => [
            'estructura_organizativa_id' => $estructura->id,
        ]);
    }

    /**
     * Designación con cargo específico
     */
    public function conCargo(Cargo $cargo): static
    {
        return $this->state(fn (array $attributes) => [
            'cargo_id' => $cargo->id,
        ]);
    }

    /**
     * Designación para un período específico
     */
    public function paraPeriodo(string $periodo): static
    {
        $fechaPeriodo = \Carbon\Carbon::createFromFormat('Ym', $periodo);
        $fechaInicio = $fechaPeriodo->startOfMonth();

        return $this->state(fn (array $attributes) => [
            'fecha_inicio' => $fechaInicio->subMonths(rand(1, 12)),
            'fecha_fin' => null,
        ]);
    }
}

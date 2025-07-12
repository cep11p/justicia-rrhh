<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\Designacion;
use App\Models\Empleado;
use App\Models\EstructuraOrganizativa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Designacion>
 */
class DesignacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Designacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'empleado_id' => Empleado::factory(),
            'estructura_organizativa_id' => EstructuraOrganizativa::factory(),
            'cargo_id' => Cargo::factory(),
            'fecha_inicio' => $this->faker->dateTimeBetween('-3 years', 'now'),
            'fecha_fin' => $this->faker->optional(0.2)->dateTimeBetween('now', '+2 years'), // 20% de probabilidad de tener fecha fin
        ];
    }

    /**
     * Indicate that the designation is active (no end date).
     */
    public function activa(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_fin' => null,
        ]);
    }

    /**
     * Indicate that the designation is inactive (has end date).
     */
    public function inactiva(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_fin' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the designation is recent (started in the last year).
     */
    public function reciente(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'fecha_fin' => null,
        ]);
    }

    /**
     * Indicate that the designation is long-term (started more than 2 years ago).
     */
    public function largaDuracion(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_inicio' => $this->faker->dateTimeBetween('-5 years', '-2 years'),
            'fecha_fin' => null,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Concepto;
use App\Models\Cargo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ValorConcepto>
 */
class ValorConceptoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'concepto_id' => Concepto::factory(),
            'cargo_id' => $this->faker->optional(0.3)->randomElement([Cargo::factory(), null]), // 30% de probabilidad de tener cargo específico
            'valor' => $this->faker->randomFloat(2, 1000, 1000000),
            'periodo' => $this->faker->numerify('2024##'),
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 year', '-1 month'),
            'fecha_fin' => $this->faker->optional(0.1)->dateTimeBetween('-1 month', 'now'), // 10% de probabilidad de tener fecha fin
        ];
    }

    /**
     * Indica que es un valor específico para un cargo
     */
    public function paraCargo(Cargo $cargo): static
    {
        return $this->state(fn (array $attributes) => [
            'cargo_id' => $cargo->id,
        ]);
    }

    /**
     * Indica que es un valor general (sin cargo específico)
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'cargo_id' => null,
        ]);
    }

    /**
     * Valor vigente (sin fecha fin)
     */
    public function vigente(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_fin' => null,
        ]);
    }

    /**
     * Valor para un período específico
     */
    public function paraPeriodo(string $periodo): static
    {
        $fechaPeriodo = \Carbon\Carbon::createFromFormat('Ym', $periodo);
        $fechaInicio = $fechaPeriodo->startOfMonth();

        return $this->state(fn (array $attributes) => [
            'periodo' => $periodo,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => null,
        ]);
    }

    /**
     * Valor básico remunerativo (alto)
     */
    public function basico(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 200000, 800000),
        ]);
    }

    /**
     * Valor porcentual (bajo)
     */
    public function porcentual(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 1, 50),
        ]);
    }

    /**
     * Valor de descuento (negativo)
     */
    public function descuento(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 5, 20),
        ]);
    }

    /**
     * Valor con concepto específico
     */
    public function conConcepto(Concepto $concepto): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => $concepto->id,
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Liquidacion>
 */
class LiquidacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $periodo = $this->faker->numerify('2024##');
        $fechaLiquidacion = \Carbon\Carbon::createFromFormat('Ym', $periodo)->endOfMonth();

        return [
            'numero' => $this->faker->unique()->numberBetween(1, 999),
            'periodo' => $periodo,
            'fecha_liquidacion' => $fechaLiquidacion,
            'descripcion' => $this->faker->sentence(6),
        ];
    }

    /**
     * Liquidación para un período específico
     */
    public function paraPeriodo(string $periodo): static
    {
        $fechaLiquidacion = \Carbon\Carbon::createFromFormat('Ym', $periodo)->endOfMonth();

        return $this->state(fn (array $attributes) => [
            'periodo' => $periodo,
            'fecha_liquidacion' => $fechaLiquidacion,
            'descripcion' => "Liquidación de haberes período {$periodo}",
        ]);
    }

    /**
     * Liquidación del período actual
     */
    public function periodoActual(): static
    {
        $periodo = now()->format('Ym');
        $fechaLiquidacion = now()->endOfMonth();

        return $this->state(fn (array $attributes) => [
            'periodo' => $periodo,
            'fecha_liquidacion' => $fechaLiquidacion,
            'descripcion' => "Liquidación de haberes período {$periodo}",
        ]);
    }

    /**
     * Liquidación de diciembre
     */
    public function diciembre(): static
    {
        $anio = $this->faker->numberBetween(2020, 2024);
        $periodo = "{$anio}12";
        $fechaLiquidacion = \Carbon\Carbon::createFromFormat('Ym', $periodo)->endOfMonth();

        return $this->state(fn (array $attributes) => [
            'numero' => $this->faker->unique()->numberBetween(1, 999),
            'periodo' => $periodo,
            'fecha_liquidacion' => $fechaLiquidacion,
            'descripcion' => "Liquidación de haberes diciembre {$anio}",
        ]);
    }

    /**
     * Liquidación con número específico
     */
    public function conNumero(int $numero): static
    {
        return $this->state(fn (array $attributes) => [
            'numero' => $numero,
        ]);
    }
}

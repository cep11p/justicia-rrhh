<?php

namespace Database\Factories;

use App\Models\Liquidacion;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiquidacionEmpleado>
 */
class LiquidacionEmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalRemunerativo = $this->faker->randomFloat(2, 200000, 1000000);
        $totalDescuentos = $totalRemunerativo * $this->faker->randomFloat(2, 0.10, 0.25); // 10-25% de descuentos
        $neto = $totalRemunerativo - $totalDescuentos;

        return [
            'liquidacion_id' => Liquidacion::factory(),
            'empleado_id' => Empleado::factory(),
            'total_remunerativo' => $totalRemunerativo,
            'total_descuentos' => $totalDescuentos,
            'neto' => $neto,
        ];
    }

    /**
     * Liquidación de empleado con valores altos
     */
    public function valoresAltos(): static
    {
        $totalRemunerativo = $this->faker->randomFloat(2, 800000, 1500000);
        $totalDescuentos = $totalRemunerativo * $this->faker->randomFloat(2, 0.15, 0.30);
        $neto = $totalRemunerativo - $totalDescuentos;

        return $this->state(fn (array $attributes) => [
            'total_remunerativo' => $totalRemunerativo,
            'total_descuentos' => $totalDescuentos,
            'neto' => $neto,
        ]);
    }

    /**
     * Liquidación de empleado con valores bajos
     */
    public function valoresBajos(): static
    {
        $totalRemunerativo = $this->faker->randomFloat(2, 150000, 400000);
        $totalDescuentos = $totalRemunerativo * $this->faker->randomFloat(2, 0.08, 0.20);
        $neto = $totalRemunerativo - $totalDescuentos;

        return $this->state(fn (array $attributes) => [
            'total_remunerativo' => $totalRemunerativo,
            'total_descuentos' => $totalDescuentos,
            'neto' => $neto,
        ]);
    }

    /**
     * Liquidación de empleado con descuentos altos
     */
    public function descuentosAltos(): static
    {
        $totalRemunerativo = $this->faker->randomFloat(2, 300000, 800000);
        $totalDescuentos = $totalRemunerativo * $this->faker->randomFloat(2, 0.25, 0.40);
        $neto = $totalRemunerativo - $totalDescuentos;

        return $this->state(fn (array $attributes) => [
            'total_remunerativo' => $totalRemunerativo,
            'total_descuentos' => $totalDescuentos,
            'neto' => $neto,
        ]);
    }

    /**
     * Liquidación de empleado con descuentos bajos
     */
    public function descuentosBajos(): static
    {
        $totalRemunerativo = $this->faker->randomFloat(2, 300000, 800000);
        $totalDescuentos = $totalRemunerativo * $this->faker->randomFloat(2, 0.05, 0.15);
        $neto = $totalRemunerativo - $totalDescuentos;

        return $this->state(fn (array $attributes) => [
            'total_remunerativo' => $totalRemunerativo,
            'total_descuentos' => $totalDescuentos,
            'neto' => $neto,
        ]);
    }

    /**
     * Liquidación de empleado con liquidación específica
     */
    public function conLiquidacion(Liquidacion $liquidacion): static
    {
        return $this->state(fn (array $attributes) => [
            'liquidacion_id' => $liquidacion->id,
        ]);
    }

    /**
     * Liquidación de empleado específico
     */
    public function conEmpleado(Empleado $empleado): static
    {
        return $this->state(fn (array $attributes) => [
            'empleado_id' => $empleado->id,
        ]);
    }

    /**
     * Liquidación de empleado con valores específicos
     */
    public function conValores(float $remunerativo, float $descuentos): static
    {
        return $this->state(fn (array $attributes) => [
            'total_remunerativo' => $remunerativo,
            'total_descuentos' => $descuentos,
            'neto' => $remunerativo - $descuentos,
        ]);
    }
}

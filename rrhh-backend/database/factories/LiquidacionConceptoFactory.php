<?php

namespace Database\Factories;

use App\Models\LiquidacionConcepto;
use App\Models\LiquidacionEmpleado;
use App\Models\Concepto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiquidacionConcepto>
 */
class LiquidacionConceptoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'liquidacion_empleado_id' => LiquidacionEmpleado::factory(),
            'concepto_id' => Concepto::factory(),
            'valor' => $this->faker->randomFloat(2, 1000, 500000),
        ];
    }

    /**
     * Concepto remunerativo
     */
    public function remunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->remunerativo(),
            'valor' => $this->faker->randomFloat(2, 50000, 300000),
        ]);
    }

    /**
     * Concepto de descuento
     */
    public function descuento(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->descuento(),
            'valor' => $this->faker->randomFloat(2, 5000, 100000),
        ]);
    }

    /**
     * Concepto básico (valor alto)
     */
    public function basico(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->basico(),
            'valor' => $this->faker->randomFloat(2, 200000, 800000),
        ]);
    }

    /**
     * Concepto jubilatorio (descuento alto)
     */
    public function jubilacion(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->jubilacion(),
            'valor' => $this->faker->randomFloat(2, 20000, 100000),
        ]);
    }

    /**
     * Concepto con valor alto
     */
    public function valorAlto(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 300000, 1000000),
        ]);
    }

    /**
     * Concepto con valor bajo
     */
    public function valorBajo(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 1000, 50000),
        ]);
    }

    /**
     * Concepto con liquidación de empleado específica
     */
    public function conLiquidacionEmpleado(LiquidacionEmpleado $liquidacionEmpleado): static
    {
        return $this->state(fn (array $attributes) => [
            'liquidacion_empleado_id' => $liquidacionEmpleado->id,
        ]);
    }

    /**
     * Concepto específico
     */
    public function conConcepto(Concepto $concepto): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => $concepto->id,
        ]);
    }

    /**
     * Concepto con valor específico
     */
    public function conValor(float $valor): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $valor,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\Concepto;
use App\Models\LiquidacionConcepto;
use App\Models\LiquidacionEmpleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiquidacionConcepto>
 */
class LiquidacionConceptoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LiquidacionConcepto::class;

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
            'importe' => $this->faker->randomFloat(2, 1000, 500000),
        ];
    }

    /**
     * Indicate that the liquidation concept is for a specific liquidation employee.
     */
    public function paraLiquidacionEmpleado(LiquidacionEmpleado $liquidacionEmpleado): static
    {
        return $this->state(fn (array $attributes) => [
            'liquidacion_empleado_id' => $liquidacionEmpleado->id,
        ]);
    }

    /**
     * Indicate that the liquidation concept is for a specific concept.
     */
    public function paraConcepto(Concepto $concepto): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => $concepto->id,
        ]);
    }

    /**
     * Indicate that the liquidation concept is for a remunerative concept.
     */
    public function remunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->remunerativo(),
        ]);
    }

    /**
     * Indicate that the liquidation concept is for a non-remunerative concept.
     */
    public function noRemunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->noRemunerativo(),
        ]);
    }

    /**
     * Indicate that the liquidation concept is for a fixed concept.
     */
    public function conceptoFijo(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->fijo(),
        ]);
    }

    /**
     * Indicate that the liquidation concept is for a percentage concept.
     */
    public function conceptoPorcentual(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->porcentual(),
        ]);
    }

    /**
     * Indicate that the liquidation concept has a high amount.
     */
    public function importeAlto(): static
    {
        return $this->state(fn (array $attributes) => [
            'importe' => $this->faker->randomFloat(2, 200000, 500000),
        ]);
    }

    /**
     * Indicate that the liquidation concept has a low amount.
     */
    public function importeBajo(): static
    {
        return $this->state(fn (array $attributes) => [
            'importe' => $this->faker->randomFloat(2, 1000, 50000),
        ]);
    }

    /**
     * Indicate that the liquidation concept has a medium amount.
     */
    public function importeMedio(): static
    {
        return $this->state(fn (array $attributes) => [
            'importe' => $this->faker->randomFloat(2, 50000, 200000),
        ]);
    }
}

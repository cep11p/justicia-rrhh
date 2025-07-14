<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\Liquidacion;
use App\Models\LiquidacionEmpleado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiquidacionEmpleado>
 */
class LiquidacionEmpleadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LiquidacionEmpleado::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'liquidacion_id' => Liquidacion::factory(),
            'empleado_id' => Empleado::factory(),
        ];
    }

    /**
     * Indicate that the liquidation employee is for a specific liquidation.
     */
    public function paraLiquidacion(Liquidacion $liquidacion): static
    {
        return $this->state(fn (array $attributes) => [
            'liquidacion_id' => $liquidacion->id,
        ]);
    }

    /**
     * Indicate that the liquidation employee is for a specific employee.
     */
    public function paraEmpleado(Empleado $empleado): static
    {
        return $this->state(fn (array $attributes) => [
            'empleado_id' => $empleado->id,
        ]);
    }

    /**
     * Indicate that the liquidation employee is for a recent liquidation.
     */
    public function liquidacionReciente(): static
    {
        return $this->state(fn (array $attributes) => [
            'liquidacion_id' => Liquidacion::factory()->reciente(),
        ]);
    }

    /**
     * Indicate that the liquidation employee is for an experienced employee.
     */
    public function empleadoExperimentado(): static
    {
        return $this->state(fn (array $attributes) => [
            'empleado_id' => Empleado::factory()->experimentado(),
        ]);
    }

    /**
     * Indicate that the liquidation employee is for a recent employee.
     */
    public function empleadoReciente(): static
    {
        return $this->state(fn (array $attributes) => [
            'empleado_id' => Empleado::factory()->reciente(),
        ]);
    }
}

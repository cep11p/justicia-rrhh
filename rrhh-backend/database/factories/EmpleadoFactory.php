<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Empleado::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'persona_id' => Persona::factory(),
            'fecha_ingreso' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'titulo' => $this->faker->randomElement(['sin_titulo', 'secundario', 'terciario', 'universitario']),
        ];
    }

    /**
     * Indicate that the employee has no title.
     */
    public function sinTitulo(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'sin_titulo',
        ]);
    }

    /**
     * Indicate that the employee has secondary education.
     */
    public function secundario(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'secundario',
        ]);
    }

    /**
     * Indicate that the employee has tertiary education.
     */
    public function terciario(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'terciario',
        ]);
    }

    /**
     * Indicate that the employee has university education.
     */
    public function universitario(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'universitario',
        ]);
    }

    /**
     * Indicate that the employee is recent (last year).
     */
    public function reciente(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_ingreso' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the employee is experienced (more than 3 years).
     */
    public function experimentado(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_ingreso' => $this->faker->dateTimeBetween('-10 years', '-3 years'),
        ]);
    }
}

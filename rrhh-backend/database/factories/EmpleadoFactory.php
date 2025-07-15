<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empleado>
 */
class EmpleadoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $titulos = ['universitario', 'terciario', 'secundario', 'sin_titulo'];

        return [
            'persona_id' => Persona::factory(),
            'fecha_ingreso' => $this->faker->dateTimeBetween('-10 years', '-1 month'),
            'legajo' => $this->faker->unique()->numerify('L####'),
            'titulo' => $this->faker->randomElement($titulos),
        ];
    }

    /**
     * Indica que el empleado tiene título universitario
     */
    public function universitario(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'universitario',
        ]);
    }

    /**
     * Indica que el empleado tiene título terciario
     */
    public function terciario(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'terciario',
        ]);
    }

    /**
     * Indica que el empleado tiene título secundario
     */
    public function secundario(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'secundario',
        ]);
    }

    /**
     * Indica que el empleado no tiene título
     */
    public function sinTitulo(): static
    {
        return $this->state(fn (array $attributes) => [
            'titulo' => 'sin_titulo',
        ]);
    }

    /**
     * Empleado con antigüedad alta (más de 5 años)
     */
    public function conAntiguedad(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_ingreso' => $this->faker->dateTimeBetween('-10 years', '-5 years'),
        ]);
    }

    /**
     * Empleado recién ingresado (menos de 1 año)
     */
    public function recienIngresado(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_ingreso' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Empleado con persona específica
     */
    public function conPersona(Persona $persona): static
    {
        return $this->state(fn (array $attributes) => [
            'persona_id' => $persona->id,
        ]);
    }
}

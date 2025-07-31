<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cargo>
 */
class CargoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cargos = [
            'Administrativo Senior' => 'Responsable de la gestión administrativa avanzada y coordinación de procesos internos',
            'Asistente Administrativo' => 'Apoyo en tareas administrativas y gestión de documentación',
            'Analista de RRHH' => 'Responsable de la gestión del personal, selección y desarrollo organizacional',
            'Asistente de RRHH' => 'Apoyo en tareas de recursos humanos y gestión de personal'
        ];
        $cargo = $this->faker->unique()->randomElement(array_keys($cargos));
        $descripcion = $cargos[$cargo];

        return [
            'nombre' => $cargo,
            'descripcion' => $descripcion,
            'tiene_funcion' => $this->faker->boolean(30), // 30% de probabilidad de tener función
        ];
    }

    /**
     * Indica que el cargo tiene función
     */
    public function conFuncion(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiene_funcion' => true,
        ]);
    }

    /**
     * Indica que el cargo no tiene función
     */
    public function sinFuncion(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiene_funcion' => false,
        ]);
    }

    /**
     * Cargo de nivel gerencial
     */
    public function gerencial(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => $this->faker->randomElement(['Director', 'Gerente', 'Jefe de Área']),
            'tiene_funcion' => true,
        ]);
    }

    /**
     * Cargo de nivel técnico
     */
    public function tecnico(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => $this->faker->randomElement(['Técnico', 'Operador', 'Especialista']),
            'tiene_funcion' => false,
        ]);
    }
}

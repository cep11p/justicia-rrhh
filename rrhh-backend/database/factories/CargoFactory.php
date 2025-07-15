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
            'Analista Senior' => 'Responsable de análisis avanzado y coordinación de proyectos especializados',
            'Analista' => 'Encargado de análisis de datos y generación de reportes',
            'Asistente' => 'Apoyo en tareas administrativas y operativas',
            'Coordinador' => 'Responsable de coordinar actividades y supervisar equipos de trabajo',
            'Supervisor' => 'Encargado de supervisar operaciones y personal asignado',
            'Técnico' => 'Especialista en tareas técnicas y operativas',
            'Especialista' => 'Profesional especializado en área específica',
            'Consultor' => 'Asesor experto en temas especializados',
            'Director' => 'Responsable de la dirección estratégica del área',
            'Gerente' => 'Encargado de la gestión operativa y administrativa',
            'Jefe de Área' => 'Responsable de la gestión y coordinación del área',
            'Operador' => 'Encargado de operaciones específicas del área',
            'Desarrollador' => 'Responsable del desarrollo de software y aplicaciones',
            'Administrador' => 'Encargado de la administración de sistemas y recursos',
            'Contador' => 'Responsable de la contabilidad y reportes financieros'
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

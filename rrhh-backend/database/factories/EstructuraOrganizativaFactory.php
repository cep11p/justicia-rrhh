<?php

namespace Database\Factories;

use App\Models\EstructuraOrganizativa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EstructuraOrganizativa>
 */
class EstructuraOrganizativaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EstructuraOrganizativa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $estructuras = [
            'Administración' => 'Departamento encargado de la gestión administrativa y procesos internos.',
            'Recursos Humanos' => 'Departamento responsable de la gestión del personal y desarrollo organizacional.',
            'Finanzas' => 'Departamento encargado de la gestión financiera y contable.',
            'Operaciones' => 'Departamento responsable de las operaciones diarias y servicios.',
            'Tecnología' => 'Departamento de sistemas informáticos y soporte técnico.',
            'Comercial' => 'Departamento de ventas y atención al cliente.',
            'Logística' => 'Departamento de gestión de inventarios y distribución.',
            'Mantenimiento' => 'Departamento de mantenimiento de instalaciones y equipos.',
        ];

        $estructura = $this->faker->unique()->randomElement(array_keys($estructuras));
        $descripcion = $estructuras[$estructura];

        return [
            'nombre' => $estructura,
            'descripcion' => $descripcion,
            'padre_id' => null, // Estructuras simples sin jerarquía
        ];
    }

    /**
     * Indica que es una estructura administrativa
     */
    public function administrativa(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Administración',
            'descripcion' => 'Departamento encargado de la gestión administrativa, procesos internos y control de gestión.',
            'padre_id' => null,
        ]);
    }

    /**
     * Indica que es una estructura de recursos humanos
     */
    public function recursosHumanos(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Departamento responsable de la gestión del personal, selección, capacitación y desarrollo organizacional.',
            'padre_id' => null,
        ]);
    }

    /**
     * Indica que es una estructura financiera
     */
    public function finanzas(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Finanzas',
            'descripcion' => 'Departamento encargado de la gestión financiera, contabilidad, presupuestos y control de costos.',
            'padre_id' => null,
        ]);
    }

    /**
     * Indica que es una estructura operacional
     */
    public function operaciones(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Operaciones',
            'descripcion' => 'Departamento responsable de las operaciones diarias, logística y servicios de apoyo.',
            'padre_id' => null,
        ]);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EstructuraOrganizativa>
 */
class EstructuraOrganizativaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombres = [
            'Dirección General', 'Recursos Humanos', 'Finanzas', 'Tecnología',
            'Operaciones', 'Marketing', 'Ventas', 'Administración',
            'Contabilidad', 'Sistemas', 'Mantenimiento', 'Seguridad',
            'Logística', 'Calidad', 'Investigación y Desarrollo'
        ];

        $descripciones = [
            'Departamento responsable de la gestión estratégica y toma de decisiones ejecutivas',
            'Área encargada de la gestión del capital humano y desarrollo organizacional',
            'Departamento de gestión financiera, contabilidad y control de costos',
            'Área de tecnología de la información y sistemas informáticos',
            'Departamento de operaciones diarias y servicios de apoyo',
            'Área de promoción y comunicación de productos y servicios',
            'Departamento de ventas y atención al cliente',
            'Área de gestión administrativa y coordinación de procesos',
            'Departamento de contabilidad general y reportes financieros',
            'Área de desarrollo de software y infraestructura tecnológica',
            'Departamento de mantenimiento de instalaciones y equipos',
            'Área de seguridad física y control de acceso',
            'Departamento de gestión de inventarios y distribución',
            'Área de control de calidad y mejora continua',
            'Departamento de investigación, desarrollo e innovación'
        ];

        return [
            'nombre' => $this->faker->randomElement($nombres),
            'descripcion' => $this->faker->randomElement($descripciones),
            'padre_id' => null, // Por defecto sin padre, se puede modificar en tests
        ];
    }

    /**
     * Indica que la estructura es hija de otra
     */
    public function hijaDe($padreId): static
    {
        return $this->state(fn (array $attributes) => [
            'padre_id' => $padreId,
        ]);
    }

    /**
     * Indica que la estructura es raíz (sin padre)
     */
    public function raiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'padre_id' => null,
        ]);
    }
}

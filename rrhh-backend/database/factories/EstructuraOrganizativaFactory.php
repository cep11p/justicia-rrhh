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
            'Recursos Humanos', 'Administración'
        ];

        $descripciones = [
            'Área encargada de la gestión del capital humano y desarrollo organizacional',
            'Área de gestión administrativa y coordinación de procesos'
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
    // public function hijaDe($padreId): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'padre_id' => $padreId,
    //     ]);
    // }

    /**
     * Indica que la estructura es raíz (sin padre)
     */
    // public function raiz(): static
    // {
    //     return $this->state(fn (array $attributes) => [
    //         'padre_id' => null,
    //     ]);
    // }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $apellidos = [
            'González', 'Rodríguez', 'Gómez', 'Fernández', 'López',
            'Díaz', 'Martínez', 'Pérez', 'García', 'Sánchez',
            'Romero', 'Sosa', 'Torres', 'Álvarez', 'Ruiz',
            'Ramírez', 'Flores', 'Acosta', 'Benítez', 'Silva'
        ];

        $nombres = [
            'Juan Carlos', 'María Elena', 'Carlos Alberto', 'Ana Sofía', 'Roberto Daniel',
            'Laura Beatriz', 'Miguel Ángel', 'Patricia Isabel', 'Diego Alejandro', 'Carmen Victoria',
            'Fernando José', 'Silvia Beatriz', 'Elena Victoria', 'José Luis', 'Mónica Patricia',
            'Ricardo Andrés', 'Claudia Marcela', 'Alejandro Martín', 'Verónica Alejandra', 'Pablo Esteban'
        ];

        return [
            'cuil' => $this->faker->unique()->numerify('20##########'),
            'apellido' => $this->faker->randomElement($apellidos),
            'nombre' => $this->faker->randomElement($nombres),
        ];
    }
}

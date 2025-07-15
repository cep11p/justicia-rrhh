<?php

namespace Database\Factories;

use App\Models\Cargo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cargo>
 */
class CargoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cargo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cargos = [
            'Director General',
            'Gerente de RRHH',
            'Analista de RRHH',
            'Contador',
            'Analista Contable',
            'Desarrollador',
            'Administrador de Sistemas',
            'Administrativo',
            'Técnico de Mantenimiento',
            'Oficial de Seguridad',
            'Personal de Limpieza',
            'Analista de Marketing',
            'Vendedor',
            'Coordinador de Logística',
            'Inspector de Calidad',
            'Supervisor de Producción',
            'Analista Financiero',
            'Abogado',
            'Asistente Administrativo',
            'Técnico de Soporte',
        ];

        return [
            'nombre' => $this->faker->unique()->randomElement($cargos),
            'tiene_funcion' => $this->faker->boolean(30), // 30% de probabilidad de tener función
        ];
    }

    /**
     * Indicate that the cargo has function.
     */
    public function conFuncion(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiene_funcion' => true,
        ]);
    }

    /**
     * Indicate that the cargo doesn't have function.
     */
    public function sinFuncion(): static
    {
        return $this->state(fn (array $attributes) => [
            'tiene_funcion' => false,
        ]);
    }
}

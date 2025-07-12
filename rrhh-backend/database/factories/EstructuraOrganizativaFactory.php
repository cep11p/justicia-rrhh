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
            'Dirección General',
            'Recursos Humanos',
            'Contabilidad',
            'Sistemas',
            'Administración',
            'Mantenimiento',
            'Seguridad',
            'Limpieza',
            'Marketing',
            'Ventas',
            'Logística',
            'Calidad',
            'Producción',
            'Finanzas',
            'Legal',
        ];

        return [
            'nombre' => $this->faker->unique()->randomElement($estructuras),
        ];
    }
}

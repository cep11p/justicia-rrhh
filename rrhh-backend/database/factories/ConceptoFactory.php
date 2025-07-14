<?php

namespace Database\Factories;

use App\Models\Concepto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concepto>
 */
class ConceptoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Concepto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conceptos = [
            ['codigo' => '001', 'descripcion' => 'Sueldo Básico', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '002', 'descripcion' => 'Adicional por Función', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '003', 'descripcion' => 'Adicional por Título', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '004', 'descripcion' => 'Presentismo', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '005', 'descripcion' => 'Horas Extras', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '006', 'descripcion' => 'Bonificación por Rendimiento', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '007', 'descripcion' => 'Jubilación', 'tipo' => 'porcentual', 'es_remunerativo' => false],
            ['codigo' => '008', 'descripcion' => 'Obra Social', 'tipo' => 'porcentual', 'es_remunerativo' => false],
            ['codigo' => '009', 'descripcion' => 'Sindicato', 'tipo' => 'porcentual', 'es_remunerativo' => false],
            ['codigo' => '010', 'descripcion' => 'Ganancia', 'tipo' => 'porcentual', 'es_remunerativo' => false],
            ['codigo' => '011', 'descripcion' => 'Antigüedad', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '012', 'descripcion' => 'Zona Desfavorable', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '013', 'descripcion' => 'Riesgo de Trabajo', 'tipo' => 'fijo', 'es_remunerativo' => true],
            ['codigo' => '014', 'descripcion' => 'Fondo de Desempleo', 'tipo' => 'porcentual', 'es_remunerativo' => false],
            ['codigo' => '015', 'descripcion' => 'INSSJP', 'tipo' => 'porcentual', 'es_remunerativo' => false],
        ];

        $concepto = $this->faker->unique()->randomElement($conceptos);

        return [
            'codigo' => $concepto['codigo'],
            'descripcion' => $concepto['descripcion'],
            'tipo' => $concepto['tipo'],
            'es_remunerativo' => $concepto['es_remunerativo'],
        ];
    }

    /**
     * Indicate that the concept is fixed type.
     */
    public function fijo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'fijo',
        ]);
    }

    /**
     * Indicate that the concept is percentage type.
     */
    public function porcentual(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'porcentual',
        ]);
    }

    /**
     * Indicate that the concept is remunerative.
     */
    public function remunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_remunerativo' => true,
        ]);
    }

    /**
     * Indicate that the concept is not remunerative.
     */
    public function noRemunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_remunerativo' => false,
        ]);
    }
}

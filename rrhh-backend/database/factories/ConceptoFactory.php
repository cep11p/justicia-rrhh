<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Concepto>
 */
class ConceptoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conceptosRemunerativos = [
            'BÁSICO' => 'Sueldo básico del empleado',
            'ADICIONAL POR FUNCIÓN' => 'Adicional por función específica',
            'ADICIONAL POR TÍTULO' => 'Adicional por título académico',
            'ANTIGÜEDAD' => 'Adicional por años de servicio',
            'ZONA' => 'Adicional por zona geográfica',
            'PRESENTISMO' => 'Adicional por asistencia perfecta',
            'HORAS EXTRAS' => 'Compensación por horas trabajadas extra',
            'BONIFICACIÓN' => 'Bonificación por rendimiento',
            'COMISIÓN' => 'Comisión por ventas o resultados',
            'PREMIO' => 'Premio por objetivos cumplidos'
        ];

        $conceptosDescuentos = [
            'APORTE JUBILATORIO' => 'Aporte jubilatorio obligatorio',
            'OBRA SOCIAL' => 'Aporte a obra social',
            'SINDICATO' => 'Aporte sindical',
            'GANANCIA' => 'Impuesto a las ganancias',
            'SEGURO' => 'Seguro de vida obligatorio',
            'PRÉSTAMO' => 'Descuento por préstamo',
            'ADELANTO' => 'Descuento por adelanto de sueldo',
            'FALTAS' => 'Descuento por inasistencias',
            'SANCIONES' => 'Descuento por sanciones disciplinarias',
            'OTROS' => 'Otros descuentos varios'
        ];

        $esRemunerativo = $this->faker->boolean(70); // 70% de probabilidad de ser remunerativo

        if ($esRemunerativo) {
            $concepto = $this->faker->unique()->randomElement(array_keys($conceptosRemunerativos));
            $descripcion = $conceptosRemunerativos[$concepto];
            $tipo = 'Remunerativo';
        } else {
            $concepto = $this->faker->unique()->randomElement(array_keys($conceptosDescuentos));
            $descripcion = $conceptosDescuentos[$concepto];
            $tipo = 'Descuento';
        }

        return [
            'codigo' => $this->faker->unique()->numerify('###'),
            'nombre' => $concepto,
            'descripcion' => $descripcion,
            'tipo' => $tipo,
            'tipo_valor' => $this->faker->randomElement(['fijo', 'porcentual']),
        ];
    }

    /**
     * Indica que es un concepto remunerativo
     */
    public function remunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Remunerativo',
        ]);
    }

    /**
     * Indica que es un concepto de descuento
     */
    public function descuento(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Descuento',
        ]);
    }

    /**
     * Indica que es un valor fijo
     */
    public function valorFijo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_valor' => 'fijo',
        ]);
    }

    /**
     * Indica que es un valor porcentual
     */
    public function valorPorcentual(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo_valor' => 'porcentual',
        ]);
    }

    /**
     * Concepto básico remunerativo
     */
    public function basico(): static
    {
        return $this->state(fn (array $attributes) => [
            'codigo' => '001',
            'nombre' => 'BÁSICO',
            'descripcion' => 'Sueldo básico del empleado',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'fijo',
        ]);
    }

    /**
     * Concepto de descuento jubilatorio
     */
    public function jubilacion(): static
    {
        return $this->state(fn (array $attributes) => [
            'codigo' => '007',
            'nombre' => 'APORTE JUBILATORIO',
            'descripcion' => 'Aporte jubilatorio obligatorio',
            'tipo' => 'Descuento',
            'tipo_valor' => 'porcentual',
        ]);
    }
}

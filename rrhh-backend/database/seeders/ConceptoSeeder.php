<?php

namespace Database\Seeders;

use App\Models\Concepto;
use Illuminate\Database\Seeder;

class ConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conceptos = [
            // Conceptos Remunerativos
            [
                'codigo' => '001',
                'nombre' => 'BÁSICO',
                'descripcion' => 'Sueldo básico del empleado',
                'tipo' => 'Remunerativo',
                'tipo_valor' => 'fijo',
            ],
            [
                'codigo' => '002',
                'nombre' => 'ADICIONAL POR FUNCIÓN',
                'descripcion' => 'Adicional por función específica (5% del básico)',
                'tipo' => 'Remunerativo',
                'tipo_valor' => 'porcentual',
            ],
            [
                'codigo' => '003',
                'nombre' => 'ADICIONAL POR TÍTULO',
                'descripcion' => 'Adicional por título académico (10% del básico)',
                'tipo' => 'Remunerativo',
                'tipo_valor' => 'porcentual',
            ],
            [
                'codigo' => '004',
                'nombre' => 'ANTIGÜEDAD',
                'descripcion' => 'Adicional por años de servicio (2% anual)',
                'tipo' => 'Remunerativo',
                'tipo_valor' => 'porcentual',
            ],
            [
                'codigo' => '005',
                'nombre' => 'ZONA',
                'descripcion' => 'Adicional por zona geográfica (40% de la suma)',
                'tipo' => 'Remunerativo',
                'tipo_valor' => 'porcentual',
            ],

            // Conceptos de Descuento
            [
                'codigo' => '007',
                'nombre' => 'APORTE JUBILATORIO',
                'descripcion' => 'Aporte jubilatorio (11% del total remunerativo)',
                'tipo' => 'Descuento',
                'tipo_valor' => 'porcentual',
            ],
            [
                'codigo' => '008',
                'nombre' => 'OBRA SOCIAL',
                'descripcion' => 'Aporte a obra social (4% del total remunerativo)',
                'tipo' => 'Descuento',
                'tipo_valor' => 'porcentual',
            ],
        ];

        foreach ($conceptos as $concepto) {
            Concepto::create($concepto);
        }
    }
}

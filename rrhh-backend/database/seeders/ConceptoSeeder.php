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
            [
                'codigo' => '001',
                'descripcion' => 'Sueldo Básico',
                'tipo' => 'fijo',
                'es_remunerativo' => true,
            ],
            [
                'codigo' => '002',
                'descripcion' => 'Adicional por Función',
                'tipo' => 'fijo',
                'es_remunerativo' => true,
            ],
            [
                'codigo' => '003',
                'descripcion' => 'Adicional por Título',
                'tipo' => 'fijo',
                'es_remunerativo' => true,
            ],
            [
                'codigo' => '004',
                'descripcion' => 'Presentismo',
                'tipo' => 'fijo',
                'es_remunerativo' => true,
            ],
            [
                'codigo' => '005',
                'descripcion' => 'Horas Extras',
                'tipo' => 'fijo',
                'es_remunerativo' => true,
            ],
            [
                'codigo' => '006',
                'descripcion' => 'Bonificación por Rendimiento',
                'tipo' => 'fijo',
                'es_remunerativo' => true,
            ],
            [
                'codigo' => '007',
                'descripcion' => 'Jubilación',
                'tipo' => 'porcentual',
                'es_remunerativo' => false,
            ],
            [
                'codigo' => '008',
                'descripcion' => 'Obra Social',
                'tipo' => 'porcentual',
                'es_remunerativo' => false,
            ],
            [
                'codigo' => '009',
                'descripcion' => 'Sindicato',
                'tipo' => 'porcentual',
                'es_remunerativo' => false,
            ],
            [
                'codigo' => '010',
                'descripcion' => 'Ganancia',
                'tipo' => 'porcentual',
                'es_remunerativo' => false,
            ],
        ];

        foreach ($conceptos as $concepto) {
            Concepto::create($concepto);
        }
    }
}

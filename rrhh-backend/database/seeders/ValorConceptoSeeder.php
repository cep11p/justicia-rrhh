<?php

namespace Database\Seeders;

use App\Models\ValorConcepto;
use App\Models\Concepto;
use App\Models\Cargo;
use Illuminate\Database\Seeder;

class ValorConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener conceptos
        $conceptos = Concepto::all()->keyBy('codigo');

        // Obtener cargos
        $adminSenior = Cargo::where('nombre', 'Administrativo Senior')->first();
        $adminAsistente = Cargo::where('nombre', 'Asistente Administrativo')->first();
        $rrhhAnalista = Cargo::where('nombre', 'Analista de RRHH')->first();
        $rrhhAsistente = Cargo::where('nombre', 'Asistente de RRHH')->first();

        // Valores de conceptos remunerativos por cargo (BÁSICO)
        $valoresBasico = [
            // Administrativo Senior: $500,000
            [
                'concepto_id' => $conceptos['001']->id,
                'cargo_id' => $adminSenior->id,
                'valor' => 500000,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // Asistente Administrativo: $300,000
            [
                'concepto_id' => $conceptos['001']->id,
                'cargo_id' => $adminAsistente->id,
                'valor' => 300000,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // Analista de RRHH: $450,000
            [
                'concepto_id' => $conceptos['001']->id,
                'cargo_id' => $rrhhAnalista->id,
                'valor' => 450000,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // Asistente de RRHH: $280,000
            [
                'concepto_id' => $conceptos['001']->id,
                'cargo_id' => $rrhhAsistente->id,
                'valor' => 280000,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
        ];

        // Valores de conceptos porcentuales (generales, no específicos de cargo)
        $valoresPorcentuales = [
            // ADICIONAL POR FUNCIÓN: 5% del básico
            [
                'concepto_id' => $conceptos['002']->id,
                'cargo_id' => null,
                'valor' => 5,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // ADICIONAL POR TÍTULO: 10% del básico
            [
                'concepto_id' => $conceptos['003']->id,
                'cargo_id' => null,
                'valor' => 10,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // ANTIGÜEDAD: 2% anual
            [
                'concepto_id' => $conceptos['004']->id,
                'cargo_id' => null,
                'valor' => 2,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // ZONA: 40% de la suma
            [
                'concepto_id' => $conceptos['005']->id,
                'cargo_id' => null,
                'valor' => 40,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // APORTE JUBILATORIO: 11% del total remunerativo
            [
                'concepto_id' => $conceptos['007']->id,
                'cargo_id' => null,
                'valor' => 11,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
            // OBRA SOCIAL: 4% del total remunerativo
            [
                'concepto_id' => $conceptos['008']->id,
                'cargo_id' => null,
                'valor' => 4,
                'periodo' => '202412',
                'fecha_inicio' => '2024-01-01',
                'fecha_fin' => null,
            ],
        ];

        // Crear valores básicos por cargo
        foreach ($valoresBasico as $valor) {
            ValorConcepto::create($valor);
        }

        // Crear valores porcentuales generales
        foreach ($valoresPorcentuales as $valor) {
            ValorConcepto::create($valor);
        }
    }
}

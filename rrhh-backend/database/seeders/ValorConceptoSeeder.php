<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Concepto;
use App\Models\ValorConcepto;
use Illuminate\Database\Seeder;

class ValorConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $valores = [
            // Valores generales (sin cargo específico)
            [
                'concepto_codigo' => '001',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 150000.00,
            ],
            [
                'concepto_codigo' => '002',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 50000.00,
            ],
            [
                'concepto_codigo' => '003',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 30000.00,
            ],
            [
                'concepto_codigo' => '004',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 15000.00,
            ],
            [
                'concepto_codigo' => '005',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 25000.00,
            ],
            [
                'concepto_codigo' => '006',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 20000.00,
            ],
            [
                'concepto_codigo' => '007',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 11.00, // Porcentaje
            ],
            [
                'concepto_codigo' => '008',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 3.00, // Porcentaje
            ],
            [
                'concepto_codigo' => '009',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 2.00, // Porcentaje
            ],
            [
                'concepto_codigo' => '010',
                'cargo_nombre' => null,
                'periodo' => '202412',
                'valor' => 35.00, // Porcentaje
            ],

            // Valores específicos por cargo
            [
                'concepto_codigo' => '001',
                'cargo_nombre' => 'Director General',
                'periodo' => '202412',
                'valor' => 300000.00,
            ],
            [
                'concepto_codigo' => '001',
                'cargo_nombre' => 'Gerente de RRHH',
                'periodo' => '202412',
                'valor' => 250000.00,
            ],
            [
                'concepto_codigo' => '001',
                'cargo_nombre' => 'Contador',
                'periodo' => '202412',
                'valor' => 200000.00,
            ],
            [
                'concepto_codigo' => '001',
                'cargo_nombre' => 'Desarrollador',
                'periodo' => '202412',
                'valor' => 180000.00,
            ],
            [
                'concepto_codigo' => '001',
                'cargo_nombre' => 'Administrador de Sistemas',
                'periodo' => '202412',
                'valor' => 220000.00,
            ],
        ];

        foreach ($valores as $valor) {
            $concepto = Concepto::where('codigo', $valor['concepto_codigo'])->first();
            $cargo = $valor['cargo_nombre'] ? Cargo::where('nombre', $valor['cargo_nombre'])->first() : null;

            if ($concepto) {
                ValorConcepto::create([
                    'concepto_id' => $concepto->id,
                    'cargo_id' => $cargo ? $cargo->id : null,
                    'periodo' => $valor['periodo'],
                    'valor' => $valor['valor'],
                ]);
            }
        }
    }
}

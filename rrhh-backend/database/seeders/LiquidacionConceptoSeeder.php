<?php

namespace Database\Seeders;

use App\Models\Concepto;
use App\Models\LiquidacionConcepto;
use App\Models\LiquidacionEmpleado;
use Illuminate\Database\Seeder;

class LiquidacionConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $liquidacionConceptos = [
            // Liquidación 1 - Empleado 1 (Director General)
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '001',
                'importe' => 300000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '002',
                'importe' => 50000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '003',
                'importe' => 30000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '004',
                'importe' => 15000.00,
            ],

            // Liquidación 1 - Empleado 2 (Gerente RRHH)
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '001',
                'importe' => 250000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '002',
                'importe' => 50000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '003',
                'importe' => 30000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '004',
                'importe' => 15000.00,
            ],

            // Liquidación 1 - Empleado 3 (Analista RRHH)
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20345678901',
                'concepto_codigo' => '001',
                'importe' => 150000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20345678901',
                'concepto_codigo' => '003',
                'importe' => 30000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20345678901',
                'concepto_codigo' => '004',
                'importe' => 15000.00,
            ],

            // Liquidación 2 - Empleado 1
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '001',
                'importe' => 300000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '002',
                'importe' => 50000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20123456789',
                'concepto_codigo' => '004',
                'importe' => 15000.00,
            ],

            // Liquidación 2 - Empleado 2
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '001',
                'importe' => 250000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '002',
                'importe' => 50000.00,
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20234567890',
                'concepto_codigo' => '004',
                'importe' => 15000.00,
            ],
        ];

        foreach ($liquidacionConceptos as $liquidacionConcepto) {
            $liquidacionEmpleado = LiquidacionEmpleado::whereHas('liquidacion', function ($query) use ($liquidacionConcepto) {
                $query->where('numero', $liquidacionConcepto['liquidacion_numero']);
            })->whereHas('empleado.persona', function ($query) use ($liquidacionConcepto) {
                $query->where('cuil', $liquidacionConcepto['empleado_cuil']);
            })->first();

            $concepto = Concepto::where('codigo', $liquidacionConcepto['concepto_codigo'])->first();

            if ($liquidacionEmpleado && $concepto) {
                LiquidacionConcepto::create([
                    'liquidacion_empleado_id' => $liquidacionEmpleado->id,
                    'concepto_id' => $concepto->id,
                    'importe' => $liquidacionConcepto['importe'],
                ]);
            }
        }
    }
}

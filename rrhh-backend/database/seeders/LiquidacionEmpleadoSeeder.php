<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Liquidacion;
use App\Models\LiquidacionEmpleado;
use Illuminate\Database\Seeder;

class LiquidacionEmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $liquidacionEmpleados = [
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20123456789',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20234567890',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20345678901',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20456789012',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-001',
                'empleado_cuil' => '20567890123',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20123456789',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20234567890',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-002',
                'empleado_cuil' => '20345678901',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-003',
                'empleado_cuil' => '20123456789',
            ],
            [
                'liquidacion_numero' => 'LIQ-2024-003',
                'empleado_cuil' => '20234567890',
            ],
        ];

        foreach ($liquidacionEmpleados as $liquidacionEmpleado) {
            $liquidacion = Liquidacion::where('numero', $liquidacionEmpleado['liquidacion_numero'])->first();
            $empleado = Empleado::whereHas('persona', function ($query) use ($liquidacionEmpleado) {
                $query->where('cuil', $liquidacionEmpleado['empleado_cuil']);
            })->first();

            if ($liquidacion && $empleado) {
                LiquidacionEmpleado::create([
                    'liquidacion_id' => $liquidacion->id,
                    'empleado_id' => $empleado->id,
                ]);
            }
        }
    }
}

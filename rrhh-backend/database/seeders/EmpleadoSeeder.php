<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Persona;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = [
            [
                'persona_cuil' => '20123456789',
                'fecha_ingreso' => '2020-01-15',
                'titulo' => 'universitario',
            ],
            [
                'persona_cuil' => '20234567890',
                'fecha_ingreso' => '2020-03-20',
                'titulo' => 'universitario',
            ],
            [
                'persona_cuil' => '20345678901',
                'fecha_ingreso' => '2020-06-10',
                'titulo' => 'terciario',
            ],
            [
                'persona_cuil' => '20456789012',
                'fecha_ingreso' => '2021-01-05',
                'titulo' => 'universitario',
            ],
            [
                'persona_cuil' => '20567890123',
                'fecha_ingreso' => '2021-02-15',
                'titulo' => 'secundario',
            ],
            [
                'persona_cuil' => '20678901234',
                'fecha_ingreso' => '2021-04-01',
                'titulo' => 'universitario',
            ],
            [
                'persona_cuil' => '20789012345',
                'fecha_ingreso' => '2021-07-12',
                'titulo' => 'terciario',
            ],
            [
                'persona_cuil' => '20890123456',
                'fecha_ingreso' => '2022-01-20',
                'titulo' => 'universitario',
            ],
            [
                'persona_cuil' => '20901234567',
                'fecha_ingreso' => '2022-03-08',
                'titulo' => 'secundario',
            ],
            [
                'persona_cuil' => '20112345678',
                'fecha_ingreso' => '2022-05-15',
                'titulo' => 'sin_titulo',
            ],
        ];

        foreach ($empleados as $empleado) {
            $persona = Persona::where('cuil', $empleado['persona_cuil'])->first();
            if ($persona) {
                Empleado::create([
                    'persona_id' => $persona->id,
                    'fecha_ingreso' => $empleado['fecha_ingreso'],
                    'titulo' => $empleado['titulo'],
                ]);
            }
        }
    }
}

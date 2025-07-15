<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Persona;
use App\Models\Cargo;
use Illuminate\Database\Seeder;

class EmpleadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = [
            // Empleados 1-5: Administración
            [
                'cuil' => '20123456789',
                'fecha_ingreso' => '2020-01-15',
                'legajo' => 'L001',
                'titulo' => 'universitario',
                'cargo_id' => $administrativoSenior->id,
            ],
            [
                'cuil' => '20234567890',
                'fecha_ingreso' => '2021-03-20',
                'legajo' => 'L002',
                'titulo' => 'terciario',
            ],
            [
                'cuil' => '20345678901',
                'fecha_ingreso' => '2019-08-10',
                'legajo' => 'L003',
                'titulo' => 'secundario',
            ],
            [
                'cuil' => '20456789012',
                'fecha_ingreso' => '2022-01-05',
                'legajo' => 'L004',
                'titulo' => 'universitario',
            ],
            [
                'cuil' => '20567890123',
                'fecha_ingreso' => '2020-11-30',
                'legajo' => 'L005',
                'titulo' => 'terciario',
            ],

            // Empleados 6-10: Recursos Humanos
            [
                'cuil' => '20678901234',
                'fecha_ingreso' => '2018-06-15',
                'legajo' => 'L006',
                'titulo' => 'universitario',
                'cargo_id' => $analistaRRHH->id,
            ],
            [
                'cuil' => '20789012345',
                'fecha_ingreso' => '2021-09-22',
                'legajo' => 'L007',
                'titulo' => 'secundario',
            ],
            [
                'cuil' => '20890123456',
                'fecha_ingreso' => '2019-12-01',
                'legajo' => 'L008',
                'titulo' => 'universitario',
            ],
            [
                'cuil' => '20901234567',
                'fecha_ingreso' => '2022-04-18',
                'legajo' => 'L009',
                'titulo' => 'terciario',
            ],
            [
                'cuil' => '20112345678',
                'fecha_ingreso' => '2020-07-08',
                'legajo' => 'L010',
                'titulo' => 'secundario',
            ],
        ];

        foreach ($empleados as $empleado) {
            $persona = Persona::where('cuil', $empleado['cuil'])->first();
            if ($persona) {
                Empleado::create([
                    'persona_id' => $persona->id,
                    'fecha_ingreso' => $empleado['fecha_ingreso'],
                    'legajo' => $empleado['legajo'],
                    'titulo' => $empleado['titulo'],
                    'cargo_id' => $empleado['cargo_id'],
                ]);
            }
        }
    }
}

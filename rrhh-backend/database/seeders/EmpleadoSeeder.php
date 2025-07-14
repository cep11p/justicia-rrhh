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
        // Obtener los cargos
        $administrativoSenior = Cargo::where('nombre', 'Administrativo Senior')->first();
        $asistenteAdministrativo = Cargo::where('nombre', 'Asistente Administrativo')->first();
        $analistaRRHH = Cargo::where('nombre', 'Analista de RRHH')->first();
        $asistenteRRHH = Cargo::where('nombre', 'Asistente de RRHH')->first();

        // Empleados para Administración (5 empleados)
        $empleadosAdmin = [
            [
                'persona_cuil' => '20123456789',
                'fecha_ingreso' => '2020-01-15',
                'titulo' => 'universitario',
                'cargo_id' => $administrativoSenior->id,
            ],
            [
                'persona_cuil' => '20234567890',
                'fecha_ingreso' => '2020-03-20',
                'titulo' => 'universitario',
                'cargo_id' => $administrativoSenior->id,
            ],
            [
                'persona_cuil' => '20345678901',
                'fecha_ingreso' => '2020-06-10',
                'titulo' => 'terciario',
                'cargo_id' => $asistenteAdministrativo->id,
            ],
            [
                'persona_cuil' => '20456789012',
                'fecha_ingreso' => '2021-01-05',
                'titulo' => 'secundario',
                'cargo_id' => $asistenteAdministrativo->id,
            ],
            [
                'persona_cuil' => '20567890123',
                'fecha_ingreso' => '2021-02-15',
                'titulo' => 'secundario',
                'cargo_id' => $asistenteAdministrativo->id,
            ],
        ];

        // Empleados para Recursos Humanos (5 empleados)
        $empleadosRRHH = [
            [
                'persona_cuil' => '20678901234',
                'fecha_ingreso' => '2021-04-01',
                'titulo' => 'universitario',
                'cargo_id' => $analistaRRHH->id,
            ],
            [
                'persona_cuil' => '20789012345',
                'fecha_ingreso' => '2021-07-12',
                'titulo' => 'universitario',
                'cargo_id' => $analistaRRHH->id,
            ],
            [
                'persona_cuil' => '20890123456',
                'fecha_ingreso' => '2022-01-20',
                'titulo' => 'terciario',
                'cargo_id' => $asistenteRRHH->id,
            ],
            [
                'persona_cuil' => '20901234567',
                'fecha_ingreso' => '2022-03-08',
                'titulo' => 'secundario',
                'cargo_id' => $asistenteRRHH->id,
            ],
            [
                'persona_cuil' => '20112345678',
                'fecha_ingreso' => '2022-05-15',
                'titulo' => 'sin_titulo',
                'cargo_id' => $asistenteRRHH->id,
            ],
        ];

        // Crear empleados de Administración
        foreach ($empleadosAdmin as $empleado) {
            $persona = Persona::where('cuil', $empleado['persona_cuil'])->first();
            if ($persona) {
                Empleado::create([
                    'persona_id' => $persona->id,
                    'fecha_ingreso' => $empleado['fecha_ingreso'],
                    'titulo' => $empleado['titulo'],
                    'cargo_id' => $empleado['cargo_id'],
                ]);
            }
        }

        // Crear empleados de Recursos Humanos
        foreach ($empleadosRRHH as $empleado) {
            $persona = Persona::where('cuil', $empleado['persona_cuil'])->first();
            if ($persona) {
                Empleado::create([
                    'persona_id' => $persona->id,
                    'fecha_ingreso' => $empleado['fecha_ingreso'],
                    'titulo' => $empleado['titulo'],
                    'cargo_id' => $empleado['cargo_id'],
                ]);
            }
        }
    }
}

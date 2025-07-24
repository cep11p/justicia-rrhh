<?php

namespace Database\Seeders;

use App\Models\Designacion;
use App\Models\Empleado;
use App\Models\EstructuraOrganizativa;
use App\Models\Cargo;
use Illuminate\Database\Seeder;

class DesignacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener estructuras organizativas
        $administracion = EstructuraOrganizativa::where('nombre', 'Administración')->first();
        $recursosHumanos = EstructuraOrganizativa::where('nombre', 'Recursos Humanos')->first();

        // Obtener cargos
        $adminSenior = Cargo::where('nombre', 'Administrativo Senior')->first();
        $adminAsistente = Cargo::where('nombre', 'Asistente Administrativo')->first();
        $rrhhAnalista = Cargo::where('nombre', 'Analista de RRHH')->first();
        $rrhhAsistente = Cargo::where('nombre', 'Asistente de RRHH')->first();

        // Obtener empleados
        $empleados = Empleado::with('persona')->get();

        // Designaciones para Administración (empleados 1-5)
        $designacionesAdmin = [
            // Empleado 1: Administrativo Senior
            [
                'empleado_cuil' => '20123456789',
                'estructura' => $administracion,
                'cargo' => $adminSenior,
                'fecha_inicio' => '2020-01-15',
                'fecha_fin' => null,
            ],
            // Empleado 2: Asistente Administrativo
            [
                'empleado_cuil' => '20234567890',
                'estructura' => $administracion,
                'cargo' => $adminAsistente,
                'fecha_inicio' => '2021-03-20',
                'fecha_fin' => null,
            ],
            // Empleado 3: Administrativo Senior
            [
                'empleado_cuil' => '20345678901',
                'estructura' => $administracion,
                'cargo' => $adminSenior,
                'fecha_inicio' => '2019-08-10',
                'fecha_fin' => null,
            ],
            // Empleado 4: Asistente Administrativo
            [
                'empleado_cuil' => '20456789012',
                'estructura' => $administracion,
                'cargo' => $adminAsistente,
                'fecha_inicio' => '2022-01-05',
                'fecha_fin' => '2023-01-14',
            ],
            [
                'empleado_cuil' => '20456789012',
                'estructura' => $administracion,
                'cargo' => $adminAsistente,
                'fecha_inicio' => '2023-01-15',
                'fecha_fin' => null,
            ],
            // Empleado 5: Administrativo Senior
            [
                'empleado_cuil' => '20567890123',
                'estructura' => $administracion,
                'cargo' => $adminSenior,
                'fecha_inicio' => '2022-01-05',
                'fecha_fin' => '2023-01-14',
            ],
            [
                'empleado_cuil' => '20567890123',
                'estructura' => $administracion,
                'cargo' => $adminAsistente,
                'fecha_inicio' => '2023-01-15',
                'fecha_fin' => null,
            ],


            // Designaciones para Recursos Humanos (empleados 6-10)
            // Empleado 6: Analista de RRHH
            [
                'empleado_cuil' => '20678901234',
                'estructura' => $recursosHumanos,
                'cargo' => $rrhhAnalista,
                'fecha_inicio' => '2018-06-15',
                'fecha_fin' => null,
            ],
            // Empleado 7: Asistente de RRHH
            [
                'empleado_cuil' => '20789012345',
                'estructura' => $recursosHumanos,
                'cargo' => $rrhhAsistente,
                'fecha_inicio' => '2021-09-22',
                'fecha_fin' => null,
            ],
            // Empleado 8: Analista de RRHH
            [
                'empleado_cuil' => '20890123456',
                'estructura' => $recursosHumanos,
                'cargo' => $rrhhAnalista,
                'fecha_inicio' => '2019-12-01',
                'fecha_fin' => null,
            ],
            // Empleado 9: Asistente de RRHH
            [
                'empleado_cuil' => '20901234567',
                'estructura' => $recursosHumanos,
                'cargo' => $rrhhAsistente,
                'fecha_inicio' => '2022-04-18',
                'fecha_fin' => null,
            ],
            // Empleado 10: Analista de RRHH
            [
                'empleado_cuil' => '20112345678',
                'estructura' => $recursosHumanos,
                'cargo' => $rrhhAnalista,
                'fecha_inicio' => '2020-07-08',
                'fecha_fin' => null,
            ],
        ];

        foreach ($designacionesAdmin as $designacion) {
            $empleado = Empleado::whereHas('persona', function ($query) use ($designacion) {
                $query->where('cuil', $designacion['empleado_cuil']);
            })->first();

            if ($empleado) {
                Designacion::create([
                    'fecha_inicio' => $designacion['fecha_inicio'],
                    'fecha_fin' => $designacion['fecha_fin'],
                    'empleado_id' => $empleado->id,
                    'estructura_organizativa_id' => $designacion['estructura']->id,
                    'cargo_id' => $designacion['cargo']->id,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Designacion;
use App\Models\Empleado;
use App\Models\EstructuraOrganizativa;
use Illuminate\Database\Seeder;

class DesignacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designaciones = [
            [
                'empleado_cuil' => '20123456789',
                'estructura' => 'Dirección General',
                'cargo' => 'Director General',
                'fecha_inicio' => '2020-01-15',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20234567890',
                'estructura' => 'Recursos Humanos',
                'cargo' => 'Gerente de RRHH',
                'fecha_inicio' => '2020-03-20',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20345678901',
                'estructura' => 'Recursos Humanos',
                'cargo' => 'Analista de RRHH',
                'fecha_inicio' => '2020-06-10',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20456789012',
                'estructura' => 'Contabilidad',
                'cargo' => 'Contador',
                'fecha_inicio' => '2021-01-05',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20567890123',
                'estructura' => 'Contabilidad',
                'cargo' => 'Analista Contable',
                'fecha_inicio' => '2021-02-15',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20678901234',
                'estructura' => 'Sistemas',
                'cargo' => 'Desarrollador',
                'fecha_inicio' => '2021-04-01',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20789012345',
                'estructura' => 'Sistemas',
                'cargo' => 'Administrador de Sistemas',
                'fecha_inicio' => '2021-07-12',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20890123456',
                'estructura' => 'Administración',
                'cargo' => 'Administrativo',
                'fecha_inicio' => '2022-01-20',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20901234567',
                'estructura' => 'Mantenimiento',
                'cargo' => 'Técnico de Mantenimiento',
                'fecha_inicio' => '2022-03-08',
                'fecha_fin' => null,
            ],
            [
                'empleado_cuil' => '20112345678',
                'estructura' => 'Limpieza',
                'cargo' => 'Personal de Limpieza',
                'fecha_inicio' => '2022-05-15',
                'fecha_fin' => null,
            ],
        ];

        foreach ($designaciones as $designacion) {
            $empleado = Empleado::whereHas('persona', function ($query) use ($designacion) {
                $query->where('cuil', $designacion['empleado_cuil']);
            })->first();

            $estructura = EstructuraOrganizativa::where('nombre', $designacion['estructura'])->first();
            $cargo = Cargo::where('nombre', $designacion['cargo'])->first();

            if ($empleado && $estructura && $cargo) {
                Designacion::create([
                    'empleado_id' => $empleado->id,
                    'estructura_organizativa_id' => $estructura->id,
                    'cargo_id' => $cargo->id,
                    'fecha_inicio' => $designacion['fecha_inicio'],
                    'fecha_fin' => $designacion['fecha_fin'],
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cargos = [
            [
                'nombre' => 'Director General',
                'tiene_funcion' => true,
            ],
            [
                'nombre' => 'Gerente de RRHH',
                'tiene_funcion' => true,
            ],
            [
                'nombre' => 'Analista de RRHH',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Contador',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Analista Contable',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Desarrollador',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Administrador de Sistemas',
                'tiene_funcion' => true,
            ],
            [
                'nombre' => 'Administrativo',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Técnico de Mantenimiento',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Oficial de Seguridad',
                'tiene_funcion' => false,
            ],
            [
                'nombre' => 'Personal de Limpieza',
                'tiene_funcion' => false,
            ],
        ];

        foreach ($cargos as $cargo) {
            Cargo::create($cargo);
        }
    }
}

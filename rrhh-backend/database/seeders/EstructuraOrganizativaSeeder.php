<?php

namespace Database\Seeders;

use App\Models\EstructuraOrganizativa;
use Illuminate\Database\Seeder;

class EstructuraOrganizativaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estructuras = [
            [
                'nombre' => 'Dirección General',
            ],
            [
                'nombre' => 'Recursos Humanos',
            ],
            [
                'nombre' => 'Contabilidad',
            ],
            [
                'nombre' => 'Sistemas',
            ],
            [
                'nombre' => 'Administración',
            ],
            [
                'nombre' => 'Mantenimiento',
            ],
            [
                'nombre' => 'Seguridad',
            ],
            [
                'nombre' => 'Limpieza',
            ],
        ];

        foreach ($estructuras as $estructura) {
            EstructuraOrganizativa::create($estructura);
        }
    }
}

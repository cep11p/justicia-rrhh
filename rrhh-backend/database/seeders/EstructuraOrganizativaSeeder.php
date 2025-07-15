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
        // Estructura 1: Administración
        $administracion = EstructuraOrganizativa::create([
            'nombre' => 'Administración',
            'descripcion' => 'Departamento de administración y finanzas',
            'padre_id' => null,
        ]);

        // Estructura 2: Recursos Humanos
        $recursosHumanos = EstructuraOrganizativa::create([
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Departamento de recursos humanos y gestión del personal',
            'padre_id' => null,
        ]);
    }
}

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
        // Crear exactamente 2 estructuras organizacionales para la presentación
        EstructuraOrganizativa::create([
            'nombre' => 'Administración',
            'descripcion' => 'Departamento encargado de la gestión administrativa, procesos internos y control de gestión.',
            'padre_id' => null,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Departamento responsable de la gestión del personal, selección, capacitación y desarrollo organizacional.',
            'padre_id' => null,
        ]);
    }
}

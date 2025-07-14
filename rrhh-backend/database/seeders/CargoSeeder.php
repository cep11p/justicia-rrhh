<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\EstructuraOrganizativa;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener las estructuras organizacionales
        $administracion = EstructuraOrganizativa::where('nombre', 'Administración')->first();
        $recursosHumanos = EstructuraOrganizativa::where('nombre', 'Recursos Humanos')->first();

        // Cargos para Administración (2 cargos)
        Cargo::create([
            'nombre' => 'Administrativo Senior',
            'descripcion' => 'Responsable de la gestión administrativa avanzada y coordinación de procesos internos.',
            'tiene_funcion' => true,
            'estructura_organizativa_id' => $administracion->id,
        ]);

        Cargo::create([
            'nombre' => 'Asistente Administrativo',
            'descripcion' => 'Apoyo en tareas administrativas y gestión de documentación.',
            'tiene_funcion' => false,
            'estructura_organizativa_id' => $administracion->id,
        ]);

        // Cargos para Recursos Humanos (2 cargos)
        Cargo::create([
            'nombre' => 'Analista de RRHH',
            'descripcion' => 'Responsable de la gestión del personal, selección y desarrollo organizacional.',
            'tiene_funcion' => true,
            'estructura_organizativa_id' => $recursosHumanos->id,
        ]);

        Cargo::create([
            'nombre' => 'Asistente de RRHH',
            'descripcion' => 'Apoyo en tareas de recursos humanos y gestión de personal.',
            'tiene_funcion' => false,
            'estructura_organizativa_id' => $recursosHumanos->id,
        ]);
    }
}

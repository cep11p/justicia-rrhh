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
        // Cargos para Administración
        Cargo::create([
            'nombre' => 'Administrativo Senior',
            'descripcion' => 'Responsable de la gestión administrativa avanzada y coordinación de procesos internos',
            'tiene_funcion' => true,
        ]);

        Cargo::create([
            'nombre' => 'Asistente Administrativo',
            'descripcion' => 'Apoyo en tareas administrativas y gestión de documentación',
            'tiene_funcion' => false,
        ]);

        // Cargos para Recursos Humanos
        Cargo::create([
            'nombre' => 'Analista de RRHH',
            'descripcion' => 'Responsable de la gestión del personal, selección y desarrollo organizacional',
            'tiene_funcion' => true,
        ]);

        Cargo::create([
            'nombre' => 'Asistente de RRHH',
            'descripcion' => 'Apoyo en tareas de recursos humanos y gestión de personal',
            'tiene_funcion' => false,
        ]);
    }
}

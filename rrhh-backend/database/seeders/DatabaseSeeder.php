<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ejecutar seeders en orden de dependencias
        $this->call([
            PersonaSeeder::class,
            EstructuraOrganizativaSeeder::class,
            CargoSeeder::class,
            ConceptoSeeder::class,
            EmpleadoSeeder::class,
            DesignacionSeeder::class,
            ValorConceptoSeeder::class,
            LiquidacionSeeder::class,
            LiquidacionConceptoSeeder::class,
        ]);
    }
}

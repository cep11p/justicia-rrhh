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
        $this->call([
            // Seeders en orden de dependencias
            PersonaSeeder::class,
            EstructuraOrganizativaSeeder::class,
            CargoSeeder::class,
            ConceptoSeeder::class,
            EmpleadoSeeder::class,
            DesignacionSeeder::class,
            ValorConceptoSeeder::class,
            LiquidacionSeeder::class,
            LiquidacionEmpleadoSeeder::class,
            LiquidacionConceptoSeeder::class,
        ]);
    }
}

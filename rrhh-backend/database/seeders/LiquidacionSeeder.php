<?php

namespace Database\Seeders;

use App\Models\Liquidacion;
use Illuminate\Database\Seeder;

class LiquidacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear liquidación para diciembre 2024
        Liquidacion::create([
            'numero' => 1,
            'periodo' => '202412',
            'fecha_liquidacion' => '2024-12-31',
            'descripcion' => 'Liquidación de haberes diciembre 2024',
        ]);
    }
}

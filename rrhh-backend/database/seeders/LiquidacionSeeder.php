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
        $liquidaciones = [
            [
                'numero' => 'LIQ-2024-001',
                'periodo' => '202412',
            ],
            [
                'numero' => 'LIQ-2024-002',
                'periodo' => '202411',
            ],
            [
                'numero' => 'LIQ-2024-003',
                'periodo' => '202410',
            ],
        ];

        foreach ($liquidaciones as $liquidacion) {
            Liquidacion::create($liquidacion);
        }
    }
}

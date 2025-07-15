<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personas = [
            [
                'cuil' => '20123456789',
                'apellido' => 'González',
                'nombre' => 'Juan Carlos',
            ],
            [
                'cuil' => '20234567890',
                'apellido' => 'Martínez',
                'nombre' => 'María Elena',
            ],
            [
                'cuil' => '20345678901',
                'apellido' => 'López',
                'nombre' => 'Carlos Alberto',
            ],
            [
                'cuil' => '20456789012',
                'apellido' => 'Rodríguez',
                'nombre' => 'Ana Sofía',
            ],
            [
                'cuil' => '20567890123',
                'apellido' => 'Pérez',
                'nombre' => 'Roberto Daniel',
            ],
            [
                'cuil' => '20678901234',
                'apellido' => 'Fernández',
                'nombre' => 'Laura Beatriz',
            ],
            [
                'cuil' => '20789012345',
                'apellido' => 'García',
                'nombre' => 'Miguel Ángel',
            ],
            [
                'cuil' => '20890123456',
                'apellido' => 'Sánchez',
                'nombre' => 'Patricia Isabel',
            ],
            [
                'cuil' => '20901234567',
                'apellido' => 'Torres',
                'nombre' => 'Diego Alejandro',
            ],
            [
                'cuil' => '20112345678',
                'apellido' => 'Ruiz',
                'nombre' => 'Carmen Victoria',
            ],
        ];

        foreach ($personas as $persona) {
            Persona::create($persona);
        }
    }
}

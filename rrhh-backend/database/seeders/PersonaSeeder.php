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
                'nombre' => 'María',
            ],
            [
                'cuil' => '20234567890',
                'apellido' => 'López',
                'nombre' => 'Juan Carlos',
            ],
            [
                'cuil' => '20345678901',
                'apellido' => 'Martínez',
                'nombre' => 'Ana Sofía',
            ],
            [
                'cuil' => '20456789012',
                'apellido' => 'Rodríguez',
                'nombre' => 'Carlos Alberto',
            ],
            [
                'cuil' => '20567890123',
                'apellido' => 'Fernández',
                'nombre' => 'Laura Patricia',
            ],
            [
                'cuil' => '20678901234',
                'apellido' => 'Pérez',
                'nombre' => 'Roberto Daniel',
            ],
            [
                'cuil' => '20789012345',
                'apellido' => 'García',
                'nombre' => 'Silvia Beatriz',
            ],
            [
                'cuil' => '20890123456',
                'apellido' => 'Sánchez',
                'nombre' => 'Miguel Ángel',
            ],
            [
                'cuil' => '20901234567',
                'apellido' => 'Ramírez',
                'nombre' => 'Elena Victoria',
            ],
            [
                'cuil' => '20112345678',
                'apellido' => 'Torres',
                'nombre' => 'Fernando José',
            ],
        ];

        foreach ($personas as $persona) {
            Persona::create($persona);
        }
    }
}

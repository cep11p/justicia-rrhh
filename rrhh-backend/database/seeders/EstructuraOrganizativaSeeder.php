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
        // Estructuras de nivel 1 (Raíz)
        $direccionGeneral = EstructuraOrganizativa::create([
            'nombre' => 'Dirección General',
            'descripcion' => 'Máxima autoridad de la organización, responsable de la gestión estratégica y toma de decisiones ejecutivas.',
        ]);

        // Estructuras de nivel 2 (Hijas de Dirección General)
        $recursosHumanos = EstructuraOrganizativa::create([
            'nombre' => 'Recursos Humanos',
            'descripcion' => 'Departamento responsable de la gestión del capital humano, incluyendo selección, capacitación y desarrollo del personal.',
            'padre_id' => $direccionGeneral->id,
        ]);

        $finanzas = EstructuraOrganizativa::create([
            'nombre' => 'Finanzas',
            'descripcion' => 'Departamento encargado de la gestión financiera, contabilidad, presupuestos y control de costos.',
            'padre_id' => $direccionGeneral->id,
        ]);

        $tecnologia = EstructuraOrganizativa::create([
            'nombre' => 'Tecnología de la Información',
            'descripcion' => 'Departamento responsable de la infraestructura tecnológica, sistemas informáticos y soporte técnico.',
            'padre_id' => $direccionGeneral->id,
        ]);

        $operaciones = EstructuraOrganizativa::create([
            'nombre' => 'Operaciones',
            'descripcion' => 'Departamento encargado de las operaciones diarias, logística y servicios de apoyo.',
            'padre_id' => $direccionGeneral->id,
        ]);

        // Estructuras de nivel 3 (Hijas de Recursos Humanos)
        EstructuraOrganizativa::create([
            'nombre' => 'Selección y Reclutamiento',
            'descripcion' => 'Área especializada en la búsqueda, selección y contratación de personal calificado.',
            'padre_id' => $recursosHumanos->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Capacitación y Desarrollo',
            'descripcion' => 'Área responsable de la formación continua, desarrollo de competencias y planes de carrera.',
            'padre_id' => $recursosHumanos->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Nómina y Beneficios',
            'descripcion' => 'Área encargada de la gestión de nóminas, liquidaciones, beneficios sociales y compensaciones.',
            'padre_id' => $recursosHumanos->id,
        ]);

        // Estructuras de nivel 3 (Hijas de Finanzas)
        EstructuraOrganizativa::create([
            'nombre' => 'Contabilidad',
            'descripcion' => 'Área responsable de la contabilidad general, registros contables y reportes financieros.',
            'padre_id' => $finanzas->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Presupuestos',
            'descripcion' => 'Área encargada de la planificación presupuestaria, control de gastos y análisis financiero.',
            'padre_id' => $finanzas->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Tesorería',
            'descripcion' => 'Área responsable de la gestión de flujo de caja, inversiones y relaciones bancarias.',
            'padre_id' => $finanzas->id,
        ]);

        // Estructuras de nivel 3 (Hijas de Tecnología)
        EstructuraOrganizativa::create([
            'nombre' => 'Desarrollo de Sistemas',
            'descripcion' => 'Área especializada en el desarrollo de software, aplicaciones y sistemas informáticos.',
            'padre_id' => $tecnologia->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Infraestructura',
            'descripcion' => 'Área responsable de la infraestructura tecnológica, redes, servidores y equipos.',
            'padre_id' => $tecnologia->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Soporte Técnico',
            'descripcion' => 'Área encargada del soporte técnico a usuarios, mantenimiento de equipos y resolución de problemas.',
            'padre_id' => $tecnologia->id,
        ]);

        // Estructuras de nivel 3 (Hijas de Operaciones)
        EstructuraOrganizativa::create([
            'nombre' => 'Logística',
            'descripcion' => 'Área responsable de la gestión de inventarios, distribución y cadena de suministro.',
            'padre_id' => $operaciones->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Mantenimiento',
            'descripcion' => 'Área encargada del mantenimiento de instalaciones, equipos y servicios generales.',
            'padre_id' => $operaciones->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Seguridad',
            'descripcion' => 'Área responsable de la seguridad física, control de acceso y protección de activos.',
            'padre_id' => $operaciones->id,
        ]);

        // Estructuras de nivel 4 (Subáreas específicas)
        $seleccion = EstructuraOrganizativa::where('nombre', 'Selección y Reclutamiento')->first();
        EstructuraOrganizativa::create([
            'nombre' => 'Reclutamiento Externo',
            'descripcion' => 'Especialización en búsqueda de talento externo y gestión de procesos de selección.',
            'padre_id' => $seleccion->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Reclutamiento Interno',
            'descripcion' => 'Especialización en promociones internas y desarrollo de carrera dentro de la organización.',
            'padre_id' => $seleccion->id,
        ]);

        $desarrollo = EstructuraOrganizativa::where('nombre', 'Desarrollo de Sistemas')->first();
        EstructuraOrganizativa::create([
            'nombre' => 'Desarrollo Web',
            'descripcion' => 'Especialización en desarrollo de aplicaciones web y sistemas online.',
            'padre_id' => $desarrollo->id,
        ]);

        EstructuraOrganizativa::create([
            'nombre' => 'Desarrollo Móvil',
            'descripcion' => 'Especialización en desarrollo de aplicaciones móviles y sistemas multiplataforma.',
            'padre_id' => $desarrollo->id,
        ]);
    }
}

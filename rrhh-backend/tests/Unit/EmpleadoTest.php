<?php

namespace Tests\Unit;

use App\Models\Empleado;
use App\Models\Persona;
use App\Models\Designacion;
use App\Models\EstructuraOrganizativa;
use App\Models\Cargo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class EmpleadoTest extends TestCase
{
    use RefreshDatabase;

    protected Empleado $empleado;
    protected EstructuraOrganizativa $estructura;
    protected Cargo $cargo;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos básicos de prueba
        $persona = Persona::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'cuil' => '20123456789',
            'fecha_nacimiento' => '1990-01-01',
            'email' => 'juan.perez@test.com',
        ]);

        $this->estructura = EstructuraOrganizativa::create([
            'nombre' => 'Administración',
            'descripcion' => 'Departamento de administración',
            'padre_id' => null,
        ]);

        $this->cargo = Cargo::create([
            'nombre' => 'Administrativo',
            'tiene_funcion' => true,
        ]);

        $this->empleado = Empleado::create([
            'persona_id' => $persona->id,
            'fecha_ingreso' => '2020-01-01',
            'titulo' => 'universitario',
        ]);
    }

    /** @test */
    public function verificar_migraciones_funcionan()
    {
        // Verificar que las tablas existen
        $this->assertTrue(Schema::hasTable('empleados'));
        $this->assertTrue(Schema::hasTable('designaciones'));
        $this->assertTrue(Schema::hasTable('personas'));
        $this->assertTrue(Schema::hasTable('estructuras_organizativas'));
        $this->assertTrue(Schema::hasTable('cargos'));
    }

    /** @test */
    public function empleado_puede_crear_designacion()
    {
        // Arrange & Act
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Assert
        $this->assertDatabaseHas('designaciones', [
            'empleado_id' => $this->empleado->id,
            'cargo_id' => $this->cargo->id,
        ]);

        $this->assertEquals('2024-01-01', $designacion->fecha_inicio->format('Y-m-d'));
        $this->assertEquals('2024-12-31', $designacion->fecha_fin->format('Y-m-d'));
    }

    /** @test */
    public function getDesignacionParaPeriodo_devuelve_null_cuando_no_hay_designacion()
    {
        // Act
        $designacion = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert
        $this->assertNull($designacion);
    }

    /** @test */
    public function getDesignacionParaPeriodo_encuentra_designacion_simple()
    {
        // Arrange - Crear una designación que cubre todo el período
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Debug: verificar que la designación se creó
        $this->assertDatabaseHas('designaciones', [
            'empleado_id' => $this->empleado->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Debug: verificar que el cargo tiene valores
        $this->assertNotNull($this->cargo->id);
        $this->assertEquals('Administrativo', $this->cargo->nombre);
        $this->assertTrue($this->cargo->tiene_funcion);

        // Debug: verificar la relación del empleado
        $empleadoDesignaciones = $this->empleado->designaciones;
        $this->assertCount(1, $empleadoDesignaciones);

        // Act
        $designacionEncontrada = $this->empleado->getDesignacionParaPeriodo('202412');

        // Debug: imprimir información
        echo "Cargo ID: " . $this->cargo->id . "\n";
        echo "Empleado ID: " . $this->empleado->id . "\n";
        echo "Designación creada ID: " . $designacion->id . "\n";
        echo "Designación encontrada: " . ($designacionEncontrada ? $designacionEncontrada->id : 'null') . "\n";

        // Debug: verificar las fechas
        $periodo = '202412';
        $fechaInicioPeriodo = Carbon::createFromFormat('Ym', $periodo)->startOfMonth();
        $fechaFinPeriodo = Carbon::createFromFormat('Ym', $periodo)->endOfMonth();

        echo "Período: " . $periodo . "\n";
        echo "Fecha inicio período: " . $fechaInicioPeriodo->format('Y-m-d') . "\n";
        echo "Fecha fin período: " . $fechaFinPeriodo->format('Y-m-d') . "\n";
        echo "Designación fecha inicio: " . $designacion->fecha_inicio->format('Y-m-d') . "\n";
        echo "Designación fecha fin: " . $designacion->fecha_fin->format('Y-m-d') . "\n";

        // Debug: verificar las condiciones
        echo "Condición 1 (fecha_inicio <= inicio_periodo): " . ($designacion->fecha_inicio <= $fechaInicioPeriodo ? 'SÍ' : 'NO') . "\n";
        echo "Condición 2 (fecha_fin >= fin_periodo): " . ($designacion->fecha_fin >= $fechaFinPeriodo ? 'SÍ' : 'NO') . "\n";

        // Assert
        $this->assertNotNull($designacionEncontrada);
        $this->assertEquals($this->cargo->id, $designacionEncontrada->cargo_id);
    }

    /** @test */
    public function verificar_integridad_designacion_valor_concepto()
    {
        // Arrange - Crear concepto y valor concepto para el cargo
        $concepto = \App\Models\Concepto::create([
            'codigo' => '001',
            'descripcion' => 'Sueldo Básico',
            'tipo' => 'fijo',
            'es_remunerativo' => true,
        ]);

        $valorConcepto = \App\Models\ValorConcepto::create([
            'periodo' => '202412',
            'valor' => 50000.00,
            'concepto_id' => $concepto->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Crear designación para el empleado
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Act - Obtener designación vigente
        $designacionVigente = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert - Verificar que existe valor concepto para el cargo de la designación
        $this->assertNotNull($designacionVigente);
        $this->assertDatabaseHas('valor_conceptos', [
            'cargo_id' => $designacionVigente->cargo_id,
            'periodo' => '202412',
        ]);

        // Verificar que el valor concepto corresponde al cargo de la designación
        $valorConceptoEncontrado = \App\Models\ValorConcepto::where('cargo_id', $designacionVigente->cargo_id)
            ->where('periodo', '202412')
            ->first();

        $this->assertNotNull($valorConceptoEncontrado);
        $this->assertEquals(50000.00, $valorConceptoEncontrado->valor);
    }
}

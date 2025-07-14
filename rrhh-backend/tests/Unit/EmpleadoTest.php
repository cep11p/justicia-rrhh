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

class EmpleadoTest extends TestCase
{
    use RefreshDatabase;

    protected Empleado $empleado;
    protected EstructuraOrganizativa $estructura;
    protected Cargo $cargo1;
    protected Cargo $cargo2;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos de prueba
        $this->crearDatosDePrueba();
    }

    private function crearDatosDePrueba(): void
    {
        // Crear estructura organizativa
        $estructura = EstructuraOrganizativa::create([
            'nombre' => 'Administración',
            'descripcion' => 'Departamento de administración',
            'padre_id' => null,
        ]);

        // Crear cargos
        $cargo1 = Cargo::create([
            'nombre' => 'Administrativo Senior',
            'descripcion' => 'Cargo senior',
            'tiene_funcion' => true,
            'estructura_organizativa_id' => $estructura->id,
        ]);

        $cargo2 = Cargo::create([
            'nombre' => 'Asistente Administrativo',
            'descripcion' => 'Cargo asistente',
            'tiene_funcion' => false,
            'estructura_organizativa_id' => $estructura->id,
        ]);

        // Crear persona
        $persona = Persona::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'cuil' => '20123456789',
            'fecha_nacimiento' => '1990-01-01',
            'email' => 'juan.perez@test.com',
        ]);

        // Crear empleado
        $this->empleado = Empleado::create([
            'persona_id' => $persona->id,
            'fecha_ingreso' => '2020-01-01',
            'titulo' => 'universitario',
        ]);

        // Guardar referencias para los tests
        $this->estructura = $estructura;
        $this->cargo1 = $cargo1;
        $this->cargo2 = $cargo2;
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
    public function getDesignacionParaPeriodo_devuelve_designacion_vigente_para_periodo()
    {
        // Arrange - Crear designación vigente para diciembre 2024
        Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        // Act
        $designacion = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert
        $this->assertNotNull($designacion);
        $this->assertEquals($this->cargo1->id, $designacion->cargo_id);
        $this->assertEquals('2024-01-01', $designacion->fecha_inicio->format('Y-m-d'));
        $this->assertEquals('2024-12-31', $designacion->fecha_fin->format('Y-m-d'));
    }

    /** @test */
    public function getDesignacionParaPeriodo_devuelve_designacion_mas_reciente_cuando_hay_varias_historicas()
    {
        // Arrange - Crear múltiples designaciones históricas
        Designacion::create([
            'fecha_inicio' => '2023-01-01',
            'fecha_fin' => '2024-06-30',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        Designacion::create([
            'fecha_inicio' => '2024-07-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo2->id,
        ]);

        // Act - Buscar designación para diciembre 2024
        $designacion = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert - Debe devolver la designación más reciente (2024-07-01)
        $this->assertNotNull($designacion);
        $this->assertEquals($this->cargo2->id, $designacion->cargo_id);
        $this->assertEquals('2024-07-01', $designacion->fecha_inicio->format('Y-m-d'));
    }

    /** @test */
    public function getDesignacionParaPeriodo_funciona_con_designacion_sin_fecha_fin()
    {
        // Arrange - Crear designación sin fecha fin (vigente indefinidamente)
        Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null,
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        // Act
        $designacion = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert
        $this->assertNotNull($designacion);
        $this->assertEquals($this->cargo1->id, $designacion->cargo_id);
        $this->assertNull($designacion->fecha_fin);
    }

    /** @test */
    public function getDesignacionParaPeriodo_lanza_excepcion_con_formato_invalido()
    {
        // Assert
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('El período debe tener formato YYYYMM (ej: 202412)');

        // Act
        $this->empleado->getDesignacionParaPeriodo('2024');
    }

    /** @test */
    public function getDesignacionParaPeriodo_funciona_con_designacion_que_inicia_en_medio_del_periodo()
    {
        // Arrange - Designación que inicia en medio del mes
        Designacion::create([
            'fecha_inicio' => '2024-12-15',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        // Act
        $designacion = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert
        $this->assertNotNull($designacion);
        $this->assertEquals($this->cargo1->id, $designacion->cargo_id);
        $this->assertEquals('2024-12-15', $designacion->fecha_inicio->format('Y-m-d'));
    }

    /** @test */
    public function getDesignacionParaPeriodo_no_encuentra_designacion_fuera_del_rango()
    {
        // Arrange - Designación fuera del período buscado
        Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-06-30',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        // Act - Buscar para diciembre 2024
        $designacion = $this->empleado->getDesignacionParaPeriodo('202412');

        // Assert
        $this->assertNull($designacion);
    }

    /** @test */
    public function getDesignacionActual_devuelve_designacion_para_periodo_actual()
    {
        // Arrange - Crear designación para el período actual
        $periodoActual = now()->format('Ym');
        $fechaInicio = Carbon::createFromFormat('Ym', $periodoActual)->startOfMonth();
        $fechaFin = Carbon::createFromFormat('Ym', $periodoActual)->endOfMonth();

        Designacion::create([
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        // Act
        $designacion = $this->empleado->getDesignacionActual();

        // Assert
        $this->assertNotNull($designacion);
        $this->assertEquals($this->cargo1->id, $designacion->cargo_id);
    }

    /** @test */
    public function tieneDesignacionEnPeriodo_devuelve_true_cuando_hay_designacion()
    {
        // Arrange
        Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo1->id,
        ]);

        // Act
        $tieneDesignacion = $this->empleado->tieneDesignacionEnPeriodo('202412');

        // Assert
        $this->assertTrue($tieneDesignacion);
    }

    /** @test */
    public function tieneDesignacionEnPeriodo_devuelve_false_cuando_no_hay_designacion()
    {
        // Act
        $tieneDesignacion = $this->empleado->tieneDesignacionEnPeriodo('202412');

        // Assert
        $this->assertFalse($tieneDesignacion);
    }
}

<?php

namespace Tests\Unit;

use App\Models\Empleado;
use App\Models\Persona;
use App\Models\Designacion;
use App\Models\EstructuraOrganizativa;
use App\Models\Cargo;
use App\Models\Concepto;
use App\Models\ValorConcepto;
use App\Models\Liquidacion;
use App\Models\LiquidacionEmpleado;
use App\Models\LiquidacionConcepto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Schema;

class EmpleadoTest extends TestCase
{
    use RefreshDatabase;

    protected Empleado $empleado;
    protected Persona $persona;
    protected EstructuraOrganizativa $estructura;
    protected Cargo $cargo;
    protected Concepto $conceptoRemunerativo;
    protected Concepto $conceptoDescuento;
    protected ValorConcepto $valorConcepto;
    protected Liquidacion $liquidacion;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear datos básicos de prueba con integridad completa
        $this->crearDatosDePrueba();
    }

    private function crearDatosDePrueba(): void
    {
        // 1. Crear persona
        $this->persona = Persona::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'cuil' => '20123456789',
            'fecha_nacimiento' => '1990-01-01',
            'email' => 'juan.perez@test.com',
        ]);

        // 2. Crear estructura organizativa
        $this->estructura = EstructuraOrganizativa::create([
            'nombre' => 'Administración',
            'descripcion' => 'Departamento de administración',
            'padre_id' => null,
        ]);

        // 3. Crear cargo
        $this->cargo = Cargo::create([
            'nombre' => 'Administrativo',
            'descripcion' => 'Cargo administrativo',
            'tiene_funcion' => true,
        ]);

        // 4. Crear empleado
        $this->empleado = Empleado::create([
            'persona_id' => $this->persona->id,
            'fecha_ingreso' => '2020-01-01',
            'legajo' => 'EMP001',
            'titulo' => 'universitario',
        ]);

        // 5. Crear conceptos
        $this->conceptoRemunerativo = Concepto::create([
            'codigo' => '001',
            'nombre' => 'Básico',
            'descripcion' => 'Sueldo Básico',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'fijo',
        ]);

        $this->conceptoDescuento = Concepto::create([
            'codigo' => '002',
            'nombre' => 'Aporte Jubilatorio',
            'descripcion' => 'Aporte jubilatorio obligatorio',
            'tipo' => 'Descuento',
            'tipo_valor' => 'porcentual',
        ]);

        // 6. Crear valor concepto
        $this->valorConcepto = ValorConcepto::create([
            'periodo' => '202412',
            'valor' => 50000.00,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'cargo_id' => $this->cargo->id,
            'fecha_inicio' => '2024-12-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // 7. Crear liquidación
        $this->liquidacion = Liquidacion::create([
            'numero' => 1,
            'periodo' => '202412',
            'fecha_liquidacion' => '2024-12-31',
            'observaciones' => 'Liquidación de prueba',
        ]);
    }

    /** @test */
    public function verificar_existencia_todas_las_tablas()
    {
        // Verificar que todas las tablas existen
        $this->assertTrue(Schema::hasTable('empleados'));
        $this->assertTrue(Schema::hasTable('personas'));
        $this->assertTrue(Schema::hasTable('estructuras_organizativas'));
        $this->assertTrue(Schema::hasTable('cargos'));
        $this->assertTrue(Schema::hasTable('designaciones'));
        $this->assertTrue(Schema::hasTable('conceptos'));
        $this->assertTrue(Schema::hasTable('valor_conceptos'));
        $this->assertTrue(Schema::hasTable('liquidaciones'));
        $this->assertTrue(Schema::hasTable('liquidacion_empleados'));
        $this->assertTrue(Schema::hasTable('liquidacion_conceptos'));
    }

    /** @test */
    public function verificar_integridad_empleado_persona()
    {
        // Verificar que el empleado tiene persona asociada
        $this->assertNotNull($this->empleado->persona);
        $this->assertEquals($this->persona->id, $this->empleado->persona->id);
        $this->assertEquals('Pérez, Juan', $this->empleado->nombre_completo);
    }

    /** @test */
    public function verificar_integridad_designacion_completa()
    {
        // Crear designación
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Verificar que la designación se creó correctamente
        $this->assertDatabaseHas('designaciones', [
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Verificar relaciones
        $this->assertCount(1, $this->empleado->designaciones);
        $this->assertEquals($designacion->id, $this->empleado->designaciones->first()->id);
    }

    /** @test */
    public function verificar_integridad_conceptos_y_valores()
    {
        // Verificar que los conceptos se crearon correctamente
        $this->assertDatabaseHas('conceptos', [
            'codigo' => '001',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'fijo',
        ]);

        $this->assertDatabaseHas('conceptos', [
            'codigo' => '002',
            'tipo' => 'Descuento',
            'tipo_valor' => 'porcentual',
        ]);

        // Verificar que el valor concepto se creó correctamente
        $this->assertDatabaseHas('valor_conceptos', [
            'periodo' => '202412',
            'valor' => 50000.00,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'cargo_id' => $this->cargo->id,
        ]);
    }

    /** @test */
    public function verificar_integridad_liquidacion_completa()
    {
        // Crear liquidación de empleado
        $liquidacionEmpleado = LiquidacionEmpleado::create([
            'liquidacion_id' => $this->liquidacion->id,
            'empleado_id' => $this->empleado->id,
            'total_remunerativo' => 50000.00,
            'total_descuentos' => 5000.00,
            'neto' => 45000.00,
        ]);

        // Verificar que la liquidación de empleado se creó correctamente
        $this->assertDatabaseHas('liquidacion_empleados', [
            'liquidacion_id' => $this->liquidacion->id,
            'empleado_id' => $this->empleado->id,
        ]);

        // Crear concepto de liquidación
        $liquidacionConcepto = LiquidacionConcepto::create([
            'liquidacion_empleado_id' => $liquidacionEmpleado->id,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'importe' => $this->valorConcepto->valor,
        ]);

        // Verificar que el concepto de liquidación se creó correctamente
        $this->assertDatabaseHas('liquidacion_conceptos', [
            'liquidacion_empleado_id' => $liquidacionEmpleado->id,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'importe' => $this->valorConcepto->valor,
        ]);

        // Verificar que el importe es igual al valor de valor_conceptos
        $this->assertEquals($this->valorConcepto->valor, $liquidacionConcepto->importe);

        // Verificar relaciones
        $this->assertCount(1, $this->empleado->liquidaciones);
        $this->assertEquals($liquidacionEmpleado->id, $this->empleado->liquidaciones->first()->id);
    }

    /** @test */
    public function verificar_cadena_completa_integridad()
    {
        // Crear designación
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
            'empleado_id' => $this->empleado->id,
            'estructura_organizativa_id' => $this->estructura->id,
            'cargo_id' => $this->cargo->id,
        ]);

        // Crear liquidación de empleado
        $liquidacionEmpleado = LiquidacionEmpleado::create([
            'liquidacion_id' => $this->liquidacion->id,
            'empleado_id' => $this->empleado->id,
            'total_remunerativo' => 50000.00,
            'total_descuentos' => 5000.00,
            'neto' => 45000.00,
        ]);

        // Verificar que toda la cadena funciona
        $this->assertNotNull($this->empleado->persona);
        $this->assertCount(1, $this->empleado->designaciones);
        $this->assertCount(1, $this->empleado->liquidaciones);

        // Verificar que el cargo de la designación tiene valores de conceptos
        $valoresConceptos = ValorConcepto::where('cargo_id', $designacion->cargo_id)
            ->where('periodo', '202412')
            ->get();

        $this->assertCount(1, $valoresConceptos);
        $this->assertEquals(50000.00, $valoresConceptos->first()->valor);
    }

    /** @test */
    public function verificar_integridad_temporal_conceptos_liquidacion()
    {
        // Crear valores de conceptos para diferentes períodos
        $valorConceptoNoviembre = ValorConcepto::create([
            'periodo' => '202411',
            'valor' => 45000.00,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'cargo_id' => $this->cargo->id,
            'fecha_inicio' => '2024-11-01',
            'fecha_fin' => '2024-11-30',
        ]);

        $valorConceptoDiciembre = ValorConcepto::create([
            'periodo' => '202412',
            'valor' => 50000.00,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'cargo_id' => $this->cargo->id,
            'fecha_inicio' => '2024-12-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear liquidación de noviembre
        $liquidacionNoviembre = Liquidacion::create([
            'numero' => 2,
            'periodo' => '202411',
            'fecha_liquidacion' => '2024-11-30',
            'observaciones' => 'Liquidación noviembre 2024',
        ]);

        $liquidacionEmpleadoNoviembre = LiquidacionEmpleado::create([
            'liquidacion_id' => $liquidacionNoviembre->id,
            'empleado_id' => $this->empleado->id,
            'total_remunerativo' => 45000.00,
            'total_descuentos' => 4500.00,
            'neto' => 40500.00,
        ]);

        // Crear concepto de liquidación para noviembre (debe usar valor de noviembre)
        $liquidacionConceptoNoviembre = LiquidacionConcepto::create([
            'liquidacion_empleado_id' => $liquidacionEmpleadoNoviembre->id,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'importe' => $valorConceptoNoviembre->valor,
        ]);

        // Crear concepto de liquidación para diciembre (debe usar valor de diciembre)
        $liquidacionEmpleadoDiciembre = LiquidacionEmpleado::create([
            'liquidacion_id' => $this->liquidacion->id,
            'empleado_id' => $this->empleado->id,
            'total_remunerativo' => 50000.00,
            'total_descuentos' => 5000.00,
            'neto' => 45000.00,
        ]);

        $liquidacionConceptoDiciembre = LiquidacionConcepto::create([
            'liquidacion_empleado_id' => $liquidacionEmpleadoDiciembre->id,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'importe' => $valorConceptoDiciembre->valor,
        ]);

        // Verificar integridad temporal: noviembre
        $this->assertEquals($valorConceptoNoviembre->valor, $liquidacionConceptoNoviembre->importe);
        $this->assertEquals(45000.00, $liquidacionConceptoNoviembre->importe);

        // Verificar integridad temporal: diciembre
        $this->assertEquals($valorConceptoDiciembre->valor, $liquidacionConceptoDiciembre->importe);
        $this->assertEquals(50000.00, $liquidacionConceptoDiciembre->importe);

        // Verificar que los valores son diferentes entre períodos
        $this->assertNotEquals(
            $liquidacionConceptoNoviembre->importe,
            $liquidacionConceptoDiciembre->importe
        );

        // Verificar que cada liquidación usa el valor correcto del período
        $this->assertDatabaseHas('liquidacion_conceptos', [
            'liquidacion_empleado_id' => $liquidacionEmpleadoNoviembre->id,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'importe' => 45000.00,
        ]);

        $this->assertDatabaseHas('liquidacion_conceptos', [
            'liquidacion_empleado_id' => $liquidacionEmpleadoDiciembre->id,
            'concepto_id' => $this->conceptoRemunerativo->id,
            'importe' => 50000.00,
        ]);
    }
}

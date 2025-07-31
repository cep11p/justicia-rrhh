<?php

namespace Tests\Unit;

use App\Models\Liquidacion;
use App\Models\Empleado;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiquidacionFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica que el factory crea automáticamente un empleado
     */
    public function test_factory_crea_empleado_automaticamente(): void
    {
        // Crear una liquidación usando el factory
        $liquidacion = Liquidacion::factory()->create();

        // Verificar que se creó un empleado
        $this->assertNotNull($liquidacion->empleado);
        $this->assertInstanceOf(Empleado::class, $liquidacion->empleado);

        // Verificar que la relación funciona correctamente
        $this->assertEquals($liquidacion->empleado_id, $liquidacion->empleado->id);
    }

    /**
     * Test que verifica que el factory puede usar un empleado existente
     */
    public function test_factory_puede_usar_empleado_existente(): void
    {
        // Crear un empleado primero
        $empleado = Empleado::factory()->create();

        // Crear una liquidación para ese empleado específico
        $liquidacion = Liquidacion::factory()->paraEmpleado($empleado)->create();

        // Verificar que usa el empleado correcto
        $this->assertEquals($empleado->id, $liquidacion->empleado_id);
        $this->assertEquals($empleado->id, $liquidacion->empleado->id);
    }

    /**
     * Test que verifica que el factory crea empleado con dependencias
     */
    public function test_factory_crea_empleado_con_dependencias(): void
    {
        // Crear una liquidación
        $liquidacion = Liquidacion::factory()->create();

        // Verificar que el empleado tiene sus dependencias básicas
        $empleado = $liquidacion->empleado;

        // Verificar que tiene persona
        $this->assertNotNull($empleado->persona);

        // Verificar que tiene designaciones (puede estar vacío pero la relación existe)
        $this->assertIsObject($empleado->designaciones);
    }

    /**
     * Test que verifica el método conEmpleadoExistente
     */
    public function test_metodo_con_empleado_existente(): void
    {
        // Crear algunos empleados primero
        $empleados = Empleado::factory()->count(3)->create();

        // Crear liquidación usando empleado existente
        $liquidacion = Liquidacion::factory()->conEmpleadoExistente()->create();

        // Verificar que usa uno de los empleados existentes
        $this->assertTrue($empleados->contains($liquidacion->empleado));
    }

        /**
     * Test que verifica que se crea empleado si no existen
     */
    public function test_crea_empleado_si_no_existen(): void
    {
        // No crear empleados previamente

        // Crear liquidación usando empleado existente
        $liquidacion = Liquidacion::factory()->conEmpleadoExistente()->create();

        // Verificar que se creó un empleado
        $this->assertNotNull($liquidacion->empleado);
        $this->assertInstanceOf(Empleado::class, $liquidacion->empleado);
    }

    /**
     * Test que verifica que se crean conceptos básicos automáticamente
     */
    public function test_crea_conceptos_basicos_automaticamente(): void
    {
        // Crear un valor_concepto para el código "001"
        $concepto = \App\Models\Concepto::factory()->basico();
        \App\Models\ValorConcepto::factory()->create([
            'concepto_id' => $concepto->id,
            'valor' => 150000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear liquidación con conceptos básicos
        $liquidacion = Liquidacion::factory()
            ->paraPeriodo('202401')
            ->conConceptosBasicos()
            ->create();

        // Verificar que se crearon los conceptos
        $this->assertGreaterThan(0, $liquidacion->liquidacionConceptos()->count());

        // Verificar que tiene el concepto básico
        $conceptoBasico = $liquidacion->liquidacionConceptos()
            ->whereHas('concepto', function($query) {
                $query->where('codigo', '001');
            })
            ->first();

        $this->assertNotNull($conceptoBasico);
        $this->assertEquals(150000.00, $conceptoBasico->importe);
    }

    /**
     * Test que verifica que se crean conceptos específicos
     */
    public function test_crea_conceptos_especificos(): void
    {
        // Crear un valor_concepto para el código "001"
        $concepto = \App\Models\Concepto::factory()->basico();
        \App\Models\ValorConcepto::factory()->create([
            'concepto_id' => $concepto->id,
            'valor' => 200000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear liquidación con conceptos específicos
        $liquidacion = Liquidacion::factory()
            ->paraPeriodo('202401')
            ->conConceptos(['001', '002'])
            ->create();

        // Verificar que se crearon exactamente 2 conceptos
        $this->assertEquals(2, $liquidacion->liquidacionConceptos()->count());

        // Verificar que tiene los conceptos correctos
        $codigos = $liquidacion->liquidacionConceptos()
            ->with('concepto')
            ->get()
            ->pluck('concepto.codigo')
            ->toArray();

        $this->assertContains('001', $codigos);
        $this->assertContains('002', $codigos);
    }

        /**
     * Test que verifica liquidación completa
     */
    public function test_liquidacion_completa(): void
    {
        // Crear un valor_concepto para el código "001"
        $concepto = \App\Models\Concepto::factory()->basico();
        \App\Models\ValorConcepto::factory()->create([
            'concepto_id' => $concepto->id,
            'valor' => 180000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear liquidación completa
        $liquidacion = Liquidacion::factory()
            ->paraPeriodo('202401')
            ->completa()
            ->create();

        // Verificar que se crearon múltiples conceptos
        $this->assertGreaterThan(5, $liquidacion->liquidacionConceptos()->count());

        // Verificar que tiene conceptos remunerativos y descuentos
        $remunerativos = $liquidacion->liquidacionConceptos()
            ->whereHas('concepto', function($query) {
                $query->where('tipo', 'Remunerativo');
            })
            ->count();

        $descuentos = $liquidacion->liquidacionConceptos()
            ->whereHas('concepto', function($query) {
                $query->where('tipo', 'Descuento');
            })
            ->count();

        $this->assertGreaterThan(0, $remunerativos);
        $this->assertGreaterThan(0, $descuentos);
    }

    /**
     * Test que verifica la relación padre-hijo entre conceptos
     */
    public function test_relacion_padre_hijo_conceptos(): void
    {
        // Crear valor_concepto para básico (001)
        $conceptoBasico = \App\Models\Concepto::factory()->basico();
        \App\Models\ValorConcepto::factory()->create([
            'concepto_id' => $conceptoBasico->id,
            'valor' => 200000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear valor_concepto para adicional por función (002)
        $conceptoFuncion = \App\Models\Concepto::factory()->create([
            'codigo' => '002',
            'nombre' => 'ADICIONAL POR FUNCIÓN',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'porcentual',
        ]);
        \App\Models\ValorConcepto::factory()->create([
            'concepto_id' => $conceptoFuncion->id,
            'valor' => 5.0, // 5%
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear liquidación con conceptos básico y función
        $liquidacion = Liquidacion::factory()
            ->paraPeriodo('202401')
            ->conConceptos(['001', '002'])
            ->create();

        // Obtener los conceptos creados
        $conceptoBasicoLiquidacion = $liquidacion->liquidacionConceptos()
            ->whereHas('concepto', function($query) {
                $query->where('codigo', '001');
            })
            ->first();

        $conceptoFuncionLiquidacion = $liquidacion->liquidacionConceptos()
            ->whereHas('concepto', function($query) {
                $query->where('codigo', '002');
            })
            ->first();

        // Verificar que el concepto básico no tiene padre
        $this->assertNull($conceptoBasicoLiquidacion->padre_id);

        // Verificar que el concepto función tiene como padre al básico
        $this->assertEquals($conceptoBasicoLiquidacion->id, $conceptoFuncionLiquidacion->padre_id);

        // Verificar que el importe del concepto función es 5% del básico
        $importeEsperado = 200000.00 * 0.05; // 5% de 200000
        $this->assertEquals($importeEsperado, $conceptoFuncionLiquidacion->importe);

        // Verificar la relación padre-hijo
        $this->assertEquals($conceptoBasicoLiquidacion->id, $conceptoFuncionLiquidacion->padre->id);
        $this->assertTrue($conceptoBasicoLiquidacion->hijos->contains($conceptoFuncionLiquidacion));
    }

    /**
     * Test que verifica que se lanza excepción si no existe el básico
     */
    public function test_lanza_excepcion_sin_concepto_basico(): void
    {
        // Crear valor_concepto para función (002) pero NO para básico (001)
        $conceptoFuncion = \App\Models\Concepto::factory()->create([
            'codigo' => '002',
            'nombre' => 'ADICIONAL POR FUNCIÓN',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'porcentual',
        ]);
        \App\Models\ValorConcepto::factory()->create([
            'concepto_id' => $conceptoFuncion->id,
            'valor' => 5.0,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Verificar que se lance una excepción al intentar crear solo el concepto 002
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No se puede calcular el concepto '002' sin el concepto básico (001)");

        $liquidacion = Liquidacion::factory()
            ->paraPeriodo('202401')
            ->conConceptos(['002'])
            ->create();
    }
}

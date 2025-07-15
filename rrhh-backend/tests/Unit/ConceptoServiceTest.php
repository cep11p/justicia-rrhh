<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ConceptoService;
use App\Models\Empleado;
use App\Models\Cargo;
use App\Models\EstructuraOrganizativa;
use App\Models\Concepto;
use App\Models\ValorConcepto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ConceptoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ConceptoService $conceptoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->conceptoService = new ConceptoService();
    }

    public function test_calcular_basico_empleado_mes_completo()
    {
        // Crear datos de prueba
        $empleado = Empleado::factory()->create();
        $cargo = Cargo::factory()->create();
        $estructura = EstructuraOrganizativa::factory()->create();

        // Crear concepto de salario básico
        $conceptoSalarioBasico = Concepto::create([
            'codigo' => '001',
            'nombre' => 'Salario Básico',
            'descripcion' => 'Salario básico del empleado',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'fijo'
        ]);

        // Crear valor del concepto para el cargo
        $valorConcepto = ValorConcepto::create([
            'periodo' => '202401',
            'valor' => 1000.00,
            'concepto_id' => $conceptoSalarioBasico->id,
            'cargo_id' => $cargo->id,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null
        ]);

        // Crear designación que cubre todo el mes
        $empleado->designaciones()->create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null,
            'estructura_organizativa_id' => $estructura->id,
            'cargo_id' => $cargo->id
        ]);

        // Calcular básico
        $resultado = $this->conceptoService->calcularBasico($empleado, '202401');

        // Verificar que el resultado es correcto
        $this->assertCount(1, $resultado);
        $this->assertEquals($estructura->nombre, $resultado[0]['estructura_organizacional']);
        $this->assertEquals($cargo->nombre, $resultado[0]['cargo']);
        $this->assertEquals(1000.00, $resultado[0]['importe']);

        // Verificar que las fechas son correctas
        $this->assertEquals('2024-01-01', $resultado[0]['periodo_fecha_inicio']->format('Y-m-d'));
        $this->assertEquals('2024-01-31', $resultado[0]['periodo_fecha_fin']->format('Y-m-d'));
    }

    public function test_calcular_basico_empleado_mes_parcial()
    {
        // Crear datos de prueba
        $empleado = Empleado::factory()->create();
        $cargo = Cargo::factory()->create();
        $estructura = EstructuraOrganizativa::factory()->create();

        // Crear concepto de salario básico
        $conceptoSalarioBasico = Concepto::create([
            'codigo' => '001',
            'nombre' => 'Salario Básico',
            'descripcion' => 'Salario básico del empleado',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'fijo'
        ]);

        // Crear valor del concepto para el cargo
        $valorConcepto = ValorConcepto::create([
            'periodo' => '202401',
            'valor' => 1000.00,
            'concepto_id' => $conceptoSalarioBasico->id,
            'cargo_id' => $cargo->id,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null
        ]);

        // Crear designación que empieza a mitad del mes
        $empleado->designaciones()->create([
            'fecha_inicio' => '2024-01-15',
            'fecha_fin' => null,
            'estructura_organizativa_id' => $estructura->id,
            'cargo_id' => $cargo->id
        ]);

        // Calcular básico
        $resultado = $this->conceptoService->calcularBasico($empleado, '202401');

        // Verificar que el resultado es correcto
        $this->assertCount(1, $resultado);
        $this->assertEquals($estructura->nombre, $resultado[0]['estructura_organizacional']);
        $this->assertEquals($cargo->nombre, $resultado[0]['cargo']);

        // Verificar que el importe es proporcional (del 15 al 31)
        $diasTrabajados = $resultado[0]['periodo_fecha_inicio']->diffInDays($resultado[0]['periodo_fecha_fin']) + 1;
        $diasTotales = $resultado[0]['periodo_fecha_inicio']->copy()->startOfMonth()->diffInDays($resultado[0]['periodo_fecha_inicio']->copy()->endOfMonth()) + 1;
        $importeEsperado = 1000.00 * ($diasTrabajados / $diasTotales);

        $this->assertEquals(round($importeEsperado, 2), round($resultado[0]['importe'], 2));

        // Verificar que las fechas son correctas
        $this->assertEquals('2024-01-15', $resultado[0]['periodo_fecha_inicio']->format('Y-m-d'));
        $this->assertEquals('2024-01-31', $resultado[0]['periodo_fecha_fin']->format('Y-m-d'));
    }

    public function test_calcular_basico_empleado_sin_designaciones()
    {
        // Crear empleado sin designaciones
        $empleado = Empleado::factory()->create();

        // Calcular básico
        $resultado = $this->conceptoService->calcularBasico($empleado, '202401');

        // Verificar que retorna array vacío
        $this->assertEmpty($resultado);
    }

    public function test_calcular_basico_empleado_multiples_designaciones()
    {
        // Crear datos de prueba
        $empleado = Empleado::factory()->create();
        $cargo1 = Cargo::factory()->create(['nombre' => 'Administrativo']);
        $cargo2 = Cargo::factory()->create(['nombre' => 'Supervisor']);
        $estructura1 = EstructuraOrganizativa::factory()->create(['nombre' => 'Administración']);
        $estructura2 = EstructuraOrganizativa::factory()->create(['nombre' => 'Supervisión']);

        // Crear concepto de salario básico
        $conceptoSalarioBasico = Concepto::create([
            'codigo' => '001',
            'nombre' => 'Salario Básico',
            'descripcion' => 'Salario básico del empleado',
            'tipo' => 'Remunerativo',
            'tipo_valor' => 'fijo'
        ]);

        // Crear valores del concepto para ambos cargos
        $valorConcepto1 = ValorConcepto::create([
            'periodo' => '202401',
            'valor' => 1000.00, // Salario básico cargo 1
            'concepto_id' => $conceptoSalarioBasico->id,
            'cargo_id' => $cargo1->id,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null
        ]);

        $valorConcepto2 = ValorConcepto::create([
            'periodo' => '202401',
            'valor' => 1500.00, // Salario básico cargo 2 (más alto)
            'concepto_id' => $conceptoSalarioBasico->id,
            'cargo_id' => $cargo2->id,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null
        ]);

        // Crear primera designación: Administrativo del 1 al 15 de enero
        $empleado->designaciones()->create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-01-15',
            'estructura_organizativa_id' => $estructura1->id,
            'cargo_id' => $cargo1->id
        ]);

        // Crear segunda designación: Supervisor del 16 al 31 de enero
        $empleado->designaciones()->create([
            'fecha_inicio' => '2024-01-16',
            'fecha_fin' => null,
            'estructura_organizativa_id' => $estructura2->id,
            'cargo_id' => $cargo2->id
        ]);

        // Calcular básico
        $resultado = $this->conceptoService->calcularBasico($empleado, '202401');

        // Verificar que el resultado tiene 2 designaciones
        $this->assertCount(2, $resultado);

        // Verificar primera designación (Supervisor: 16-31 enero) - más reciente primero
        $this->assertEquals($estructura2->nombre, $resultado[0]['estructura_organizacional']);
        $this->assertEquals($cargo2->nombre, $resultado[0]['cargo']);
        $this->assertEquals('2024-01-16', $resultado[0]['periodo_fecha_inicio']->format('Y-m-d'));
        $this->assertEquals('2024-01-31', $resultado[0]['periodo_fecha_fin']->format('Y-m-d'));

        // Verificar segunda designación (Administrativo: 1-15 enero) - más antigua segundo
        $this->assertEquals($estructura1->nombre, $resultado[1]['estructura_organizacional']);
        $this->assertEquals($cargo1->nombre, $resultado[1]['cargo']);
        $this->assertEquals('2024-01-01', $resultado[1]['periodo_fecha_inicio']->format('Y-m-d'));
        $this->assertEquals('2024-01-15', $resultado[1]['periodo_fecha_fin']->format('Y-m-d'));

        // Verificar que los importes son proporcionales usando los valores reales calculados
        $diasTrabajados1 = $resultado[0]['periodo_fecha_inicio']->copy()->startOfDay()->diffInDays($resultado[0]['periodo_fecha_fin']->copy()->startOfDay()) + 1;
        $diasTrabajados2 = $resultado[1]['periodo_fecha_inicio']->copy()->startOfDay()->diffInDays($resultado[1]['periodo_fecha_fin']->copy()->startOfDay()) + 1;
        $diasTotales = 31;

        // Cargo 2 (Supervisor): días reales trabajados
        $importeEsperado2 = 1500.00 * ($diasTrabajados1 / $diasTotales);
        $this->assertEquals(round($importeEsperado2, 2), round($resultado[0]['importe'], 2));

        // Cargo 1 (Administrativo): días reales trabajados
        $importeEsperado1 = 1000.00 * ($diasTrabajados2 / $diasTotales);
        $this->assertEquals(round($importeEsperado1, 2), round($resultado[1]['importe'], 2));

        // Verificar que están ordenadas por fecha de inicio descendente (más reciente primero)
        $this->assertTrue($resultado[0]['periodo_fecha_inicio']->greaterThan($resultado[1]['periodo_fecha_inicio']));
    }
}

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
}

<?php

namespace Tests\Unit;

use App\Models\Concepto;
use App\Models\LiquidacionConcepto;
use App\Models\ValorConcepto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LiquidacionConceptoFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica que el factory usa el valor_concepto para conceptos con código "001"
     */
    public function test_factory_usa_valor_concepto_para_codigo_001(): void
    {
        // Crear un concepto con código "001"
        $concepto = Concepto::factory()->basico();

        // Crear un valor_concepto para este concepto
        $valorConcepto = ValorConcepto::factory()->create([
            'concepto_id' => $concepto->id,
            'valor' => 150000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Crear una liquidación con período que coincida con el valor_concepto
        $liquidacion = \App\Models\Liquidacion::factory()->create([
            'periodo' => '202401',
        ]);

        // Crear un LiquidacionConcepto usando el factory
        $liquidacionConcepto = LiquidacionConcepto::factory()->create([
            'liquidacion_id' => $liquidacion->id,
            'concepto_id' => $concepto->id,
        ]);

        // Verificar que el importe sea igual al valor del valor_concepto
        $this->assertEquals($valorConcepto->valor, $liquidacionConcepto->importe);
    }

        /**
     * Test que verifica que el factory usa valor aleatorio para conceptos sin código "001"
     */
    public function test_factory_usa_valor_aleatorio_para_otros_codigos(): void
    {
        // Crear un concepto con código diferente a "001"
        $concepto = Concepto::factory()->create([
            'codigo' => '002',
            'nombre' => 'OTRO CONCEPTO',
        ]);

        // Crear una liquidación
        $liquidacion = \App\Models\Liquidacion::factory()->create([
            'periodo' => '202401',
        ]);

        // Crear un LiquidacionConcepto usando el factory
        $liquidacionConcepto = LiquidacionConcepto::factory()->create([
            'liquidacion_id' => $liquidacion->id,
            'concepto_id' => $concepto->id,
        ]);

        // Verificar que el importe no sea 0 y esté en el rango esperado
        $this->assertGreaterThan(0, $liquidacionConcepto->importe);
        $this->assertLessThanOrEqual(500000, $liquidacionConcepto->importe);
        $this->assertGreaterThanOrEqual(1000, $liquidacionConcepto->importe);
    }

    /**
     * Test que verifica que el factory lanza excepción cuando no encuentra valor_concepto para código "001"
     */
    public function test_factory_lanza_excepcion_sin_valor_concepto_para_codigo_001(): void
    {
        // Crear un concepto con código "001" pero sin valor_concepto
        $concepto = Concepto::factory()->basico();

        // Crear una liquidación
        $liquidacion = \App\Models\Liquidacion::factory()->create([
            'periodo' => '202401',
        ]);

        // Verificar que se lance una excepción
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("No se encontró un valor_concepto para el concepto con código '001' en el período '202401'");

        // Intentar crear un LiquidacionConcepto usando el factory
        LiquidacionConcepto::factory()->create([
            'liquidacion_id' => $liquidacion->id,
            'concepto_id' => $concepto->id,
        ]);
    }

    /**
     * Test que verifica el método conCodigo001 del factory
     */
    public function test_metodo_con_codigo_001(): void
    {
        // Crear un valor_concepto para código "001"
        $concepto = Concepto::factory()->basico();
        $valorConcepto = ValorConcepto::factory()->create([
            'concepto_id' => $concepto->id,
            'valor' => 200000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Usar el método específico del factory
        $liquidacionConcepto = LiquidacionConcepto::factory()->conCodigo001()->create();

        // Verificar que el concepto tenga código "001"
        $this->assertEquals('001', $liquidacionConcepto->concepto->codigo);

        // Verificar que el importe sea igual al valor del valor_concepto
        $this->assertEquals($valorConcepto->valor, $liquidacionConcepto->importe);
    }

    /**
     * Test que verifica que el método conConcepto maneja correctamente el código "001"
     */
    public function test_metodo_con_concepto_para_codigo_001(): void
    {
        // Crear un concepto con código "001"
        $concepto = Concepto::factory()->basico();

        // Crear un valor_concepto para este concepto
        $valorConcepto = ValorConcepto::factory()->create([
            'concepto_id' => $concepto->id,
            'valor' => 175000.00,
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => '2024-12-31',
        ]);

        // Usar el método conConcepto del factory
        $liquidacionConcepto = LiquidacionConcepto::factory()->conConcepto($concepto)->create();

        // Verificar que el concepto sea el correcto
        $this->assertEquals($concepto->id, $liquidacionConcepto->concepto_id);

        // Verificar que el importe sea igual al valor del valor_concepto
        $this->assertEquals($valorConcepto->valor, $liquidacionConcepto->importe);
    }
}

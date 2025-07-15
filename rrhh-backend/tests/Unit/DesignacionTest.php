<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Designacion;
use App\Models\Empleado;
use App\Models\Cargo;
use App\Models\EstructuraOrganizativa;
use App\Models\Concepto;
use App\Models\ValorConcepto;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DesignacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_importe_designacion_calcula_correctamente()
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
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null,
            'empleado_id' => $empleado->id,
            'estructura_organizativa_id' => $estructura->id,
            'cargo_id' => $cargo->id
        ]);

        // Fechas del período (enero 2024)
        $periodoInicio = Carbon::create(2024, 1, 1);
        $periodoFin = Carbon::create(2024, 1, 31);

        // Calcular importe
        $importe = $designacion->getImporteDesginacion($periodoInicio, $periodoFin);

        // Verificar que el importe es correcto (100% del mes = 1000.00)
        $this->assertEquals(1000.00, $importe);
    }

    public function test_get_importe_designacion_calcula_parcial_correctamente()
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

        // Crear designación que empieza a mitad del mes (15 de enero)
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-15',
            'fecha_fin' => null,
            'empleado_id' => $empleado->id,
            'estructura_organizativa_id' => $estructura->id,
            'cargo_id' => $cargo->id
        ]);

        // Fechas del período (enero 2024)
        $periodoInicio = Carbon::create(2024, 1, 1);
        $periodoFin = Carbon::create(2024, 1, 31);

        // Calcular importe
        $importe = $designacion->getImporteDesginacion($periodoInicio, $periodoFin);

        // Verificar que el importe es correcto (17 días de 31 = 54.84% aprox)
        $diasTrabajados = 17; // del 15 al 31
        $diasTotales = 31;
        $importeEsperado = 1000.00 * ($diasTrabajados / $diasTotales);

        $this->assertEquals(round($importeEsperado, 2), round($importe, 2));
    }

    public function test_get_importe_designacion_retorna_cero_sin_concepto()
    {
        // Crear datos de prueba
        $empleado = Empleado::factory()->create();
        $cargo = Cargo::factory()->create();
        $estructura = EstructuraOrganizativa::factory()->create();

        // Crear designación sin concepto de salario básico
        $designacion = Designacion::create([
            'fecha_inicio' => '2024-01-01',
            'fecha_fin' => null,
            'empleado_id' => $empleado->id,
            'estructura_organizativa_id' => $estructura->id,
            'cargo_id' => $cargo->id
        ]);

        // Fechas del período
        $periodoInicio = Carbon::create(2024, 1, 1);
        $periodoFin = Carbon::create(2024, 1, 31);

        // Calcular importe
        $importe = $designacion->getImporteDesginacion($periodoInicio, $periodoFin);

        // Verificar que retorna 0 cuando no hay concepto
        $this->assertEquals(0, $importe);
    }
}

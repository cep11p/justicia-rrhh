<?php

namespace Database\Seeders;

use App\Models\LiquidacionConcepto;
use App\Models\Concepto;
use App\Models\ValorConcepto;
use App\Models\Liquidacion;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LiquidacionConceptoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodo = '202412';
        $lista_liquidaciones = Liquidacion::with(['empleado.persona', 'empleado.designaciones.cargo'])->get();


        foreach ($lista_liquidaciones as $liquidacion) {
            $empleado = $liquidacion->empleado;
            $designaciones = $empleado->getDesignacionesParaPeriodo($periodo);

            if ($designaciones->isEmpty()) {
                continue;
            }

            // Usar la primera designación (más reciente)
            $designacion = $designaciones->first();
            $cargo = $designacion->cargo;
            $fechaIngreso = Carbon::parse($empleado->fecha_ingreso);
            $fechaPeriodo = Carbon::createFromFormat('Ym', $periodo);
            $aniosAntiguedad = $fechaIngreso->diffInYears($fechaPeriodo);

            // Obtener conceptos
            $conceptos = Concepto::all()->keyBy('codigo');

            // Calcular BÁSICO
            $valorBasico = ValorConcepto::where('concepto_id', $conceptos['001']->id)
                ->where('cargo_id', $cargo->id)
                ->where('periodo', $periodo)
                ->first();

            if ($valorBasico) {
                $basico = $valorBasico->valor;

                // Crear concepto BÁSICO
                LiquidacionConcepto::create([
                    'liquidacion_id' => $liquidacion->id,
                    'concepto_id' => $conceptos['001']->id,
                    'importe' => $basico,
                ]);


                // Calcular ADICIONAL POR FUNCIÓN (5% del básico si tiene función)
                if ($cargo->tiene_funcion) {
                    $valorFuncion = ValorConcepto::where('concepto_id', $conceptos['002']->id)
                        ->where('periodo', $periodo)
                        ->first();

                    if ($valorFuncion) {
                        $adicionalFuncion = $basico * ($valorFuncion->valor / 100);

                        LiquidacionConcepto::create([
                            'liquidacion_id' => $liquidacion->id,
                            'concepto_id' => $conceptos['002']->id,
                            'importe' => $adicionalFuncion,
                        ]);
                    }
                }

                // Calcular ADICIONAL POR TÍTULO
                if ($empleado->titulo) {
                    $valorTitulo = ValorConcepto::where('concepto_id', $conceptos['003']->id)
                        ->where('periodo', $periodo)
                        ->first();

                    if ($valorTitulo) {
                        $adicionalTitulo = $basico * ($valorTitulo->valor / 100);

                        LiquidacionConcepto::create([
                            'liquidacion_id' => $liquidacion->id,
                            'concepto_id' => $conceptos['003']->id,
                            'importe' => $adicionalTitulo,
                        ]);
                    }
                }

                // Calcular ANTIGÜEDAD (2% anual)
                $valorAntiguedad = ValorConcepto::where('concepto_id', $conceptos['004']->id)
                    ->where('periodo', $periodo)
                    ->first();

                if ($valorAntiguedad) {
                    $sumaBasico = $basico;
                    if ($cargo->tiene_funcion) {
                        $sumaBasico += $basico * 0.05; // Adicional por función
                    }
                    if ($empleado->titulo === 'universitario') {
                        $sumaBasico += $basico * 0.10; // Adicional por título
                    }

                    $adicionalAntiguedad = $sumaBasico * (($valorAntiguedad->valor / 100) * $aniosAntiguedad);

                    LiquidacionConcepto::create([
                        'liquidacion_id' => $liquidacion->id,
                        'concepto_id' => $conceptos['004']->id,
                        'importe' => $adicionalAntiguedad,
                    ]);
                }

                // Calcular ZONA (40% de la suma)
                $valorZona = ValorConcepto::where('concepto_id', $conceptos['005']->id)
                    ->where('periodo', $periodo)
                    ->first();

                if ($valorZona) {
                    $sumaParaZona = $basico;
                    if ($cargo->tiene_funcion) {
                        $sumaParaZona += $basico * 0.05;
                    }
                    if ($empleado->titulo === 'universitario') {
                        $sumaParaZona += $basico * 0.10;
                    }
                    if ($valorAntiguedad) {
                        $sumaParaZona += $sumaParaZona * ($valorAntiguedad->valor / 100) * $aniosAntiguedad;
                    }

                    $adicionalZona = $sumaParaZona * ($valorZona->valor / 100);

                    LiquidacionConcepto::create([
                        'liquidacion_id' => $liquidacion->id,
                        'concepto_id' => $conceptos['005']->id,
                        'importe' => $adicionalZona,
                    ]);
                }

                // Calcular descuentos
                $totalRemunerativo = $basico;
                if ($cargo->tiene_funcion) {
                    $totalRemunerativo += $basico * 0.05;
                }
                if ($empleado->titulo === 'universitario') {
                    $totalRemunerativo += $basico * 0.10;
                }
                if ($valorAntiguedad) {
                    $totalRemunerativo += $sumaBasico * ($valorAntiguedad->valor / 100) * $aniosAntiguedad;
                }
                if ($valorZona) {
                    $totalRemunerativo += $sumaParaZona * ($valorZona->valor / 100);
                }

                // APORTE JUBILATORIO (11%)
                $valorJubilacion = ValorConcepto::where('concepto_id', $conceptos['007']->id)
                    ->where('periodo', $periodo)
                    ->first();

                if ($valorJubilacion) {
                    $descuentoJubilacion = $totalRemunerativo * ($valorJubilacion->valor / 100);

                    LiquidacionConcepto::create([
                        'liquidacion_id' => $liquidacion->id,
                        'concepto_id' => $conceptos['007']->id,
                        'importe' => $descuentoJubilacion,
                    ]);
                }

                // OBRA SOCIAL (4%)
                $valorObraSocial = ValorConcepto::where('concepto_id', $conceptos['008']->id)
                    ->where('periodo', $periodo)
                    ->first();

                if ($valorObraSocial) {
                    $descuentoObraSocial = $totalRemunerativo * ($valorObraSocial->valor / 100);

                    LiquidacionConcepto::create([
                        'liquidacion_id' => $liquidacion->id,
                        'concepto_id' => $conceptos['008']->id,
                        'importe' => $descuentoObraSocial,
                    ]);
                }
            }
        }
    }
}

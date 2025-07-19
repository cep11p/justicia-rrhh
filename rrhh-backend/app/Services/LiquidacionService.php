<?php

namespace App\Services;

use App\Models\Concepto;
use App\Models\Empleado;
use App\Models\Liquidacion;
use App\Models\LiquidacionConcepto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiquidacionService
{

    /**
     * Calcula el salario básico para un empleado en un período específico.
     *
     * Este método obtiene todas las designaciones del empleado para el período
     * y calcula el importe básico correspondiente a cada una, considerando
     * los días trabajados y el valor del concepto de salario básico.
     *
     * @param Empleado $empleado El empleado para calcular el básico
     * @param string $periodo Período en formato YYYYMM (ej: '202401' para enero 2024)
     * @return array Array con los importes básicos calculados por designación
     *
     * @example
     * $empleado = Empleado::find(1);
     * $basicos = $conceptoService->calcularBasico($empleado, '202401');
     * // Retorna: [
     * //   [
     * //     'estructura_organizacional' => 'Administración',
     * //     'cargo' => 'Administrativo',
     * //     'periodo_fecha_inicio' => Carbon('2024-01-01'),
     * //     'periodo_fecha_fin' => Carbon('2024-01-31'),
     * //     'importe' => 1000.00
     * //   ]
     * // ]
     */
    public function calcularBasico(Empleado $empleado, string $periodo): array
    {
        // Obtener las designaciones del empleado para el período
        $designaciones = $empleado->getDesignacionesParaPeriodo($periodo);

        if ($designaciones->isEmpty()) {
            return [];
        }

        // Calcular los importes básicos para cada designación
        $resultado = $empleado->calcularImporteDeDesignaciones($designaciones, $periodo);

        return $resultado;
    }

    /**
     * Crea una liquidación.
     *
     * Este método crea una liquidación, incluyendo
     * el concepto básico y los conceptos adicionales por función, título, antigüedad y zona.
     *
     * @param array $data Los datos de la liquidación
     * @return Liquidacion La liquidación creada
     */
    public function store(array $data)
    {
        // Iniciar una transacción
        return DB::transaction(function () use ($data) {

            $liquidacion = Liquidacion::create([
                'numero' => Liquidacion::max('numero') + 1,
                'periodo' => $data['periodo'] ?? null,
                'fecha_liquidacion' => $data['fecha_liquidacion'] ?? now(),
                'observaciones' => $data['observaciones'] ?? null,
                'empleado_id' => $data['empleado_id'] ?? null,
            ]);

            $this->calcularConceptosRemunerativos($liquidacion, $data['empleado_id'], $data['periodo']);
            $this->calcularConceptosNoRemunerativos($liquidacion, $data['empleado_id'], $data['periodo']);

            return $liquidacion;
        });
    }

    public function list(Request $request){

        $query = Liquidacion::query();

        if ($request->has('periodo')) {
            $query->where('periodo', $request->periodo);
        }

        if ($request->has('persona_fullname')) {
            $query->whereHas('empleado.persona', function($query) use ($request) {
                $query->where('fullname', 'like', '%' . $request->persona_fullname . '%');
            });
        }
        if ($request->has('cuil')) {
            $query->whereHas('empleado', function($query) use ($request) {
                $query->where('cuil', $request->cuil);
            });
        }

        if ($request->has('legajo')) {
            $query->whereHas('empleado', function($query) use ($request) {
                $query->where('legajo', $request->legajo);
            });
        }

        return $query->with([
            'empleado.persona'
            ])->get();
    }

    /**
     * Calcula los conceptos remunerativos para una liquidación.
     *
     * Este método calcula los conceptos remunerativos para una liquidación, incluyendo
     * el concepto básico y los conceptos adicionales por función, título, antigüedad y zona.
     *
     * @param Liquidacion $liquidacion La liquidación a la que se agregarán los conceptos
     * @param Empleado $empleado El empleado a quien se le calcularán los conceptos
     * @param string $periodo El período de la liquidació
     * @return void
     */
    public function calcularConceptosRemunerativos(Liquidacion $liquidacion, $empleado_id, string $periodo){

        //el base tiene que estar siempre calculado para poder calcular los otros conceptos
        $this->crearLiquidacionConceptoBasico($liquidacion, $empleado_id, $periodo);

        //concepto adicional por funcion
        $this->crearRemunerativo($liquidacion, $periodo, '002');
        //concepto adicional por titulo
        $this->crearRemunerativo($liquidacion, $periodo, '003');
        //concepto adicional por antiguedad
        $this->crearRemunerativo($liquidacion, $periodo, '004');
        //concepto adicional por zona
        $this->crearRemunerativo($liquidacion, $periodo, '005');
    }

    public function calcularConceptosNoRemunerativos(Liquidacion $liquidacion, $empleado_id, string $periodo){

        //concepto adicional por funcion
        $this->crearNoRemunerativo($liquidacion, $periodo, '007');
        $this->crearNoRemunerativo($liquidacion, $periodo, '008');
    }

    public function crearNoRemunerativo(Liquidacion $liquidacion, string $periodo, string $codigo){

        $total_remunerativo = LiquidacionConcepto::where('liquidacion_id', $liquidacion->id)->conRemunerativos()->sum('importe');

        $concepto = Concepto::where('codigo', $codigo)->first();
        $valor_concepto = $concepto->valorConcepto($periodo);

        if (!$valor_concepto) {
            throw new \Exception("No existe un valor para el concepto {$concepto->codigo} en el período {$periodo}");
        }


        $liquidacion_concepto_atributos = [
            'liquidacion_id' => $liquidacion->id,
            'concepto_id' => $concepto->id,
            'importe' => $total_remunerativo * ($valor_concepto->valor / 100),
            'padre_id' => null,
        ];
        LiquidacionConcepto::create($liquidacion_concepto_atributos);

    }

    /**
     * Crea un concepto remunerativo para una liquidación.
     *
     * Este método calcula el importe de un concepto remunerativo basado en el importe
     * de los conceptos básicos de la liquidación y el valor del concepto remunerativo.
     *
     * @param Liquidacion $liquidacion La liquidación a la que se agregará el concepto
     * @param string $periodo El período de la liquidación
     * @param string $codigo El código del concepto remunerativo
     * @return void
     */
    public function crearRemunerativo(Liquidacion $liquidacion, string $periodo, string $codigo){

        $colleccion_conceptos_basicos = LiquidacionConcepto::where('liquidacion_id', $liquidacion->id)
        ->whereHas('concepto', function($query) {
            $query->where('codigo', '001');
        })
        ->get();

        $concepto = Concepto::where('codigo', $codigo)->first();
        $valor_concepto = $concepto->valorConcepto($periodo);

        //si es el concepto de antiguedad, se tiene que calcular el importe de la antiguedad
        if($concepto->codigo == '004'){
            $pre_antiguedad = $liquidacion->empleado->pre_antiguedad;
            $antiguedad = $liquidacion->empleado->antiguedad + $pre_antiguedad;
            $valor_concepto = $valor_concepto->valor * $antiguedad;
        }

        if (!$valor_concepto) {
            throw new \Exception("No existe un valor para el concepto {$concepto->codigo} en el período {$periodo}");
        }

        foreach ($colleccion_conceptos_basicos as $basico) {

            $liquidacion_concepto_atributos = [
                'liquidacion_id' => $liquidacion->id,
                'concepto_id' => $concepto->id,
                'importe' => $basico['importe'] * ($valor_concepto->valor / 100),
                'padre_id' => $basico->id,
            ];
            LiquidacionConcepto::create($liquidacion_concepto_atributos);

        }
    }

    /**
     * Crea un concepto básico para una liquidación.
     *
     * Este método crea un concepto básico para una liquidación
     *
     * @param Liquidacion $liquidacion para vincular en el concepto
     * @param string $empleado_id El ID del empleado a quien se le agregará el concepto
     * @param string $periodo El período de la liquidación
     * @return void
     */
    public function crearLiquidacionConceptoBasico(Liquidacion $liquidacion, $empleado_id, string $periodo)
    {
        $empleado = Empleado::find($empleado_id);

        $colleccion_basicos = $empleado->getImportePorDesginacion($periodo);
        foreach ($colleccion_basicos as $basico) {

            $liquidacion_concepto_atributos = [
                'liquidacion_id' => $liquidacion->id,
                'concepto_id' => Concepto::where('codigo', '001')->first()->id,
                'importe' => $basico['importe'],
            ];

            LiquidacionConcepto::create($liquidacion_concepto_atributos);
        }
    }

}

<?php

namespace App\Http\Requests;

use App\Models\Liquidacion;
use App\Models\Empleado;
use App\Models\ValorConcepto;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class LiquidacionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'empleado_id' => [
                'required',
                'integer',
                'exists:empleados,id',
                Rule::unique('liquidaciones')
                    ->where('periodo', $this->input('periodo'))
                    ->ignore($this->route('liquidacion'))
                    ->whereNull('deleted_at')
            ],
            'periodo' => 'required|string|regex:/^\d{6}$/|date_format:Ym',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validarValoresConcepto($validator);
        });
    }

        /**
     * Valida que existan los valores concepto necesarios
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected function validarValoresConcepto($validator)
    {
        $empleadoId = $this->input('empleado_id');
        $periodo = $this->input('periodo');

        if (!$empleadoId || !$periodo) {
            return;
        }

        // Obtener empleado con sus designaciones
        $empleado = Empleado::with(['designaciones.cargo'])->find($empleadoId);
        if (!$empleado) {
            return;
        }

        // Obtener todas las designaciones activas para el período
        $designaciones = $empleado->getDesignacionesParaPeriodo($periodo);
        if ($designaciones->isEmpty()) {
            $validator->errors()->add('empleado_id', "El empleado no tiene designaciones activas para el período {$periodo}");
            return;
        }

        $faltantes = [];
        $cargosConValor = [];
        $designacionesSinValor = [];

        // Verificar cada designación
        foreach ($designaciones as $designacion) {
            $cargo = $designacion->cargo;

            // Verificar si existe valor básico para este cargo
            $valorBasico = ValorConcepto::where('concepto_id', 1) // Concepto básico (001)
                ->where('cargo_id', $cargo->id)
                ->byPeriodo($periodo)
                ->first();

            if ($valorBasico) {
                $cargosConValor[] = $cargo->nombre;
            } else {
                $faltantes[] = "Valor básico para cargo '{$cargo->nombre}'";
                $designacionesSinValor[] = [
                    'cargo' => $cargo->nombre,
                    'fecha_inicio' => $designacion->fecha_inicio->format('d/m/Y'),
                    'fecha_fin' => $designacion->fecha_fin ? $designacion->fecha_fin->format('d/m/Y') : 'Vigente'
                ];
            }
        }

        // Si al menos una designación no tiene valor básico, es un error crítico
        if (!empty($faltantes)) {
            $totalDesignaciones = count($designaciones);
            $cargosConValorCount = count($cargosConValor);

            if ($totalDesignaciones === 1) {
                $mensaje = "El cargo no tiene valores concepto configurados para el período {$periodo}: " . implode(', ', $faltantes);
            } else {
                $mensaje = "Faltan valores concepto para el período {$periodo}. Cargos con valores: {$cargosConValorCount} de {$totalDesignaciones}. ";

                // Agregar detalles específicos de las designaciones sin valor
                if (!empty($designacionesSinValor)) {
                    $detallesDesignaciones = [];
                    foreach ($designacionesSinValor as $designacion) {
                        $detallesDesignaciones[] = "{$designacion['cargo']} ({$designacion['fecha_inicio']} - {$designacion['fecha_fin']})";
                    }
                    $mensaje .= "Designaciones sin valores: " . implode(', ', $detallesDesignaciones) . ". ";
                }

                $mensaje .= "Valores faltantes: " . implode(', ', $faltantes);
            }

            $validator->errors()->add('empleado_id', $mensaje);
            return;
        }

        // Validar valores generales necesarios (solo si TODAS las designaciones tienen valores)
        $codigosGenerales = ['002', '003', '004', '005', '007', '008']; // Adicional función, título, antigüedad, zona, jubilación, obra social

        foreach ($codigosGenerales as $codigo) {
            $concepto = \App\Models\Concepto::where('codigo', $codigo)->first();

            if (!$concepto) {
                $faltantes[] = "Concepto con código {$codigo} no existe";
                continue;
            }

            $valorGeneral = ValorConcepto::where('concepto_id', $concepto->id)
                ->where('cargo_id', null)
                ->byPeriodo($periodo)
                ->first();

            if (!$valorGeneral) {
                $faltantes[] = "Valor general para concepto código {$codigo}";
            }
        }

        if (!empty($faltantes)) {
            $mensaje = "Faltan valores concepto generales para el período {$periodo}: " . implode(', ', $faltantes);
            $validator->errors()->add('periodo', $mensaje);
        }
    }

    public function messages(): array
    {
        return [
            'empleado_id.required' => 'El ID del empleado es obligatorio.',
            'empleado_id.integer' => 'El ID del empleado debe ser un número entero.',
            'empleado_id.exists' => 'El empleado especificado no existe.',
            'empleado_id.unique' => 'Ya existe una liquidación para este empleado en el período especificado.',
            'periodo.required' => 'El período es obligatorio.',
            'periodo.string' => 'El período debe ser una cadena de texto.',
            'periodo.regex' => 'El período debe tener formato YYYYMM (ej: 202412).',
            'periodo.date_format' => 'El período debe ser una fecha válida en formato YYYYMM.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}

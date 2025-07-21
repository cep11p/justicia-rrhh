<?php

namespace App\Http\Requests;

use App\Models\Liquidacion;
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

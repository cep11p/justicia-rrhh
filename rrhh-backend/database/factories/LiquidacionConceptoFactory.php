<?php

namespace Database\Factories;

use App\Models\Concepto;
use App\Models\Liquidacion;
use App\Models\ValorConcepto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiquidacionConcepto>
 */
class LiquidacionConceptoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $liquidacion = Liquidacion::factory()->create();
        $concepto = Concepto::factory()->create();

        // Si el concepto tiene código "001", buscar el valor en valor_concepto
        if ($concepto->codigo === '001') {
            $valorConcepto = ValorConcepto::where('concepto_id', $concepto->id)
                ->byPeriodo($liquidacion->periodo)
                ->first();

            if (!$valorConcepto) {
                throw new \Exception("No se encontró un valor_concepto para el concepto con código '001' en el período '{$liquidacion->periodo}'");
            }

            $importe = $valorConcepto->valor;
        } else {
            throw new \Exception("No se puede generar un importe aleatorio para este concepto. Falta lógica específica para calcular el importe.");
        }

        return [
            'liquidacion_id' => $liquidacion->id,
            'concepto_id' => $concepto->id,
            'importe' => $importe,
        ];
    }

    /**
     * Concepto remunerativo
     */
    public function remunerativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->remunerativo(),
            'importe' => $this->faker->randomFloat(2, 50000, 300000),
        ]);
    }

    /**
     * Concepto de descuento
     */
    public function descuento(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->descuento(),
            'importe' => $this->faker->randomFloat(2, 5000, 100000),
        ]);
    }

    /**
     * Concepto básico (valor alto)
     */
    public function basico(): static
    {
        return $this->state(function (array $attributes) {
            $liquidacion = Liquidacion::factory()->create();
            $concepto = Concepto::factory()->basico();

            // Si el concepto tiene código "001", buscar el valor en valor_concepto
            if ($concepto->codigo === '001') {
                $valorConcepto = ValorConcepto::where('concepto_id', $concepto->id)
                    ->byPeriodo($liquidacion->periodo)
                    ->first();

                if (!$valorConcepto) {
                    throw new \Exception("No se encontró un valor_concepto para el concepto con código '001' en el período '{$liquidacion->periodo}'");
                }

                $importe = $valorConcepto->valor;
            } else {
                $importe = $this->faker->randomFloat(2, 200000, 800000);
            }

            return [
                'liquidacion_id' => $liquidacion->id,
                'concepto_id' => $concepto->id,
                'importe' => $importe,
            ];
        });
    }

    /**
     * Concepto jubilatorio (descuento alto)
     */
    public function jubilacion(): static
    {
        return $this->state(fn (array $attributes) => [
            'concepto_id' => Concepto::factory()->jubilacion(),
            'importe' => $this->faker->randomFloat(2, 20000, 100000),
        ]);
    }

    /**
     * Concepto con valor alto
     */
    public function valorAlto(): static
    {
        return $this->state(fn (array $attributes) => [
            'importe' => $this->faker->randomFloat(2, 300000, 1000000),
        ]);
    }

    /**
     * Concepto con valor bajo
     */
    public function valorBajo(): static
    {
        return $this->state(fn (array $attributes) => [
            'importe' => $this->faker->randomFloat(2, 1000, 50000),
        ]);
    }

    /**
     * Concepto específico
     */
    public function conConcepto(Concepto $concepto): static
    {
        return $this->state(function (array $attributes) use ($concepto) {
            $liquidacion = Liquidacion::factory()->create();

            // Si el concepto tiene código "001", buscar el valor en valor_concepto
            if ($concepto->codigo === '001') {
                $valorConcepto = ValorConcepto::where('concepto_id', $concepto->id)
                    ->byPeriodo($liquidacion->periodo)
                    ->first();

                if (!$valorConcepto) {
                    throw new \Exception("No se encontró un valor_concepto para el concepto con código '001' en el período '{$liquidacion->periodo}'");
                }

                $importe = $valorConcepto->valor;
            } else {
                $importe = $this->faker->randomFloat(2, 1000, 500000);
            }

            return [
                'liquidacion_id' => $liquidacion->id,
                'concepto_id' => $concepto->id,
                'importe' => $importe,
            ];
        });
    }

    /**
     * Concepto con valor específico
     */
    public function conValor(float $valor): static
    {
        return $this->state(fn (array $attributes) => [
            'importe' => $valor,
        ]);
    }

    /**
     * Concepto con código 001 usando valor_concepto
     */
    public function conCodigo001(): static
    {
        return $this->state(function (array $attributes) {
            $liquidacion = Liquidacion::factory()->create();
            $concepto = Concepto::where('codigo', '001')->first();

            if (!$concepto) {
                $concepto = Concepto::factory()->create(['codigo' => '001']);
            }

            $valorConcepto = ValorConcepto::where('concepto_id', $concepto->id)
                ->byPeriodo($liquidacion->periodo)
                ->first();

            if (!$valorConcepto) {
                throw new \Exception("No se encontró un valor_concepto para el concepto con código '001' en el período '{$liquidacion->periodo}'");
            }

            $importe = $valorConcepto->valor;

            return [
                'liquidacion_id' => $liquidacion->id,
                'concepto_id' => $concepto->id,
                'importe' => $importe,
            ];
        });
    }
}

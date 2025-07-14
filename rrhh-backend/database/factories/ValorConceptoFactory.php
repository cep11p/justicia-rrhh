<?php

namespace Database\Factories;

use App\Models\Cargo;
use App\Models\Concepto;
use App\Models\ValorConcepto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ValorConcepto>
 */
class ValorConceptoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ValorConcepto::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'concepto_id' => Concepto::factory(),
            'cargo_id' => $this->faker->optional(0.3)->randomElement([Cargo::factory(), null]), // 30% de probabilidad de tener cargo específico
            'periodo' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Ym'),
            'valor' => $this->faker->randomFloat(2, 1000, 500000),
        ];
    }

    /**
     * Indicate that the value is for a specific position.
     */
    public function paraCargo(): static
    {
        return $this->state(fn (array $attributes) => [
            'cargo_id' => Cargo::factory(),
        ]);
    }

    /**
     * Indicate that the value is general (no specific position).
     */
    public function general(): static
    {
        return $this->state(fn (array $attributes) => [
            'cargo_id' => null,
        ]);
    }

    /**
     * Indicate that the value is for the current period.
     */
    public function periodoActual(): static
    {
        return $this->state(fn (array $attributes) => [
            'periodo' => now()->format('Ym'),
        ]);
    }

    /**
     * Indicate that the value is for a specific period.
     */
    public function paraPeriodo(string $periodo): static
    {
        return $this->state(fn (array $attributes) => [
            'periodo' => $periodo,
        ]);
    }

    /**
     * Indicate that the value is high (for senior positions).
     */
    public function valorAlto(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 200000, 500000),
        ]);
    }

    /**
     * Indicate that the value is low (for junior positions).
     */
    public function valorBajo(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 1000, 100000),
        ]);
    }

    /**
     * Indicate that the value is a percentage (for percentage concepts).
     */
    public function porcentual(): static
    {
        return $this->state(fn (array $attributes) => [
            'valor' => $this->faker->randomFloat(2, 1, 50),
        ]);
    }
}

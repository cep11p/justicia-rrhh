<?php

namespace Database\Factories;

use App\Models\Liquidacion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Liquidacion>
 */
class LiquidacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Liquidacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, 2024);
        $month = $this->faker->numberBetween(1, 12);
        $periodo = sprintf('%04d%02d', $year, $month);
        $numero = sprintf('LIQ-%04d-%03d', $year, $this->faker->unique()->numberBetween(1, 999));

        return [
            'numero' => $numero,
            'periodo' => $periodo,
        ];
    }

    /**
     * Indicate that the liquidation is for the current year.
     */
    public function añoActual(): static
    {
        $year = now()->year;
        $month = $this->faker->numberBetween(1, 12);
        $periodo = sprintf('%04d%02d', $year, $month);
        $numero = sprintf('LIQ-%04d-%03d', $year, $this->faker->unique()->numberBetween(1, 999));

        return $this->state(fn (array $attributes) => [
            'numero' => $numero,
            'periodo' => $periodo,
        ]);
    }

    /**
     * Indicate that the liquidation is for a specific year.
     */
    public function paraAño(int $year): static
    {
        $month = $this->faker->numberBetween(1, 12);
        $periodo = sprintf('%04d%02d', $year, $month);
        $numero = sprintf('LIQ-%04d-%03d', $year, $this->faker->unique()->numberBetween(1, 999));

        return $this->state(fn (array $attributes) => [
            'numero' => $numero,
            'periodo' => $periodo,
        ]);
    }

    /**
     * Indicate that the liquidation is for a specific period.
     */
    public function paraPeriodo(string $periodo): static
    {
        $year = substr($periodo, 0, 4);
        $numero = sprintf('LIQ-%04d-%03d', $year, $this->faker->unique()->numberBetween(1, 999));

        return $this->state(fn (array $attributes) => [
            'numero' => $numero,
            'periodo' => $periodo,
        ]);
    }

    /**
     * Indicate that the liquidation is recent (last 6 months).
     */
    public function reciente(): static
    {
        $date = $this->faker->dateTimeBetween('-6 months', 'now');
        $year = $date->format('Y');
        $month = $date->format('m');
        $periodo = $date->format('Ym');
        $numero = sprintf('LIQ-%04d-%03d', $year, $this->faker->unique()->numberBetween(1, 999));

        return $this->state(fn (array $attributes) => [
            'numero' => $numero,
            'periodo' => $periodo,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Models\PartInventoryMonitor;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartInventoryMonitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartInventoryMonitor::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'active' => $this->faker->boolean(),
            'threshold' => $this->faker->numberBetween(-10000, 10000),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Monitor;

class MonitorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Monitor::class;

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

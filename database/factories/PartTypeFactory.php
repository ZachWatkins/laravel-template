<?php

namespace Database\Factories;

use App\Models\PartType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}

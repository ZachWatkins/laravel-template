<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProvinceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Province::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'name' => $this->faker->name(),
            'abbreviation' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'country_id' => Country::factory(),
        ];
    }
}

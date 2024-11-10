<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Manufacturer;
use App\Models\Part;
use App\Models\PartType;

class PartFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Part::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(["active","inactive"]),
            'number' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'name' => $this->faker->name(),
            'sku' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'inventory' => $this->faker->numberBetween(-10000, 10000),
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
            'weight' => $this->faker->randomFloat(2, 0, 999999.99),
            'weight_unit' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'filename' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'published_at' => $this->faker->dateTime(),
            'part_type_id' => PartType::factory(),
            'manufacturer_id' => Manufacturer::factory(),
        ];
    }
}

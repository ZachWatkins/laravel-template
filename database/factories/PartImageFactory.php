<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Part;
use App\Models\PartImage;

class PartImageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartImage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(["thumbnail","main","additional"]),
            'path' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'part_id' => Part::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Part;
use App\Models\PartWebpage;

class PartWebpageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PartWebpage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(["active","inactive"]),
            'path' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'title' => $this->faker->sentence(4),
            'meta_title' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'meta_description' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'meta_keywords' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'content' => $this->faker->paragraphs(3, true),
            'part_id' => Part::factory(),
        ];
    }
}

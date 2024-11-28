<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\OrderDestination;
use App\Models\OrderPayment;

class OrderDestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDestination::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'address_line_1' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'address_line_2' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'city' => $this->faker->city(),
            'state_province_region' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'postal_code' => $this->faker->postcode(),
            'country_code' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'space_station' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'planet_or_moon' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'star_system' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'sector' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'quadrant' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'order_payment_id' => OrderPayment::factory(),
        ];
    }
}

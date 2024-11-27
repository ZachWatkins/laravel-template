<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ShippingAddress;

class ShippingAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ShippingAddress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_number' => $this->faker->phoneNumber(),
            'street' => $this->faker->streetName(),
            'company' => $this->faker->company(),
            'city' => $this->faker->city(),
            'postal_code' => $this->faker->postcode(),
            'country_code' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'province_code' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'province_name' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'state_province_region' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'space_station' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'planet_or_moon' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'star_system' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'sector' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'quadrant' => $this->faker->regexify('[A-Za-z0-9]{100}'),
        ];
    }
}

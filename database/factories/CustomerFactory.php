<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\User;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'phone' => $this->faker->phoneNumber(),
            'shipping_street_1' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'shipping_street_2' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'shipping_city' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'shipping_state' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'shipping_zip_code' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'shipping_instructions' => $this->faker->regexify('[A-Za-z0-9]{500}'),
            'billing_street_1' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'billing_street_2' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'billing_city' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'billing_state' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'billing_zip_code' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'billing_card_name' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'billing_card_number' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'billing_card_expiration' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'billing_card_cvv' => $this->faker->regexify('[A-Za-z0-9]{3}'),
            'user_id' => User::factory(),
        ];
    }
}

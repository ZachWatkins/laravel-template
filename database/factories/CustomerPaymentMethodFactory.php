<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerPaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerPaymentMethodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerPaymentMethod::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'card_name' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'card_number' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'card_expiration' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'street_1' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'street_2' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'city' => $this->faker->city(),
            'state' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'zip_code' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'customer_id' => Customer::factory(),
        ];
    }
}

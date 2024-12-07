<?php

namespace Database\Factories;

use App\Models\BillingAddress;
use App\Models\Customer;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'birthday' => $this->faker->date(),
            'phone_number' => $this->faker->phoneNumber(),
            'subscribed_to_newsletter' => $this->faker->boolean(),
            'user_id' => User::factory(),
            'shipping_address_id' => ShippingAddress::factory(),
            'billing_address_id' => BillingAddress::factory(),
        ];
    }
}

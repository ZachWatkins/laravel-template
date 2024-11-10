<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\CustomerBillingAddress;
use App\Models\CustomerPaymentMethod;
use App\Models\CustomerShippingAddress;
use App\Models\Order;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->numberBetween(-10000, 10000),
            'status' => $this->faker->randomElement(["paid","processing","shipped","delivered","canceled","returned","partially_refunded","refunded","completed"]),
            'total' => $this->faker->randomFloat(2, 0, 99999999.99),
            'notes' => $this->faker->text(),
            'customer_id' => Customer::factory(),
            'customer_payment_method_id' => CustomerPaymentMethod::factory(),
            'customer_shipping_address_id' => CustomerShippingAddress::factory(),
            'customer_billing_address_id' => CustomerBillingAddress::factory(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderPayment::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'total' => $this->faker->randomFloat(2, 0, 99999999.99),
            'order_id' => Order::factory(),
        ];
    }
}

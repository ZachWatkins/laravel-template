<?php

namespace Database\Factories;

use App\Models\BillingAddress;
use App\Models\OrderPayment;
use App\Models\OrderPaymentCard;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderPaymentCardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderPaymentCard::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'number' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'expiration' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'order_payment_id' => OrderPayment::factory(),
            '_id' => BillingAddress::factory(),
        ];
    }
}

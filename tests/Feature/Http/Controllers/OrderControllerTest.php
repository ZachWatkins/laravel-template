<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Currency;
use App\Models\Customer;
use App\Models\Locale;
use App\Models\Order;
use function Pest\Faker\fake;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    $orders = Order::factory()->count(3)->create();

    $response = get(route('orders.index'));

    $response->assertOk();
    $response->assertViewIs('order.index');
    $response->assertViewHas('orders');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\OrderController::class,
        'store',
        \App\Http\Requests\OrderStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $number = fake()->numberBetween(-10000, 10000);
    $payment_state = fake()->randomElement(['pending','paid','partially_refunded','refunded']);
    $shipping_state = fake()->randomElement(['pending','shipped','delivered','returned']);
    $items_total = fake()->numberBetween(-10000, 10000);
    $total = fake()->randomFloat(2, 0, 100000);
    $created_by_guest = fake()->boolean();
    $notes = fake()->text();
    $customer = Customer::factory()->create();
    $currency = Currency::factory()->create();
    $locale = Locale::factory()->create();

    $response = post(route('orders.store'), [
        'number' => $number,
        'payment_state' => $payment_state,
        'shipping_state' => $shipping_state,
        'items_total' => $items_total,
        'total' => $total,
        'created_by_guest' => $created_by_guest,
        'notes' => $notes,
        'customer_id' => $customer->id,
        'currency_id' => $currency->id,
        'locale_id' => $locale->id,
    ]);
    $response->assertSessionHasNoErrors();

    $orders = Order::query()
        ->where('number', $number)
        ->where('payment_state', $payment_state)
        ->where('shipping_state', $shipping_state)
        ->where('items_total', $items_total)
        ->where('total', $total)
        ->where('created_by_guest', $created_by_guest)
        ->where('notes', $notes)
        ->where('customer_id', $customer->id)
        ->where('currency_id', $currency->id)
        ->where('locale_id', $locale->id)
        ->get();
    expect($orders)->toHaveCount(1);
    $order = $orders->first();

    $response->assertRedirect(route('orders.index'));
    $response->assertSessionHas('order.id', $order->id);
});


test('show displays view', function (): void {
    $order = Order::factory()->create();

    $response = get(route('orders.show', $order));

    $response->assertOk();
    $response->assertViewIs('order.show');
    $response->assertViewHas('order');
});

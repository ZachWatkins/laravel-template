<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use function Pest\Laravel\get;

test('index displays view', function (): void {
    $orders = Order::factory()->count(3)->create();

    $response = get(route('orders.index'));

    $response->assertOk();
    $response->assertViewIs('order.index');
    $response->assertViewHas('orders');
});


test('show displays view', function (): void {
    $order = Order::factory()->create();

    $response = get(route('orders.show', $order));

    $response->assertOk();
    $response->assertViewIs('order.show');
    $response->assertViewHas('order');
});

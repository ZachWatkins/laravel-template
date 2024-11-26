<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ShoppingCart;
use function Pest\Laravel\get;

test('order redirects', function (): void {
    $shoppingCart = ShoppingCart::factory()->create();

    $response = get(route('shopping-carts.order'));

    $response->assertRedirect();
});

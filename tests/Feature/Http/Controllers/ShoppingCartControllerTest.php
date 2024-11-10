<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ShoppingCart;
use function Pest\Laravel\get;

test('order behaves as expected', function (): void {
    $shoppingCart = ShoppingCart::factory()->create();

    $response = get(route('shopping-carts.order'));
});

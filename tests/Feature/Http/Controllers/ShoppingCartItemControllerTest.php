<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Part;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;

use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    $shoppingCartItems = ShoppingCartItem::factory()->count(3)->create();

    $response = get(route('shopping-cart-items.index'));

    $response->assertOk();
    $response->assertViewIs('shoppingCartItem.index');
    $response->assertViewHas('shoppingCartItems');
});

test('create displays view', function (): void {
    $response = get(route('shopping-cart-items.create'));

    $response->assertOk();
    $response->assertViewIs('shoppingCartItem.create');
});

test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\ShoppingCartItemController::class,
        'store',
        \App\Http\Requests\ShoppingCartItemStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $quantity = fake()->numberBetween(-10000, 10000);
    $unit_price = fake()->numberBetween(-10000, 10000);
    $shopping_cart = ShoppingCart::factory()->create();
    $part = Part::factory()->create();

    $response = post(route('shopping-cart-items.store'), [
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'shopping_cart_id' => $shopping_cart->id,
        'part_id' => $part->id,
    ]);

    $shoppingCartItems = ShoppingCartItem::query()
        ->where('quantity', $quantity)
        ->where('unit_price', $unit_price)
        ->where('shopping_cart_id', $shopping_cart->id)
        ->where('part_id', $part->id)
        ->get();
    expect($shoppingCartItems)->toHaveCount(1);
    $shoppingCartItem = $shoppingCartItems->first();

    $response->assertRedirect(route('shopping-cart-items.index'));
    $response->assertSessionHas('shoppingCartItem.id', $shoppingCartItem->id);
});

test('show displays view', function (): void {
    $shoppingCartItem = ShoppingCartItem::factory()->create();

    $response = get(route('shopping-cart-items.show', $shoppingCartItem));

    $response->assertOk();
    $response->assertViewIs('shoppingCartItem.show');
    $response->assertViewHas('shoppingCartItem');
});

test('edit displays view', function (): void {
    $shoppingCartItem = ShoppingCartItem::factory()->create();

    $response = get(route('shopping-cart-items.edit', $shoppingCartItem));

    $response->assertOk();
    $response->assertViewIs('shoppingCartItem.edit');
    $response->assertViewHas('shoppingCartItem');
});

test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\ShoppingCartItemController::class,
        'update',
        \App\Http\Requests\ShoppingCartItemUpdateRequest::class
    );

test('update redirects', function (): void {
    $shoppingCartItem = ShoppingCartItem::factory()->create();
    $quantity = fake()->numberBetween(-10000, 10000);
    $unit_price = fake()->numberBetween(-10000, 10000);
    $shopping_cart = ShoppingCart::factory()->create();
    $part = Part::factory()->create();

    $response = put(route('shopping-cart-items.update', $shoppingCartItem), [
        'quantity' => $quantity,
        'unit_price' => $unit_price,
        'shopping_cart_id' => $shopping_cart->id,
        'part_id' => $part->id,
    ]);

    $shoppingCartItem->refresh();

    $response->assertRedirect(route('shopping-cart-items.index'));
    $response->assertSessionHas('shoppingCartItem.id', $shoppingCartItem->id);

    expect($quantity)->toEqual($shoppingCartItem->quantity);
    expect($unit_price)->toEqual($shoppingCartItem->unit_price);
    expect($shopping_cart->id)->toEqual($shoppingCartItem->shopping_cart_id);
    expect($part->id)->toEqual($shoppingCartItem->part_id);
});

test('destroy deletes and redirects', function (): void {
    $shoppingCartItem = ShoppingCartItem::factory()->create();

    $response = delete(route('shopping-cart-items.destroy', $shoppingCartItem));

    $response->assertRedirect(route('shopping-cart-items.index'));

    assertModelMissing($shoppingCartItem);
});

<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use App\Models\ShoppingCart;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    $shoppingCarts = ShoppingCart::factory()->count(3)->create();

    $response = get(route('shopping-carts.index'));

    $response->assertOk();
    $response->assertViewIs('shoppingCart.index');
    $response->assertViewHas('shoppingCarts');
});


test('create displays view', function (): void {
    $response = get(route('shopping-carts.create'));

    $response->assertOk();
    $response->assertViewIs('shoppingCart.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\ShoppingCartController::class,
        'store',
        \App\Http\Requests\ShoppingCartStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $customer = Customer::factory()->create();

    $response = post(route('shopping-carts.store'), [
        'customer_id' => $customer->id,
    ]);

    $shoppingCarts = ShoppingCart::query()
        ->where('customer_id', $customer->id)
        ->get();
    expect($shoppingCarts)->toHaveCount(1);
    $shoppingCart = $shoppingCarts->first();

    $response->assertRedirect(route('shopping-carts.index'));
    $response->assertSessionHas('shoppingCart.id', $shoppingCart->id);
});


test('show displays view', function (): void {
    $shoppingCart = ShoppingCart::factory()->create();

    $response = get(route('shopping-carts.show', $shoppingCart));

    $response->assertOk();
    $response->assertViewIs('shoppingCart.show');
    $response->assertViewHas('shoppingCart');
});


test('edit displays view', function (): void {
    $shoppingCart = ShoppingCart::factory()->create();

    $response = get(route('shopping-carts.edit', $shoppingCart));

    $response->assertOk();
    $response->assertViewIs('shoppingCart.edit');
    $response->assertViewHas('shoppingCart');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\ShoppingCartController::class,
        'update',
        \App\Http\Requests\ShoppingCartUpdateRequest::class
    );

test('update redirects', function (): void {
    $shoppingCart = ShoppingCart::factory()->create();
    $customer = Customer::factory()->create();

    $response = put(route('shopping-carts.update', $shoppingCart), [
        'customer_id' => $customer->id,
    ]);

    $shoppingCart->refresh();

    $response->assertRedirect(route('shopping-carts.index'));
    $response->assertSessionHas('shoppingCart.id', $shoppingCart->id);

    expect($customer->id)->toEqual($shoppingCart->customer_id);
});


test('destroy deletes and redirects', function (): void {
    $shoppingCart = ShoppingCart::factory()->create();

    $response = delete(route('shopping-carts.destroy', $shoppingCart));

    $response->assertRedirect(route('shopping-carts.index'));

    assertModelMissing($shoppingCart);
});

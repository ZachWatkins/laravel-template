<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BillingAddress;
use App\Models\Customer;
use App\Models\ShippingAddress;
use App\Models\User;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    $customers = Customer::factory()->count(3)->create();

    $response = get(route('customers.index'));

    $response->assertOk();
    $response->assertViewIs('customer.index');
    $response->assertViewHas('customers');
});


test('create displays view', function (): void {
    $response = get(route('customers.create'));

    $response->assertOk();
    $response->assertViewIs('customer.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\CustomerController::class,
        'store',
        \App\Http\Requests\CustomerStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $subscribed_to_newsletter = fake()->boolean();
    $user = User::factory()->create();
    $shipping_address = ShippingAddress::factory()->create();
    $billing_address = BillingAddress::factory()->create();

    $response = post(route('customers.store'), [
        'subscribed_to_newsletter' => $subscribed_to_newsletter,
        'user_id' => $user->id,
        'shipping_address_id' => $shipping_address->id,
        'billing_address_id' => $billing_address->id,
    ]);

    $customers = Customer::query()
        ->where('subscribed_to_newsletter', $subscribed_to_newsletter)
        ->where('user_id', $user->id)
        ->where('shipping_address_id', $shipping_address->id)
        ->where('billing_address_id', $billing_address->id)
        ->get();
    expect($customers)->toHaveCount(1);
    $customer = $customers->first();

    $response->assertRedirect(route('customers.index'));
    $response->assertSessionHas('customer.id', $customer->id);
});


test('show displays view', function (): void {
    $customer = Customer::factory()->create();

    $response = get(route('customers.show', $customer));

    $response->assertOk();
    $response->assertViewIs('customer.show');
    $response->assertViewHas('customer');
});


test('edit displays view', function (): void {
    $customer = Customer::factory()->create();

    $response = get(route('customers.edit', $customer));

    $response->assertOk();
    $response->assertViewIs('customer.edit');
    $response->assertViewHas('customer');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\CustomerController::class,
        'update',
        \App\Http\Requests\CustomerUpdateRequest::class
    );

test('update redirects', function (): void {
    $customer = Customer::factory()->create();
    $subscribed_to_newsletter = fake()->boolean();
    $user = User::factory()->create();
    $shipping_address = ShippingAddress::factory()->create();
    $billing_address = BillingAddress::factory()->create();

    $response = put(route('customers.update', $customer), [
        'subscribed_to_newsletter' => $subscribed_to_newsletter,
        'user_id' => $user->id,
        'shipping_address_id' => $shipping_address->id,
        'billing_address_id' => $billing_address->id,
    ]);

    $customer->refresh();

    $response->assertRedirect(route('customers.index'));
    $response->assertSessionHas('customer.id', $customer->id);

    expect($subscribed_to_newsletter)->toEqual($customer->subscribed_to_newsletter);
    expect($user->id)->toEqual($customer->user_id);
    expect($shipping_address->id)->toEqual($customer->shipping_address_id);
    expect($billing_address->id)->toEqual($customer->billing_address_id);
});


test('destroy deletes and redirects', function (): void {
    $customer = Customer::factory()->create();

    $response = delete(route('customers.destroy', $customer));

    $response->assertRedirect(route('customers.index'));

    assertModelMissing($customer);
});

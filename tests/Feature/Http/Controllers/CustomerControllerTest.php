<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

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
    $phone = fake()->phoneNumber();
    $shipping_street_1 = fake()->word();
    $shipping_street_2 = fake()->word();
    $shipping_city = fake()->word();
    $shipping_state = fake()->word();
    $shipping_zip_code = fake()->word();
    $shipping_instructions = fake()->word();
    $billing_street_1 = fake()->word();
    $billing_street_2 = fake()->word();
    $billing_city = fake()->word();
    $billing_state = fake()->word();
    $billing_zip_code = fake()->word();
    $billing_card_name = fake()->word();
    $billing_card_number = fake()->word();
    $billing_card_expiration = fake()->word();
    $billing_card_cvv = fake()->word();
    $user = User::factory()->create();

    $response = post(route('customers.store'), [
        'phone' => $phone,
        'shipping_street_1' => $shipping_street_1,
        'shipping_street_2' => $shipping_street_2,
        'shipping_city' => $shipping_city,
        'shipping_state' => $shipping_state,
        'shipping_zip_code' => $shipping_zip_code,
        'shipping_instructions' => $shipping_instructions,
        'billing_street_1' => $billing_street_1,
        'billing_street_2' => $billing_street_2,
        'billing_city' => $billing_city,
        'billing_state' => $billing_state,
        'billing_zip_code' => $billing_zip_code,
        'billing_card_name' => $billing_card_name,
        'billing_card_number' => $billing_card_number,
        'billing_card_expiration' => $billing_card_expiration,
        'billing_card_cvv' => $billing_card_cvv,
        'user_id' => $user->id,
    ]);

    $customers = Customer::query()
        ->where('phone', $phone)
        ->where('shipping_street_1', $shipping_street_1)
        ->where('shipping_street_2', $shipping_street_2)
        ->where('shipping_city', $shipping_city)
        ->where('shipping_state', $shipping_state)
        ->where('shipping_zip_code', $shipping_zip_code)
        ->where('shipping_instructions', $shipping_instructions)
        ->where('billing_street_1', $billing_street_1)
        ->where('billing_street_2', $billing_street_2)
        ->where('billing_city', $billing_city)
        ->where('billing_state', $billing_state)
        ->where('billing_zip_code', $billing_zip_code)
        ->where('billing_card_name', $billing_card_name)
        ->where('billing_card_number', $billing_card_number)
        ->where('billing_card_expiration', $billing_card_expiration)
        ->where('billing_card_cvv', $billing_card_cvv)
        ->where('user_id', $user->id)
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
    $phone = fake()->phoneNumber();
    $shipping_street_1 = fake()->word();
    $shipping_street_2 = fake()->word();
    $shipping_city = fake()->word();
    $shipping_state = fake()->word();
    $shipping_zip_code = fake()->word();
    $shipping_instructions = fake()->word();
    $billing_street_1 = fake()->word();
    $billing_street_2 = fake()->word();
    $billing_city = fake()->word();
    $billing_state = fake()->word();
    $billing_zip_code = fake()->word();
    $billing_card_name = fake()->word();
    $billing_card_number = fake()->word();
    $billing_card_expiration = fake()->word();
    $billing_card_cvv = fake()->word();
    $user = User::factory()->create();

    $response = put(route('customers.update', $customer), [
        'phone' => $phone,
        'shipping_street_1' => $shipping_street_1,
        'shipping_street_2' => $shipping_street_2,
        'shipping_city' => $shipping_city,
        'shipping_state' => $shipping_state,
        'shipping_zip_code' => $shipping_zip_code,
        'shipping_instructions' => $shipping_instructions,
        'billing_street_1' => $billing_street_1,
        'billing_street_2' => $billing_street_2,
        'billing_city' => $billing_city,
        'billing_state' => $billing_state,
        'billing_zip_code' => $billing_zip_code,
        'billing_card_name' => $billing_card_name,
        'billing_card_number' => $billing_card_number,
        'billing_card_expiration' => $billing_card_expiration,
        'billing_card_cvv' => $billing_card_cvv,
        'user_id' => $user->id,
    ]);

    $customer->refresh();

    $response->assertRedirect(route('customers.index'));
    $response->assertSessionHas('customer.id', $customer->id);

    expect($phone)->toEqual($customer->phone);
    expect($shipping_street_1)->toEqual($customer->shipping_street_1);
    expect($shipping_street_2)->toEqual($customer->shipping_street_2);
    expect($shipping_city)->toEqual($customer->shipping_city);
    expect($shipping_state)->toEqual($customer->shipping_state);
    expect($shipping_zip_code)->toEqual($customer->shipping_zip_code);
    expect($shipping_instructions)->toEqual($customer->shipping_instructions);
    expect($billing_street_1)->toEqual($customer->billing_street_1);
    expect($billing_street_2)->toEqual($customer->billing_street_2);
    expect($billing_city)->toEqual($customer->billing_city);
    expect($billing_state)->toEqual($customer->billing_state);
    expect($billing_zip_code)->toEqual($customer->billing_zip_code);
    expect($billing_card_name)->toEqual($customer->billing_card_name);
    expect($billing_card_number)->toEqual($customer->billing_card_number);
    expect($billing_card_expiration)->toEqual($customer->billing_card_expiration);
    expect($billing_card_cvv)->toEqual($customer->billing_card_cvv);
    expect($user->id)->toEqual($customer->user_id);
});


test('destroy deletes and redirects', function (): void {
    $customer = Customer::factory()->create();

    $response = delete(route('customers.destroy', $customer));

    $response->assertRedirect(route('customers.index'));

    assertModelMissing($customer);
});

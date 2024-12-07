<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerPaymentMethod;

use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    $customerPaymentMethods = CustomerPaymentMethod::factory()->count(3)->create();

    $response = get(route('customer-payment-methods.index'));

    $response->assertOk();
    $response->assertViewIs('customerPaymentMethod.index');
    $response->assertViewHas('customerPaymentMethods');
});

test('create displays view', function (): void {
    $response = get(route('customer-payment-methods.create'));

    $response->assertOk();
    $response->assertViewIs('customerPaymentMethod.create');
});

test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\CustomerPaymentMethodController::class,
        'store',
        \App\Http\Requests\CustomerPaymentMethodStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $card_name = fake()->word();
    $card_number = fake()->word();
    $card_expiration = date('m/y', strtotime('+1 year'));
    $street_1 = fake()->word();
    $street_2 = fake()->word();
    $city = fake()->city();
    $state = fake()->word();
    $zip_code = '55555';
    $customer = Customer::factory()->create();

    $response = post(route('customer-payment-methods.store'), [
        'card_name' => $card_name,
        'card_number' => $card_number,
        'card_expiration' => $card_expiration,
        'street_1' => $street_1,
        'street_2' => $street_2,
        'city' => $city,
        'state' => $state,
        'zip_code' => $zip_code,
        'customer_id' => $customer->id,
    ]);
    $response->assertSessionHasNoErrors();

    $customerPaymentMethods = CustomerPaymentMethod::query()
        ->where('card_name', $card_name)
        ->where('card_number', $card_number)
        ->where('card_expiration', $card_expiration)
        ->where('street_1', $street_1)
        ->where('street_2', $street_2)
        ->where('city', $city)
        ->where('state', $state)
        ->where('zip_code', $zip_code)
        ->where('customer_id', $customer->id)
        ->get();
    expect($customerPaymentMethods)->toHaveCount(1);
    $customerPaymentMethod = $customerPaymentMethods->first();

    $response->assertRedirect(route('customer-payment-methods.index'));
    $response->assertSessionHas('customerPaymentMethod.id', $customerPaymentMethod->id);
});

test('show displays view', function (): void {
    $customerPaymentMethod = CustomerPaymentMethod::factory()->create();

    $response = get(route('customer-payment-methods.show', $customerPaymentMethod));

    $response->assertOk();
    $response->assertViewIs('customerPaymentMethod.show');
    $response->assertViewHas('customerPaymentMethod');
});

test('edit displays view', function (): void {
    $customerPaymentMethod = CustomerPaymentMethod::factory()->create();

    $response = get(route('customer-payment-methods.edit', $customerPaymentMethod));

    $response->assertOk();
    $response->assertViewIs('customerPaymentMethod.edit');
    $response->assertViewHas('customerPaymentMethod');
});

test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\CustomerPaymentMethodController::class,
        'update',
        \App\Http\Requests\CustomerPaymentMethodUpdateRequest::class
    );

test('update redirects', function (): void {
    $customerPaymentMethod = CustomerPaymentMethod::factory()->create();
    $card_name = fake()->word();
    $card_number = fake()->word();
    $card_expiration = date('m/y', strtotime('+1 year'));
    $street_1 = fake()->word();
    $street_2 = fake()->word();
    $city = fake()->city();
    $state = fake()->word();
    $zip_code = '55555';
    $customer = Customer::factory()->create();

    $response = put(route('customer-payment-methods.update', $customerPaymentMethod), [
        'card_name' => $card_name,
        'card_number' => $card_number,
        'card_expiration' => $card_expiration,
        'street_1' => $street_1,
        'street_2' => $street_2,
        'city' => $city,
        'state' => $state,
        'zip_code' => $zip_code,
        'customer_id' => $customer->id,
    ]);

    $customerPaymentMethod->refresh();

    $response->assertRedirect(route('customer-payment-methods.index'));
    $response->assertSessionHas('customerPaymentMethod.id', $customerPaymentMethod->id);

    expect($card_name)->toEqual($customerPaymentMethod->card_name);
    expect($card_number)->toEqual($customerPaymentMethod->card_number);
    expect($card_expiration)->toEqual($customerPaymentMethod->card_expiration);
    expect($street_1)->toEqual($customerPaymentMethod->street_1);
    expect($street_2)->toEqual($customerPaymentMethod->street_2);
    expect($city)->toEqual($customerPaymentMethod->city);
    expect($state)->toEqual($customerPaymentMethod->state);
    expect($zip_code)->toEqual($customerPaymentMethod->zip_code);
    expect($customer->id)->toEqual($customerPaymentMethod->customer_id);
});

test('destroy deletes and redirects', function (): void {
    $customerPaymentMethod = CustomerPaymentMethod::factory()->create();

    $response = delete(route('customer-payment-methods.destroy', $customerPaymentMethod));

    $response->assertRedirect(route('customer-payment-methods.index'));

    assertModelMissing($customerPaymentMethod);
});

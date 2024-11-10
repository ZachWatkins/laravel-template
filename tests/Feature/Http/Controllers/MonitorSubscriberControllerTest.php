<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Monitor;
use App\Models\MonitorSubscriber;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index displays view', function (): void {
    $monitorSubscribers = MonitorSubscriber::factory()->count(3)->create();

    $response = get(route('monitor-subscribers.index'));

    $response->assertOk();
    $response->assertViewIs('monitorSubscriber.index');
    $response->assertViewHas('monitorSubscribers');
});


test('create displays view', function (): void {
    $response = get(route('monitor-subscribers.create'));

    $response->assertOk();
    $response->assertViewIs('monitorSubscriber.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\MonitorSubscriberController::class,
        'store',
        \App\Http\Requests\MonitorSubscriberStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $name = fake()->name();
    $email = fake()->safeEmail();
    $monitor = Monitor::factory()->create();

    $response = post(route('monitor-subscribers.store'), [
        'name' => $name,
        'email' => $email,
        'monitor_id' => $monitor->id,
    ]);

    $monitorSubscribers = MonitorSubscriber::query()
        ->where('name', $name)
        ->where('email', $email)
        ->where('monitor_id', $monitor->id)
        ->get();
    expect($monitorSubscribers)->toHaveCount(1);
    $monitorSubscriber = $monitorSubscribers->first();

    $response->assertRedirect(route('monitorSubscribers.index'));
    $response->assertSessionHas('monitorSubscriber.id', $monitorSubscriber->id);
});


test('show displays view', function (): void {
    $monitorSubscriber = MonitorSubscriber::factory()->create();

    $response = get(route('monitor-subscribers.show', $monitorSubscriber));

    $response->assertOk();
    $response->assertViewIs('monitorSubscriber.show');
    $response->assertViewHas('monitorSubscriber');
});


test('edit displays view', function (): void {
    $monitorSubscriber = MonitorSubscriber::factory()->create();

    $response = get(route('monitor-subscribers.edit', $monitorSubscriber));

    $response->assertOk();
    $response->assertViewIs('monitorSubscriber.edit');
    $response->assertViewHas('monitorSubscriber');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\MonitorSubscriberController::class,
        'update',
        \App\Http\Requests\MonitorSubscriberUpdateRequest::class
    );

test('update redirects', function (): void {
    $monitorSubscriber = MonitorSubscriber::factory()->create();
    $name = fake()->name();
    $email = fake()->safeEmail();
    $monitor = Monitor::factory()->create();

    $response = put(route('monitor-subscribers.update', $monitorSubscriber), [
        'name' => $name,
        'email' => $email,
        'monitor_id' => $monitor->id,
    ]);

    $monitorSubscriber->refresh();

    $response->assertRedirect(route('monitorSubscribers.index'));
    $response->assertSessionHas('monitorSubscriber.id', $monitorSubscriber->id);

    expect($name)->toEqual($monitorSubscriber->name);
    expect($email)->toEqual($monitorSubscriber->email);
    expect($monitor->id)->toEqual($monitorSubscriber->monitor_id);
});


test('destroy deletes and redirects', function (): void {
    $monitorSubscriber = MonitorSubscriber::factory()->create();

    $response = delete(route('monitor-subscribers.destroy', $monitorSubscriber));

    $response->assertRedirect(route('monitorSubscribers.index'));

    assertModelMissing($monitorSubscriber);
});

<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Monitor;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index displays view', function (): void {
    $monitors = Monitor::factory()->count(3)->create();

    $response = get(route('monitors.index'));

    $response->assertOk();
    $response->assertViewIs('monitor.index');
    $response->assertViewHas('monitors');
});


test('create displays view', function (): void {
    $response = get(route('monitors.create'));

    $response->assertOk();
    $response->assertViewIs('monitor.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\MonitorController::class,
        'store',
        \App\Http\Requests\MonitorStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $active = fake()->boolean();
    $threshold = fake()->numberBetween(-10000, 10000);

    $response = post(route('monitors.store'), [
        'active' => $active,
        'threshold' => $threshold,
    ]);

    $monitors = Monitor::query()
        ->where('active', $active)
        ->where('threshold', $threshold)
        ->get();
    expect($monitors)->toHaveCount(1);
    $monitor = $monitors->first();

    $response->assertRedirect(route('monitors.index'));
    $response->assertSessionHas('monitor.id', $monitor->id);
});


test('show displays view', function (): void {
    $monitor = Monitor::factory()->create();

    $response = get(route('monitors.show', $monitor));

    $response->assertOk();
    $response->assertViewIs('monitor.show');
    $response->assertViewHas('monitor');
});


test('edit displays view', function (): void {
    $monitor = Monitor::factory()->create();

    $response = get(route('monitors.edit', $monitor));

    $response->assertOk();
    $response->assertViewIs('monitor.edit');
    $response->assertViewHas('monitor');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\MonitorController::class,
        'update',
        \App\Http\Requests\MonitorUpdateRequest::class
    );

test('update redirects', function (): void {
    $monitor = Monitor::factory()->create();
    $active = fake()->boolean();
    $threshold = fake()->numberBetween(-10000, 10000);

    $response = put(route('monitors.update', $monitor), [
        'active' => $active,
        'threshold' => $threshold,
    ]);

    $monitor->refresh();

    $response->assertRedirect(route('monitors.index'));
    $response->assertSessionHas('monitor.id', $monitor->id);

    expect($active)->toEqual($monitor->active);
    expect($threshold)->toEqual($monitor->threshold);
});


test('destroy deletes and redirects', function (): void {
    $monitor = Monitor::factory()->create();

    $response = delete(route('monitors.destroy', $monitor));

    $response->assertRedirect(route('monitors.index'));

    assertModelMissing($monitor);
});

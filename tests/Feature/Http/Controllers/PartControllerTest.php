<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Manufacturer;
use App\Models\Part;
use App\Models\PartType;
use function Pest\Faker\fake;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(\JMac\Testing\Traits\AdditionalAssertions::class);

test('index displays view', function (): void {
    $parts = Part::factory()->count(3)->create();

    $response = get(route('parts.index'));

    $response->assertOk();
    $response->assertViewIs('part.index');
    $response->assertViewHas('parts');
});


test('create displays view', function (): void {
    $response = get(route('parts.create'));

    $response->assertOk();
    $response->assertViewIs('part.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\PartController::class,
        'store',
        \App\Http\Requests\PartStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $status = fake()->randomElement(
        ['active', 'inactive']
    );
    $number = fake()->word();
    $name = fake()->name();
    $sku = fake()->word();
    $enabled = fake()->boolean();
    $inventory = fake()->numberBetween(0, 10000);
    $unit_price = fake()->numberBetween(1, 1000000);
    $weight = fake()->numberBetween(1, 10000);
    $weight_unit = fake()->randomElement([
        'kg',
        'g',
        'mg',
        'lb',
        'oz',
        'st',
        'ton',
        'm',
        'cm',
        'mm',
        'in',
        'ft',
        'yd',
    ]);
    $filename = fake()->word() . '.jpg';
    $part_type = PartType::factory()->create();
    $manufacturer = Manufacturer::factory()->create();

    $response = post(route('parts.store'), [
        'status' => $status,
        'number' => $number,
        'name' => $name,
        'sku' => $sku,
        'enabled' => $enabled,
        'inventory' => $inventory,
        'unit_price' => $unit_price,
        'weight' => $weight,
        'weight_unit' => $weight_unit,
        'filename' => $filename,
        'part_type_id' => $part_type->id,
        'manufacturer_id' => $manufacturer->id,
    ]);
    $response->assertSessionHasNoErrors();

    $parts = Part::query()
        ->where('status', $status)
        ->where('number', $number)
        ->where('name', $name)
        ->where('sku', $sku)
        ->where('enabled', $enabled)
        ->where('inventory', $inventory)
        ->where('unit_price', $unit_price)
        ->where('weight', $weight)
        ->where('weight_unit', $weight_unit)
        ->where('filename', $filename)
        ->where('part_type_id', $part_type->id)
        ->where('manufacturer_id', $manufacturer->id)
        ->get();
    expect($parts)->toHaveCount(1);
    $part = $parts->first();

    $response->assertRedirect(route('parts.index'));
    $response->assertSessionHas('part.id', $part->id);
});


test('show displays view', function (): void {
    $part = Part::factory()->create();

    $response = get(route('parts.show', $part));

    $response->assertOk();
    $response->assertViewIs('part.show');
    $response->assertViewHas('part');
});


test('edit displays view', function (): void {
    $part = Part::factory()->create();

    $response = get(route('parts.edit', $part));

    $response->assertOk();
    $response->assertViewIs('part.edit');
    $response->assertViewHas('part');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\PartController::class,
        'update',
        \App\Http\Requests\PartUpdateRequest::class
    );

test('update redirects', function (): void {
    $part = Part::factory()->create();
    $status = fake()->randomElement(
        ['active', 'inactive']
    );
    $number = fake()->word();
    $name = fake()->name();
    $sku = fake()->word();
    $enabled = fake()->boolean();
    $inventory = fake()->numberBetween(0, 10000);
    $unit_price = fake()->numberBetween(1, 1000000);
    $weight = fake()->numberBetween(1, 10000);
    $weight_unit = fake()->randomElement([
        'kg',
        'g',
        'mg',
        'lb',
        'oz',
        'st',
        'ton',
        'm',
        'cm',
        'mm',
        'in',
        'ft',
        'yd',
    ]);
    $filename = fake()->word() . '.jpg';
    $part_type = PartType::factory()->create();
    $manufacturer = Manufacturer::factory()->create();

    $response = put(route('parts.update', $part), [
        'status' => $status,
        'number' => $number,
        'name' => $name,
        'sku' => $sku,
        'enabled' => $enabled,
        'inventory' => $inventory,
        'unit_price' => $unit_price,
        'weight' => $weight,
        'weight_unit' => $weight_unit,
        'filename' => $filename,
        'part_type_id' => $part_type->id,
        'manufacturer_id' => $manufacturer->id,
    ]);

    $part->refresh();

    $response->assertRedirect(route('parts.index'));
    $response->assertSessionHas('part.id', $part->id);

    expect($status)->toEqual($part->status);
    expect($number)->toEqual($part->number);
    expect($name)->toEqual($part->name);
    expect($sku)->toEqual($part->sku);
    expect($enabled)->toEqual($part->enabled);
    expect($inventory)->toEqual($part->inventory);
    expect($unit_price)->toEqual($part->unit_price);
    expect($weight)->toEqual($part->weight);
    expect($weight_unit)->toEqual($part->weight_unit);
    expect($filename)->toEqual($part->filename);
    expect($part_type->id)->toEqual($part->part_type_id);
    expect($manufacturer->id)->toEqual($part->manufacturer_id);
});


test('destroy deletes and redirects', function (): void {
    $part = Part::factory()->create();

    $response = delete(route('parts.destroy', $part));

    $response->assertRedirect(route('parts.index'));

    assertSoftDeleted($part);
});

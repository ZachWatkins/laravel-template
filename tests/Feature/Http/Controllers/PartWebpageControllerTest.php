<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Part;
use App\Models\PartWebpage;
use function Pest\Faker\fake;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

test('index displays view', function (): void {
    $partWebpages = PartWebpage::factory()->count(3)->create();

    $response = get(route('part-webpages.index'));

    $response->assertOk();
    $response->assertViewIs('partWebpage.index');
    $response->assertViewHas('partWebpages');
});


test('create displays view', function (): void {
    $response = get(route('part-webpages.create'));

    $response->assertOk();
    $response->assertViewIs('partWebpage.create');
});


test('store uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\PartWebpageController::class,
        'store',
        \App\Http\Requests\PartWebpageStoreRequest::class
    );

test('store saves and redirects', function (): void {
    $status = fake()->randomElement(/** enum_attributes **/);
    $path = fake()->word();
    $title = fake()->sentence(4);
    $meta_title = fake()->word();
    $meta_description = fake()->word();
    $meta_keywords = fake()->word();
    $content = fake()->paragraphs(3, true);
    $part = Part::factory()->create();

    $response = post(route('part-webpages.store'), [
        'status' => $status,
        'path' => $path,
        'title' => $title,
        'meta_title' => $meta_title,
        'meta_description' => $meta_description,
        'meta_keywords' => $meta_keywords,
        'content' => $content,
        'part_id' => $part->id,
    ]);

    $partWebpages = PartWebpage::query()
        ->where('status', $status)
        ->where('path', $path)
        ->where('title', $title)
        ->where('meta_title', $meta_title)
        ->where('meta_description', $meta_description)
        ->where('meta_keywords', $meta_keywords)
        ->where('content', $content)
        ->where('part_id', $part->id)
        ->get();
    expect($partWebpages)->toHaveCount(1);
    $partWebpage = $partWebpages->first();

    $response->assertRedirect(route('partWebpages.index'));
    $response->assertSessionHas('partWebpage.id', $partWebpage->id);
});


test('show displays view', function (): void {
    $partWebpage = PartWebpage::factory()->create();

    $response = get(route('part-webpages.show', $partWebpage));

    $response->assertOk();
    $response->assertViewIs('partWebpage.show');
    $response->assertViewHas('partWebpage');
});


test('edit displays view', function (): void {
    $partWebpage = PartWebpage::factory()->create();

    $response = get(route('part-webpages.edit', $partWebpage));

    $response->assertOk();
    $response->assertViewIs('partWebpage.edit');
    $response->assertViewHas('partWebpage');
});


test('update uses form request validation')
    ->assertActionUsesFormRequest(
        \App\Http\Controllers\PartWebpageController::class,
        'update',
        \App\Http\Requests\PartWebpageUpdateRequest::class
    );

test('update redirects', function (): void {
    $partWebpage = PartWebpage::factory()->create();
    $status = fake()->randomElement(/** enum_attributes **/);
    $path = fake()->word();
    $title = fake()->sentence(4);
    $meta_title = fake()->word();
    $meta_description = fake()->word();
    $meta_keywords = fake()->word();
    $content = fake()->paragraphs(3, true);
    $part = Part::factory()->create();

    $response = put(route('part-webpages.update', $partWebpage), [
        'status' => $status,
        'path' => $path,
        'title' => $title,
        'meta_title' => $meta_title,
        'meta_description' => $meta_description,
        'meta_keywords' => $meta_keywords,
        'content' => $content,
        'part_id' => $part->id,
    ]);

    $partWebpage->refresh();

    $response->assertRedirect(route('partWebpages.index'));
    $response->assertSessionHas('partWebpage.id', $partWebpage->id);

    expect($status)->toEqual($partWebpage->status);
    expect($path)->toEqual($partWebpage->path);
    expect($title)->toEqual($partWebpage->title);
    expect($meta_title)->toEqual($partWebpage->meta_title);
    expect($meta_description)->toEqual($partWebpage->meta_description);
    expect($meta_keywords)->toEqual($partWebpage->meta_keywords);
    expect($content)->toEqual($partWebpage->content);
    expect($part->id)->toEqual($partWebpage->part_id);
});


test('destroy deletes and redirects', function (): void {
    $partWebpage = PartWebpage::factory()->create();

    $response = delete(route('part-webpages.destroy', $partWebpage));

    $response->assertRedirect(route('partWebpages.index'));

    assertModelMissing($partWebpage);
});

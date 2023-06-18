<?php

namespace Tests\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_models(): void
    {
        $user = User::factory()->create();
        Model::factory()->create(['user_id' => $user->id]);

        $response = $this
            ->actingAs($user)
            ->get('/api/models/');
        $response->assertStatus(200);
    }
}
